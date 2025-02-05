<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRoomBookingsTable extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();
        $this->forge->addField([
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
            ],
            'deleted_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('room_id', 'rooms', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('room_bookings');
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('room_bookings');
    }
}