<?php

namespace App\Controllers;

use App\Models\UserModel;

class Hotel extends BaseController{
    public function index(){
        return view('welcome_message');
    }
    public function login(){
        return view('template/page-login');
    }
    public function register(){
        // Handle HTMX POST request
        if ($this->request->isAJAX() || $this->request->hasHeader('HX-Request')) {
            $validation = \Config\Services::validation();

            // Validate form inputs
            $validation->setRules([
                'name' => 'required|min_length[3]|max_length[255]',
                'email' => 'required|valid_email|is_unique[users.email]',
                'phone' => 'required|numeric|min_length[10]|max_length[15]|is_unique[users.phone]',
                'password' => 'required|min_length[6]',
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'errors' => $validation->getErrors(),
                ]);
            }

            // Collect user input data
            $data = [
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
                'phone' => $this->request->getPost('phone'),
                'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT), // Hash the password
            ];

            // Create an instance of UserModel and register the user
            $userModel = new UserModel();
            $result = $userModel->registerUser($data);

            // Return JSON response
            return $this->response->setJSON($result)
                                ->setHeader('X-CSRF-TOKEN', csrf_hash());
        }
        return view('template/page-register');
    }
    public function forget_password(){
        return view('template/page-forget');
    }
    public function otp(){
        return view('template/page-otp');
    }
}
