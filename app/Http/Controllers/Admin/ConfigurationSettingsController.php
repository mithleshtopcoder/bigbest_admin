<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AppSetting;
use App\Helpers\MyHelper;

class ConfigurationSettingsController extends Controller
{
    public function rolesPermissions()
    {
        return view('configuration-settings.roles-permissions.index');
    }

    public function optionMaster()
    {
        return view('configuration-settings.option-master.index');
    }

    /**
     * Show Company Setup Page
     */
    public function companySetup()
    {
        $settings = AppSetting::first();
        return view('configuration-settings.company-setup.index', compact('settings'));
    }

    /**
     * Store / Update Company Setup (TAB-WISE SAFE)
     */
    public function storeCompanySetup(Request $request)
    {
        $tab = $request->tab; // general | localization | social | content | delivery | maintenance

        $settings = AppSetting::firstOrCreate([]);

        /**
         * TAB-WISE VALIDATION + SAVE
         */
        switch ($tab) {

            /* ================= GENERAL ================= */
            case 'general':
                $data = $request->validate([
                    'company_name'  => 'nullable|string|max:255',
                    'company_title' => 'nullable|string|max:255',
                    'url'           => 'nullable|string|max:255',
                    'email'         => 'nullable|email',
                    'phone'         => 'nullable|string|max:30',
                    'address'       => 'nullable|string',
                    'logo'          => 'nullable|image|mimes:png,jpg,jpeg,svg',
                    'favicon'       => 'nullable|image|mimes:png,jpg,jpeg,ico',
                    'header'   => 'nullable|image|mimes:png,jpg,jpeg,svg',
                    'template' => 'nullable|image|mimes:png,jpg,jpeg,svg',

                ]);

                if ($request->hasFile('logo')) {
    $data['logo'] = MyHelper::uploadImage(
        $request->file('logo'),
        'settings'
    );
}

                if ($request->hasFile('favicon')) {
    $data['small_logo'] = MyHelper::uploadImage(
        $request->file('favicon'),
        'settings'
    );
}

if ($request->hasFile('header')) {
    $data['header'] = MyHelper::uploadImage($request->file('header'), 'settings');
}

if ($request->hasFile('template')) {
    $data['template'] = MyHelper::uploadImage($request->file('template'), 'settings');
}

                $settings->fill($data);
                break;

            /* ================= LOCALIZATION ================= */
            case 'localization':
                $data = $request->validate([
                    'currency'        => 'nullable|string|max:10',
                    'currency_symbol' => 'nullable|string|max:10',
                    'timezone'        => 'nullable|string|max:50',
                    'language'        => 'nullable|string|max:10',
                ]);

                $settings->fill($data);
                break;

            /* ================= SOCIAL ================= */
            case 'social':
                $data = $request->validate([
                    'facebook_url'   => 'nullable|url',
                    'twitter_url'    => 'nullable|url',
                    'instagram_url'  => 'nullable|url',
                    'youtube_url'    => 'nullable|url',
                    'linkedin_url'   => 'nullable|url',
                    'whatsapp_number'=> 'nullable|string|max:20',
                ]);

                $settings->fill($data);
                break;

            /* ================= CONTENT ================= */
            case 'content':
                $data = $request->validate([
                    'about_us'    => 'nullable|string',
                    'description' => 'nullable|string',
                ]);

                $settings->fill($data);
                break;

            /* ================= DELIVERY ================= */
            case 'delivery':
                $data = $request->validate([
                    'min_order_amount'        => 'nullable|numeric',
                    'delivery_charge'         => 'nullable|numeric',
                    'free_delivery_threshold' => 'nullable|numeric',
                    'delivery_tax_percent' => 'nullable|numeric'
                ]);

                // Avoid NOT NULL crash
                $data['min_order_amount'] = $data['min_order_amount'] ?? $settings->min_order_amount ?? 0;
                $data['delivery_charge']  = $data['delivery_charge'] ?? $settings->delivery_charge ?? 0;
                $data['delivery_tax_percent']  = $data['delivery_tax_percent'] ?? $settings->delivery_tax_percent ?? 0;


                $settings->fill($data);
                break;

/* ================= PAYMENT ================= */
            case 'payment':
    $data = $request->validate([
        'razorpay_key'            => 'nullable|string',
        'razorpay_secret'         => 'nullable|string',
        'razorpay_webhook_secret' => 'nullable|string',
    ]);

    $settings->fill($data);
    break;
/* ================= MAIL ================= */
case 'mail':
    $data = $request->validate([
        'mail_mailer'        => 'nullable|string',
        'mail_host'          => 'nullable|string',
        'mail_port'          => 'nullable|string',
        'mail_username'      => 'nullable|string',
        'mail_password'      => 'nullable|string',
        'mail_encryption'    => 'nullable|string',
        'mail_from_address'  => 'nullable|email',
        'mail_from_name'     => 'nullable|string',
        'mail_bcc_address'   => 'nullable|email',
    ]);

    $settings->fill($data);
    break;
/* ================= SMS ================= */
case 'sms':
    $data = $request->validate([
        'sms_provider'     => 'nullable|string',
        'sms_key'          => 'nullable|string',
        'sms_secret'       => 'nullable|string',
        'sms_sender_id'    => 'nullable|string',
        'sms_template_id'  => 'nullable|string',
    ]);

    $settings->fill($data);
    break;
/* ================= POS ================= */
case 'pos':
    $data = $request->validate([
        'pos_enabled' => 'required|boolean',
    ]);

    $settings->fill($data);
    break;

            /* ================= MAINTENANCE ================= */
            case 'maintenance':
                $data = $request->validate([
                    'maintenance_message' => 'nullable|string',
                ]);

                $data['is_maintenance_mode'] = $request->has('maintenance_mode');

                $settings->fill($data);
                break;
        }

        /**
         * GLOBAL SAFETY DEFAULTS (FIRST INSERT ONLY)
         */
        $settings->currency        = $settings->currency ?? 'INR';
        $settings->currency_symbol = $settings->currency_symbol ?? '₹';
        $settings->timezone        = $settings->timezone ?? 'Asia/Kolkata';
        $settings->language        = $settings->language ?? 'en';
        $settings->min_order_amount = $settings->min_order_amount ?? 0;
        $settings->delivery_charge  = $settings->delivery_charge ?? 0;

        $settings->save();

        return redirect()
            ->route('configuration-settings.company-setup')
            ->with('success', ucfirst($tab).' settings updated successfully');
    }

    public function paymentMethods()
    {
        return view('configuration-settings.payment-methods.index');
    }

    public function taxSettings()
    {
        return view('configuration-settings.tax-settings.index');
    }
}