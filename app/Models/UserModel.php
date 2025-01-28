<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';

    protected $allowedFields = ['name', 'email', 'phone', 'password', 'id_proof', 'emaail_activation', 'status', 'created_at', 'updated_at'];

    // Set the validation rules for the user data
    protected $validationRules = [
        'name'     => 'required|string|max_length[255]',
        'email'    => 'required|valid_email|is_unique[users.email]',
        'phone'    => 'required|string|max_length[15]',
        'password' => 'required|min_length[8]',
    ];

    // Set the error messages if validation fails
    protected $validationMessages = [
        'email' => [
            'is_unique' => 'The email is already registered.',
        ],
    ];

    // Insert the user data into the database
    public function registerUser($data){
        try {
            // Validate the data first
            if (!$this->validate($data)) {
                return ['status' => 'error', 'message' => $this->errors()];
            }

            // Insert data using the query builder
            if ($this->insert($data)) {
                return ['status' => 'success', 'message' => 'User registered successfully.', 'redirectUrl' => base_url('hotel/login'), 'csrf_token' => csrf_hash()];
            } else {
                return ['status' => 'error', 'message' => 'Failed to register user.', 'csrf_token' => csrf_hash()];
            }
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Error: ' . $e->getMessage(), 'csrf_token' => csrf_hash()];
        }
    }

    public function loginUser($email, $password){
        // Check if the user exists
        $user = $this->where('email', $email)->first();

        if (!$user) {
            return [
                'status' => 'error',
                'message' => 'Invalid email.',
                'csrf_token' => csrf_hash(),
            ];
        }

        // Verify the password
        if (!password_verify($password, $user['password'])) {
            return [
                'status' => 'error',
                'message' => 'Invalid password.',
                'csrf_token' => csrf_hash(),
            ];
        }

        if(!$user['email_activation']){
            return [
                'status' => 'error',
                'message' => 'Activate your email.',
                'csrf_token' => csrf_hash(),
            ];
        }

        if($user['status'] != 'active'){
            return [
                'status' => 'error',
                'message' => 'You are suspended from this platform.',
                'csrf_token' => csrf_hash(),
            ];
        }

        // Store all user data in session upon successful login
        session()->set('user', $user);

        // If successful, return success message and user data
        return [
            'status' => 'success',
            'message' => 'Login successful!',
            'user' => [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role_id' => $user['role_id'],
            ],
            'redirectUrl' => base_url('hotel/dashboard'),
            'csrf_token' => csrf_hash(),
        ];
    }
}