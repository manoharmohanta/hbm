<?php
namespace App\Models;

use CodeIgniter\Model;

class UHORelationModel extends Model{
    protected $table = 'u_h_o_relation';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = ['user_id', 'hotel_id', 'hotel_owner_id'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $useSoftDeletes = true; // Enables soft delete
    protected $deletedField = 'deleted_at'; // Column for soft deletes
    
    protected $validationRules = [
        'user_id' => 'required|integer',
        'hotel_id' => 'required|integer'
    ];
}