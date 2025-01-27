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

    // Set the before insert actions (e.g., hashing the password)
    protected $beforeInsert = ['hashPassword'];

    // Hash the password before inserting
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    // Insert the user data into the database
    public function registerUser($data)
    {
        try {
            // Validate the data first
            if (!$this->validate($data)) {
                return ['status' => 'error', 'message' => $this->errors()];
            }

            // Insert data using the query builder
            if ($this->insert($data)) {
                return ['status' => 'success', 'message' => 'User registered successfully.'];
            } else {
                return ['status' => 'error', 'message' => 'Failed to register user.'];
            }
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => 'Error: ' . $e->getMessage()];
        }
    }
}