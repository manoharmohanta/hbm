<?php

namespace App\Controllers;

use App\Models\UserModel;
use Config\Database;

class Hotel extends BaseController{
    private $db;
    private $userModel;
    public function __construct()
    {
        $this->db = Database::connect();
        $this->userModel = new UserModel();
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
            $user = $this->userModel->where('email', $email)->first();

            // print_r(sizeof($user));exit();

            if (!($user)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid email.', 'csrf_token' => csrf_hash()]);
            }

            // Ensure password verification only runs if user exists
            if (!password_verify($password, $user['password'])) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid password.', 'csrf_token' => csrf_hash()]);
            }

            // Check if the user has activated their email
            if (!$user['email_activation']) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Activate your email.', 'csrf_token' => csrf_hash()]);
            }

            // Check if the user is active
            if ($user['status'] !== 'active') {
                return $this->response->setJSON(['status' => 'error', 'message' => 'You are suspended from this platform.', 'csrf_token' => csrf_hash()]);
            }


            session()->set('user', $user);

            // Define role-based redirect URLs
            $redirectUrls = [
                1 => 'super-admin',       // super_admin
                2 => 'hotel-owner',       // hotel_owner
                3 => 'hotel-manager',     // hotel_manager
                4 => 'front-office',      // front_office
                5 => 'housekeeping',      // housekeeping
                6 => 'kitchen',           // kitchen
                7 => 'staff',             // staff
                8 => 'customer',          // customer
            ];

            // Get redirect URL based on role_id
            $redirectUrl = $redirectUrls[$user['role_id']] ?? 'hotel'; // Fallback URL

            session()->set('controller', $redirectUrl);

            $result = [
                'status' => 'success',
                'message' => 'Login successful!',
                'redirectUrl' => base_url($redirectUrl),
                'csrf_token' => csrf_hash(),
            ];

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
            ];

            // Validate user input using the UserModel's validation rules
            if (!$this->validate($this->userModel->validationRules)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => $this->validator->getErrors(),
                    'csrf_token' => csrf_hash()
                ]);
            }

            // Check if email already exists
            if ($this->userModel->where('email', $data['email'])->first()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'The email is already registered.',
                    'csrf_token' => csrf_hash()
                ]);
            }

            if ($this->userModel->insert($data)) {
                $result = [
                    'status' => 'success',
                    'message' => 'User registered successfully.',
                    'redirectUrl' => base_url('hotel/login'),
                    'csrf_token' => csrf_hash()
                ];
            } else {
                $result = ['status' => 'error', 'message' => 'Failed to register user.', 'csrf_token' => csrf_hash()];
            }

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
