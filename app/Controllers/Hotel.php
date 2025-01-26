<?php

namespace App\Controllers;

class Hotel extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }
    public function login(){
        return view('template/page-login');
    }
    public function sign_up(){
        return view('template/page-register');
    }
    public function forget_password(){
        return view('template/page-forget');
    }
}
