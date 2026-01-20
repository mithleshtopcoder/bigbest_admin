<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CMSController extends Controller
{
    public function index()
    {
        return view('cms.company-setup.index');
    }

    public function aboutUs()
    {
        return view('cms.about-us.index');
    }

    public function contactUs()
    {
        return view('cms.contact-us.index');
    }

    public function policyMaster($type)
    {
        return view('cms.policy-master.index', ['type' => $type]);
    }

    public function banners()
    {
        return view('cms.banners.index');
    }

    public function socialMedia()
    {
        return view('cms.social-media.index');
    }

    public function faq()
    {
        return view('cms.faq.index');
    }
}

