<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model{
    protected $table      = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'email', 'phone', 'password', 'id_proof', 'email_activation', 'status', 'created_at', 'updated_at'];

    protected $useTimestamps = true; // Enables automatic timestamps
    protected $createdField = 'created_at'; // Column for created timestamp
    protected $updatedField = 'updated_at'; // Column for updated timestamp

    protected $useSoftDeletes = true; // Enables soft delete
    protected $deletedField = 'deleted_at'; // Column for soft deletes
    // Validation rules for registration
    protected $validationRules = [
        'name'     => 'required|string|max_length[255]',
        'email'    => 'required|is_unique[users.email,id,{id}]|valid_email',
        'phone'    => 'required|numeric|max_length[10]',
        'password' => 'required|min_length[8]',
    ];

    protected $validationMessages = [
        'email' => [
            'is_unique' => 'The email is already registered.',
            'check_email_exists' => 'The provided email does not exist in our records.',
        ],
    ];

    /**
     * Register a new user
     */
    public function registerUser($data){
        try {
            if (!$this->validate($data)) {
                return ['status' => 'error', 'message' => $this->errors(), 'csrf_token' => csrf_hash()];
            }

            if ($this->insert($data)) {
                return [
                    'status' => 'success',
                    'message' => 'User registered successfully.',
                    'redirectUrl' => base_url('hotel/login'),
                    'csrf_token' => csrf_hash()
                ];
            } else {
                return ['status' => 'error', 'message' => 'Failed to register user.', 'csrf_token' => csrf_hash()];
            }
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Error: ' . $e->getMessage(), 'csrf_token' => csrf_hash()];
        }
    }

    /**
     * Login user with email and password
     */
    public function loginUser($email, $password){
        $user = $this->select('users.*, roles.name as role_name')
                     ->join('roles', 'roles.id = users.role_id')
                     ->where('users.email', $email)
                     ->first();

        if (!$user) {
            return ['status' => 'error', 'message' => 'Invalid email.', 'csrf_token' => csrf_hash()];
        }

        if (!password_verify($password, $user['password'])) {
            return ['status' => 'error', 'message' => 'Invalid password.', 'csrf_token' => csrf_hash()];
        }

        if (!$user['email_activation']) {
            return ['status' => 'error', 'message' => 'Activate your email.', 'csrf_token' => csrf_hash()];
        }

        if ($user['status'] != 'active') {
            return ['status' => 'error', 'message' => 'You are suspended from this platform.', 'csrf_token' => csrf_hash()];
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

        return [
            'status' => 'success',
            'message' => 'Login successful!',
            'user' => [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role_id' => $user['role_id'],
            ],
            'redirectUrl' => base_url($redirectUrl),
            'csrf_token' => csrf_hash(),
        ];
    }

    public function updateUser($userId, $data){
        try {
            // Validate the data
            if (!$this->validate($data)) {
                return ['status' => 'error', 'message' => $this->errors(), 'csrf_token' => csrf_hash()];
            }

            // Hash the password if it's being updated
            if (!empty($data['password'])) {
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            } else {
                unset($data['password']); // Prevent overwriting with empty password
            }

            // Perform the update
            if ($this->update($userId, $data)) {
                return [
                    'status' => 'success',
                    'message' => 'User updated successfully.',
                    'redirectUrl' => base_url(session()->get('controller').'/profile'),
                    'csrf_token' => csrf_hash()
                ];
            } else {
                return ['status' => 'error', 'message' => 'Failed to update user.', 'csrf_token' => csrf_hash()];
            }
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Error: ' . $e->getMessage(), 'csrf_token' => csrf_hash()];
        }
    }
}