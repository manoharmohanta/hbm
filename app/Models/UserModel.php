<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model{
    protected $table      = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'email', 'phone', 'password', 'role_id', 'id_proof', 'email_activation', 'status', 'created_at', 'updated_at'];

    protected $useTimestamps = true; // Enables automatic timestamps
    protected $createdField = 'created_at'; // Column for created timestamp
    protected $updatedField = 'updated_at'; // Column for updated timestamp

    protected $useSoftDeletes = true; // Enables soft delete
    protected $deletedField = 'deleted_at'; // Column for soft deletes
    // Validation rules for registration
    protected $validationRules = [
        'name'     => 'required|string|max_length[25]',
        // 'email'    => 'required|valid_email|is_unique[users.email,id,{id}]', // Allow self-update for email
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