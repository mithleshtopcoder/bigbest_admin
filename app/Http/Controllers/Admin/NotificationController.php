<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function pushNotifications()
    {
        return view('admin.notifications.push-notifications');
    }

    public function smsTemplates()
    {
        return view('admin.notifications.sms-templates');
    }

    public function emailTemplates()
    {
        return view('admin.notifications.email-templates');
    }

    public function orderAlerts()
    {
        return view('admin.notifications.order-alerts');
    }

    public function promotionalBroadcasts()
    {
        return view('admin.notifications.promotional-broadcasts');
    }
}

