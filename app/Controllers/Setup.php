<?php
namespace App\Controllers;

use CodeIgniter\Controller;

class Setup extends Controller
{
    public function index() {
        return view('template/setup');
    }

    public function first_setup(){
        $db = \Config\Database::connect();
        $forge = \Config\Database::forge();

        // Roles Table
        if (!$db->tableExists('roles')) {
            $forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true
                ],
                'name' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255
                ],
                'created_at' => [
                    'type' => 'TIMESTAMP',
                    'null' => true
                ],
                'updated_at' => [
                    'type' => 'TIMESTAMP',
                    'null' => true
                ]
            ]);
            $forge->addKey('id', true);
            $forge->createTable('roles');

            // Insert default roles with created_at
            $db->table('roles')->insertBatch([
                ['name' => 'super_admin', 'created_at' => date('Y-m-d H:i:s')], // Can view all Hotels & Owner Details
                ['name' => 'hotel_owner', 'created_at' => date('Y-m-d H:i:s')], // Can view all Hotels & Manager & Staff & Rooms & customers
                ['name' => 'hotel_manager', 'created_at' => date('Y-m-d H:i:s')], // Can view Staff & Rooms & customers
                ['name' => 'staff', 'created_at' => date('Y-m-d H:i:s')], // Can view Rooms & customers & Orders
                ['name' => 'customer', 'created_at' => date('Y-m-d H:i:s')], // Can see the room details, Wifi Details and request for house keeping
                ['name' => 'housekeeping', 'created_at' => date('Y-m-d H:i:s')], // Update the housekeeping activity (Red - cleaning, orange - booked , Green - Empty & clean)
                ['name' => 'front_office', 'created_at' => date('Y-m-d H:i:s')], // Check in & check out & billing
                ['name' => 'kitchen', 'created_at' => date('Y-m-d H:i:s')] // Order placed - Red , Order completed - Green, Preparing - Orange
            ]);
        }

        // Users Table
        if (!$db->tableExists('users')) {
            $forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true
                ],
                'name' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255
                ],
                'email' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'unique' => true
                ],
                'phone' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'unique' => true
                ],
                'password' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255
                ],
                'id_proof' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255
                ],
                'role_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'default' => 2
                ],
                'email_activation' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'unsigned' => true,
                    'default' => 0
                ],
                'status' => [
                    'type' => 'ENUM',
                    'constraint' => ['active', 'suspended'],
                    'default' => 'active'
                ],
                'created_at' => [
                    'type' => 'TIMESTAMP',
                    'null' => true
                ],
                'updated_at' => [
                    'type' => 'TIMESTAMP',
                    'null' => true
                ]
            ]);
            $forge->addKey('id', true);
            $forge->createTable('users');
        }

        // Hotels Table
        if (!$db->tableExists('hotels')) {
            $forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true
                ],
                'name' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255
                ],
                'address' => [
                    'type' => 'TEXT'
                ],
                'phone' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255
                ],
                'email_id' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255
                ],
                'wifi_user_name' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255
                ],
                'wifi_password' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255
                ],
                'user_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true
                ],
                'created_at' => [
                    'type' => 'TIMESTAMP',
                    'null' => true
                ],
                'updated_at' => [
                    'type' => 'TIMESTAMP',
                    'null' => true
                ]
            ]);
            $forge->addKey('id', true);
            $forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
            $forge->createTable('hotels');
        }

        // Managers Table
        if (!$db->tableExists('managers')) {
            $forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true
                ],
                'user_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true
                ],
                'hotel_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true
                ],
                'created_at' => [
                    'type' => 'TIMESTAMP',
                    'null' => true
                ],
                'updated_at' => [
                    'type' => 'TIMESTAMP',
                    'null' => true
                ]
            ]);
            $forge->addKey('id', true);
            $forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
            $forge->addForeignKey('hotel_id', 'hotels', 'id', 'CASCADE', 'CASCADE');
            $forge->createTable('managers');
        }

        // Staff Table
        if (!$db->tableExists('staff')) {
            $forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true
                ],
                'name' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255
                ],
                'role_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true
                ],
                'manager_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true
                ],
                'hotel_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true
                ],
                'created_at' => [
                    'type' => 'TIMESTAMP',
                    'null' => true
                ],
                'updated_at' => [
                    'type' => 'TIMESTAMP',
                    'null' => true
                ]
            ]);
            $forge->addKey('id', true);
            $forge->addForeignKey('manager_id', 'managers', 'id', 'CASCADE', 'CASCADE');
            $forge->addForeignKey('hotel_id', 'hotels', 'id', 'CASCADE', 'CASCADE');
            $forge->createTable('staff');
        }

        // Rooms Table
        if (!$db->tableExists('rooms')) {
            $forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true
                ],
                'hotel_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true
                ],
                'room_number' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50
                ],
                'type' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100
                ],
                'price' => [
                    'type' => 'DECIMAL',
                    'constraint' => '10,2'
                ],
                'status' => [
                    'type' => 'ENUM',
                    'constraint' => ['available', 'cleaning', 'occupied', 'clean'],
                    'default' => 'available'
                ],
                'created_at' => [
                    'type' => 'TIMESTAMP',
                    'null' => true
                ],
                'updated_at' => [
                    'type' => 'TIMESTAMP',
                    'null' => true
                ]
            ]);
            $forge->addKey('id', true);
            $forge->addForeignKey('hotel_id', 'hotels', 'id', 'CASCADE', 'CASCADE');
            $forge->createTable('rooms');
        }

        // Room Bookings Table
        if (!$db->tableExists('room_bookings')) {
            $forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true
                ],
                'room_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true
                ],
                'user_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true
                ],
                'check_in_date' => [
                    'type' => 'DATE'
                ],
                'check_out_date' => [
                    'type' => 'DATE'
                ],
                'status' => [
                    'type' => 'ENUM',
                    'constraint' => ['booked', 'checked_in', 'checked_out', 'cancelled'],
                    'default' => 'booked'
                ],
                'created_at' => [
                    'type' => 'TIMESTAMP',
                    'null' => true
                ],
                'updated_at' => [
                    'type' => 'TIMESTAMP',
                    'null' => true
                ]
            ]);
            $forge->addKey('id', true);
            $forge->addForeignKey('room_id', 'rooms', 'id', 'CASCADE', 'CASCADE');
            $forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
            $forge->createTable('room_bookings');
        }

        // Menu Items Table
        if (!$db->tableExists('menu_items')) {
            $forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true
                ],
                'hotel_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true
                ],
                'name' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255
                ],
                'description' => [
                    'type' => 'TEXT',
                    'null' => true
                ],
                'price' => [
                    'type' => 'DECIMAL',
                    'constraint' => '10,2'
                ],
                'availability' => [
                    'type' => 'ENUM',
                    'constraint' => ['available', 'unavailable'],
                    'default' => 'available'
                ],
                'created_at' => [
                    'type' => 'TIMESTAMP',
                    'null' => true
                ],
                'updated_at' => [
                    'type' => 'TIMESTAMP',
                    'null' => true
                ]
            ]);
            $forge->addKey('id', true);
            $forge->addForeignKey('hotel_id', 'hotels', 'id', 'CASCADE', 'CASCADE');
            $forge->createTable('menu_items');
        }

        // Orders Table
        if (!$db->tableExists('orders')) {
            $forge->addField([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true
                ],
                'user_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true
                ],
                'room_booking_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true
                ],
                'menu_item_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true
                ],
                'quantity' => [
                    'type' => 'INT',
                    'constraint' => 11
                ],
                'total_price' => [
                    'type' => 'DECIMAL',
                    'constraint' => '10,2'
                ],
                'status' => [
                    'type' => 'ENUM',
                    'constraint' => ['pending', 'preparing', 'completed', 'cancelled'],
                    'default' => 'pending'
                ],
                'created_at' => [
                    'type' => 'TIMESTAMP',
                    'null' => true
                ],
                'updated_at' => [
                    'type' => 'TIMESTAMP',
                    'null' => true
                ]
            ]);
            $forge->addKey('id', true);
            $forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
            $forge->addForeignKey('menu_item_id', 'menu_items', 'id', 'CASCADE', 'CASCADE');
            $forge->createTable('orders');
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Database tables created successfully.']);
    }
}
