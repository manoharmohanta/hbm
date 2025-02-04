<?php
namespace App\Models;

use CodeIgniter\Model;

class HotelModel extends Model{
    protected $table            = 'hotels';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = [
        'name', 'address', 'phone', 'email_id', 'wifi_user_name', 'wifi_password', 'user_id', 'created_at', 'updated_at'
    ];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
}