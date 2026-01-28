<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmsTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SmsTemplateController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $channel = $request->input('channel', 'sms');
        $data = $request->validate([
            'channel' => ['required', 'string', 'max:50'],
            'key' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-z0-9_]+$/',
                Rule::unique('sms_templates', 'key')->where(fn ($q) => $q->where('channel', $channel)),
            ],
            'name' => ['required', 'string', 'max:255'],
            'provider_template_id' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = (bool)($data['is_active'] ?? false);

        SmsTemplate::create($data);

        return redirect()
            ->route('configuration-settings.company-setup', ['tab' => $data['channel']])
            ->with('success', strtoupper($data['channel']) . ' template created successfully');
    }

    public function update(Request $request, SmsTemplate $smsTemplate): RedirectResponse
    {
        $channel = $request->input('channel', $smsTemplate->channel ?? 'sms');
        $data = $request->validate([
            'channel' => ['required', 'string', 'max:50'],
            'key' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-z0-9_]+$/',
                Rule::unique('sms_templates', 'key')
                    ->where(fn ($q) => $q->where('channel', $channel))
                    ->ignore($smsTemplate->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'provider_template_id' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = (bool)($data['is_active'] ?? false);

        $smsTemplate->update($data);

        return redirect()
            ->route('configuration-settings.company-setup', ['tab' => $data['channel']])
            ->with('success', strtoupper($data['channel']) . ' template updated successfully');
    }

    public function destroy(SmsTemplate $smsTemplate): RedirectResponse
    {
        $channel = $smsTemplate->channel ?? 'sms';
        $smsTemplate->delete();

        return redirect()
            ->route('configuration-settings.company-setup', ['tab' => $channel])
            ->with('success', strtoupper($channel) . ' template deleted successfully');
    }
}