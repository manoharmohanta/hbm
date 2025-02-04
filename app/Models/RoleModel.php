<?php namespace App\Models;

use CodeIgniter\Model;

class RoleModel extends Model{
    protected $table      = 'roles';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['name'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules    = [
        'name' => 'required|min_length[3]|max_length[255]',
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'The role name is required.',
            'min_length' => 'The role name must be at least 3 characters long.',
            'max_length' => 'The role name cannot exceed 255 characters.',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Add a new role
     */
    public function addRole($data)
    {
        if ($this->insert($data)) {
            return [
                'status' => 'success',
                'message' => 'Role registered successfully.',
                'csrf_token' => csrf_hash()
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Failed to register role.',
                'errors' => $this->errors(),
                'csrf_token' => csrf_hash()
            ];
        }
    }

    /**
     * Update an existing role
     */
    public function updateRole($id, $data)
    {
        if ($this->update($id, $data)) {
            return [
                'status' => 'success',
                'message' => 'Role updated successfully.',
                'csrf_token' => csrf_hash()
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Failed to update role.',
                'errors' => $this->errors(),
                'csrf_token' => csrf_hash()
            ];
        }
    }

    /**
     * Delete a role
     */
    public function deleteRole($id)
    {
        if ($this->delete($id)) {
            return [
                'status' => 'success',
                'message' => 'Role deleted successfully.',
                'csrf_token' => csrf_hash()
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Failed to delete role.',
                'csrf_token' => csrf_hash()
            ];
        }
    }
}