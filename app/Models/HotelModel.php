<?php
namespace App\Models;

use CodeIgniter\Model;

class HotelModel extends Model
{
    protected $table            = 'hotels';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields    = [
        'name', 'address', 'phone', 'email_id', 'wifi_user_name', 'wifi_password', 'user_id', 'created_at', 'updated_at'
    ];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    public function getHotel($hotelId)
    {
        return $this->where('id', $hotelId)->first();
    }

    public function getHotelsByUser($userId)
    {
        return $this->where('user_id', $userId)->findAll();
    }

    public function addHotel($data)
    {
        if (!$this->validate($data)) {
            return ['status' => 'error', 'message' => $this->errors()];
        }
        
        if ($this->insert($data)) {
            return ['status' => 'success', 'message' => 'Hotel added successfully.', 'csrf_token' => csrf_hash()];
        }
        return ['status' => 'error', 'message' => 'Failed to add hotel.', 'csrf_token' => csrf_hash()];
    }

    public function updateHotel($hotelId, $data)
    {
        if (!$this->validate($data)) {
            return ['status' => 'error', 'message' => $this->errors()];
        }
        
        if ($this->update($hotelId, $data)) {
            return ['status' => 'success', 'message' => 'Hotel updated successfully.', 'csrf_token' => csrf_hash()];
        }
        return ['status' => 'error', 'message' => 'Failed to update hotel.', 'csrf_token' => csrf_hash()];
    }

    public function deleteHotel($hotelId)
    {
        if ($this->delete($hotelId)) {
            return ['status' => 'success', 'message' => 'Hotel deleted successfully.', 'csrf_token' => csrf_hash()];
        }
        return ['status' => 'error', 'message' => 'Failed to delete hotel.', 'csrf_token' => csrf_hash()];
    }
}