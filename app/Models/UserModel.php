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
        'name'     => 'required|min_length[3]|max_length[25]',
        'email'    => 'required|valid_email', // Allow self-update for email |is_unique[users.email]
        'phone'    => 'required|numeric|max_length[10]',
        'password' => 'required|min_length[8]',
    ];

    protected $validationMessages = [
        'email' => [
            'is_unique' => 'The email is already registered.',
            'check_email_exists' => 'The provided email does not exist in our records.',
        ],
    ];
}