<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrdersTable extends Migration
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
            ],
            'deleted_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('menu_item_id', 'menu_items', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('orders');
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('orders');
    }
}