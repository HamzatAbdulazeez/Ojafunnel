<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomePageController extends Controller
{
    //
    public function index()
    {
        return view('frontend.index');
    }
    //  Faqs
    public function faqs()
    {
        return view('frontend.faqs');
    }
    // Contact-Us
    public function contact()
    {
        return view('frontend.contact');
    }
    // Login
    public function login()
    {
        Auth::logout();

        return view('auth.login');
    }
    // Sign Up
    public function signup()
    {
        return view('auth.signup');
    }
    // Forgot
    public function forgot()
    {
        return view('auth.forgot');
    }
    // ResetPassword
    public function resetpassword()
    {
        return view('auth.reset');
    }
    // Market Automation
    public function marketauto()
    {
        return view('frontend.marketauto');
    }
    // Pgae Builder
    public function pagebuilder()
    {
        return view('frontend.pagebuilder');
    }
    // Privacy
    public function privacy()
    {
        return view('frontend.privacy');
    }
    // Terms
    public function terms()
    {
        return view('frontend.terms');
    }
    // EmailMarketing
    public function emailmarketing()
    {
        return view('frontend.emailmarketing');
    }
    // Chat Automation
    public function chatautomation()
    {
        return view('frontend.chatautomation');
    }
}