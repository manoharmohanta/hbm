<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\HotelModel;

class Hotel extends BaseController{
    public function index(){
        return view('welcome_message');
    }
    public function login(){
        // Handle HTMX POST request
        if ($this->request->isAJAX() || $this->request->hasHeader('HX-Request')) {
            $validation = \Config\Services::validation();

            // Validate form inputs
            $validation->setRules([
                'email' => 'required|valid_email',
                'password' => 'required|min_length[6]',
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'errors' => $validation->getErrors(),
                    'csrf_token' => csrf_hash(), // Include updated CSRF token
                ]);
            }

            // Retrieve user input
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            // Create an instance of UserModel and register the user
            $userModel = new UserModel();
            $result = $userModel->loginUser($email, $password);

            // Return JSON response
            return $this->response->setJSON($result);
        }
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
                'phone' => 'required|numeric|min_length[10]|max_length[10]|is_unique[users.phone]',
                'password' => 'required|min_length[6]',
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'errors' => $validation->getErrors(),
                    'csrf_token' => csrf_hash(), // Include updated CSRF token
                ]);
            }

            // Collect user input data
            $data = [
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
                'phone' => $this->request->getPost('phone'),
                'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT), // Hash the password
                'created_at' => date('Y-m-d H:i:s'),
            ];

            // Create an instance of UserModel and register the user
            $userModel = new UserModel();
            $result = $userModel->registerUser($data);

            // Return JSON response
            return $this->response->setJSON($result);
        }
        return view('template/page-register');
    }
    public function forget_password(){
        return view('template/page-forget');
    }
    public function otp(){
        return view('template/page-otp');
    }
    public function logout() {
        session()->destroy();
        return redirect()->to(base_url('hotel/login'));
    }
}
