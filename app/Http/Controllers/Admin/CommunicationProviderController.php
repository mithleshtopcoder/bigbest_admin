<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommunicationProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CommunicationProviderController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'channel' => ['required', 'string', 'max:50'],
            'provider_name' => ['nullable', 'string', 'max:255'],
            'base_url' => ['nullable', 'string', 'max:255'],
            'api_key' => ['nullable', 'string', 'max:255'],
            'api_secret' => ['nullable', 'string', 'max:255'],
            'sender_id' => ['nullable', 'string', 'max:255'],
            'route' => ['nullable', 'string', 'max:50'],
            'default_template_id' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = (bool)($data['is_active'] ?? true);

        if ($data['is_active']) {
            CommunicationProvider::where('channel', $data['channel'])
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        CommunicationProvider::create($data);

        return redirect()
            ->route('configuration-settings.company-setup', ['tab' => $data['channel']])
            ->with('success', strtoupper($data['channel']) . ' provider saved successfully');
    }

    public function update(Request $request, CommunicationProvider $communicationProvider): RedirectResponse
    {
        $data = $request->validate([
            'channel' => ['required', 'string', 'max:50'],
            'provider_name' => ['nullable', 'string', 'max:255'],
            'base_url' => ['nullable', 'string', 'max:255'],
            'api_key' => ['nullable', 'string', 'max:255'],
            'api_secret' => ['nullable', 'string', 'max:255'],
            'sender_id' => ['nullable', 'string', 'max:255'],
            'route' => ['nullable', 'string', 'max:50'],
            'default_template_id' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = (bool)($data['is_active'] ?? true);

        if ($data['is_active']) {
            CommunicationProvider::where('channel', $data['channel'])
                ->where('is_active', true)
                ->where('id', '!=', $communicationProvider->id)
                ->update(['is_active' => false]);
        }

        $communicationProvider->update($data);

        return redirect()
            ->route('configuration-settings.company-setup', ['tab' => $data['channel']])
            ->with('success', strtoupper($data['channel']) . ' provider updated successfully');
    }
}