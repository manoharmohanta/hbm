<?php

namespace App\Models;

use CodeIgniter\Model;

class RoomModel extends Model{
    protected $table      = 'rooms';
    protected $primaryKey = 'id';
    protected $allowedFields = ['hotel_id', 'room_number', 'type', 'price', 'status', 'created_at', 'updated_at'];

    protected $useTimestamps = true; // Enables automatic timestamps
    protected $createdField = 'created_at'; // Column for created timestamp
    protected $updatedField = 'updated_at'; // Column for updated timestamp

    protected $useSoftDeletes = true; // Enables soft delete
    protected $deletedField = 'deleted_at'; // Column for soft deletes
    
    // Optional: Define validation rules for the ENUM field
    protected $validationRules = [
        'status' => 'in_list[available,cleaning,occupied,clean]'
    ];
}

