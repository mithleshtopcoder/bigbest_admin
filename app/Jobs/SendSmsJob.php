<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\SmsService;
use App\Models\Order;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // Default keeps backward compatibility with older serialized jobs
    protected string $channel = 'sms';
    protected string $templateKey;
    protected array $data;
    protected string $phoneNumber;

    public function __construct(string $templateKey, string $phoneNumber, array $data = [], string $channel = 'sms')
    {
        $this->templateKey = $templateKey;
        $this->phoneNumber = $phoneNumber;
        $this->data = $data;
        $this->channel = $channel ?: 'sms';
    }

    public function handle(SmsService $smsService): void
{
    // Check if this is an abandoned cart SMS
    if ($this->templateKey === 'otp_login' && !empty($this->data['customer_id'])) {
        
        // Check if customer has placed any order after adding to cart
        $orderExists = Order::where('customer_id', $this->data['customer_id'])
            ->where('created_at', '>=', now()->subHour()) // within last 1 hour
            ->exists();

        if ($orderExists) {
            \Log::info("Abandoned cart SMS skipped: order already placed", [
                'customer_id' => $this->data['customer_id']
            ]);
            return; // Stop sending SMS
        }
    }

    // Send SMS if not blocked
    $smsService->sendNow($this->templateKey, $this->phoneNumber, $this->data, $this->channel ?: 'sms');
}

}