<?php

namespace App\Services;

use App\Jobs\SendSmsJob;
use App\Models\AppSetting;
use App\Models\CommunicationProvider;
use App\Models\SmsTemplate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * Dispatch message to queue (recommended for most use cases).
     * Backward compatible: channel defaults to "sms".
     */
    public function dispatch(string $templateKey, string $phoneNumber, array $data = [], string $channel = 'sms'): void
    {
        SendSmsJob::dispatch($templateKey, $phoneNumber, $data, $channel);
    }

    /**
     * Dispatch message immediately (no queue worker required).
     * Backward compatible: channel defaults to "sms".
     */
    public function dispatchSync(string $templateKey, string $phoneNumber, array $data = [], string $channel = 'sms'): void
    {
        SendSmsJob::dispatchSync($templateKey, $phoneNumber, $data, $channel);
    }

    /**
     * Send message immediately (called by job).
     * Backward compatible: channel defaults to "sms".
     */
    public function sendNow(string $templateKey, string $phoneNumber, array $data = [], string $channel = 'sms'): bool
    {
        $template = SmsTemplate::where('channel', $channel)
            ->where('key', $templateKey)
            ->where('is_active', true)
            ->first();

        if (!$template) {
            Log::warning('Message template missing/inactive', [
                'channel' => $channel,
                'template_key' => $templateKey,
                'phone' => $phoneNumber,
            ]);
            return false;
        }

        $settings = AppSetting::first();
        $provider = CommunicationProvider::where('channel', $channel)
            ->where('is_active', true)
            ->latest('id')
            ->first();

        // Defaults (SMS keeps legacy fallbacks)
        $defaultBaseUrl = $channel === 'sms' ? (config('sms.base_url') ?: env('SMPP_URL')) : null;
        $defaultUsername = $channel === 'sms' ? (config('sms.username') ?: env('SMPP_USERNAME')) : null;
        $defaultPassword = $channel === 'sms' ? (config('sms.password') ?: env('SMPP_PASSWORD')) : null;
        $defaultSenderId = $channel === 'sms' ? (config('sms.sender_id') ?: env('SMPP_SENDER_ID')) : null;
        $defaultRoute = $channel === 'sms' ? (config('sms.route') ?: env('SMPP_ROUTE', '4')) : null;

        $baseUrl = $this->valueOrFallback(
            $provider?->base_url,
            $channel === 'sms' ? $settings?->sms_provider : null,
            $defaultBaseUrl
        );

        $username = $this->valueOrFallback(
            $provider?->api_key,
            $channel === 'sms' ? $settings?->sms_key : null,
            $defaultUsername
        );

        $password = $this->valueOrFallback(
            $provider?->api_secret,
            $channel === 'sms' ? $settings?->sms_secret : null,
            $defaultPassword
        );

        $senderId = $this->valueOrFallback(
            $provider?->sender_id,
            $channel === 'sms' ? $settings?->sms_sender_id : null,
            $defaultSenderId
        );

        $route = $this->valueOrFallback(
            $provider?->route,
            $defaultRoute
        );

        $providerTemplateId = $template->provider_template_id
            ?: ($provider?->default_template_id ?: null)
            ?: ($channel === 'sms' ? ($settings?->sms_template_id ?: null) : null);

        $message = $this->render($template->message, $data);

        if (!$baseUrl) {
            Log::error('Provider base_url missing', ['channel' => $channel]);
            return false;
        }

        $meta = is_array($provider?->meta ?? null) ? ($provider->meta ?? []) : [];
        $method = strtoupper((string)($meta['http_method'] ?? 'GET'));
        $paramMap = (array)($meta['param_map'] ?? []);
        $extraParams = (array)($meta['extra_params'] ?? []);

        // Standard payload keys
        $payload = [
            'username' => $username,
            'password' => $password,
            'senderid' => $senderId,
            'route' => $route,
            'number' => $phoneNumber,
            'message' => $message,
        ];

        if ($providerTemplateId) {
            $payload['templateid'] = $providerTemplateId;
        }

        // Rename params if provider meta defines mapping
        $params = [];
        foreach ($payload as $k => $v) {
            if ($v === null || (is_string($v) && trim($v) === '')) continue;
            $mapped = $paramMap[$k] ?? $k;
            $params[$mapped] = $v;
        }
        foreach ($extraParams as $k => $v) {
            $params[$k] = $v;
        }

        $finalUrl = rtrim($baseUrl, '?');

        try {
            $response = $method === 'POST'
                ? Http::asForm()->post($finalUrl, $params)
                : Http::get($finalUrl, $params);

            Log::info('Message sent', [
                'channel' => $channel,
                'phone' => $phoneNumber,
                'template' => $templateKey,
                'template_id' => $providerTemplateId,
                'response' => $response->body(),
            ]);

            return $response->successful();
        } catch (\Throwable $e) {
            Log::error('Message sending failed', [
                'channel' => $channel,
                'phone' => $phoneNumber,
                'template' => $templateKey,
                'error' => $e->getMessage(),
            ]);

            return false;
        }

//         Log::info('Message sent', [
//     'channel' => $channel,
//     'phone' => $phoneNumber,
//     'template' => $templateKey,
//     'template_id' => $providerTemplateId,
//     'response' => $response->body(),
// ]);
    }

    /**
     * Render message placeholders. Supports:
     * - {key}
     * - {#KEY#}
     * - [Key]
     * - #[Key]
     */
    public function render(string $message, array $data = []): string
    {
        foreach ($data as $key => $value) {
            $k = (string)$key;
            $v = (string)$value;

            $message = str_replace('{' . $k . '}', $v, $message);
            $message = str_replace('{#' . strtoupper($k) . '#}', $v, $message);
            $message = str_replace('[' . $this->titleKey($k) . ']', $v, $message);
            $message = str_replace('#[' . $this->titleKey($k) . ']', $v, $message);
        }

        return $message;
    }

    private function titleKey(string $k): string
    {
        // order_id -> OrderID, otp -> OTP, minutes -> Minutes
        $parts = preg_split('/[_\s]+/', $k) ?: [$k];
        $out = '';
        foreach ($parts as $p) {
            if ($p === '') continue;
            $out .= ucfirst(strtolower($p));
        }
        return $out;
    }

    private function valueOrFallback(?string $primary, mixed ...$fallbacks): ?string
    {
        if ($primary !== null && trim($primary) !== '') {
            return $primary;
        }

        foreach ($fallbacks as $f) {
            if ($f === null) continue;
            if (is_string($f) && trim($f) === '') continue;
            return is_string($f) ? $f : (string)$f;
        }

        return null;
    }
}