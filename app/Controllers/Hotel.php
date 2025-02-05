<?php

namespace App\Controllers;

use App\Models\UserModel;
use Config\Database;

class Hotel extends BaseController{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }
    public function index() {
        if(!$this->db->tableExists('migrations')){
            return redirect()->to(base_url('setup'));
        }
        return view('welcome_message');
    }
    public function login(){
        // Handle HTMX POST request
        if ($this->request->isAJAX() || $this->request->hasHeader('HX-Request')) {

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
