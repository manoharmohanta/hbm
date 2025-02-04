<?php
namespace App\Models;

use CodeIgniter\Model;

class ManagerModel extends Model{
    protected $table = 'managers';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = ['user_id', 'hotel_id'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $validationRules = [
        'user_id' => 'required|integer',
        'hotel_id' => 'required|integer'
    ];
}