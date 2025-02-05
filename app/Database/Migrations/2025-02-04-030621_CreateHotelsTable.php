<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateHotelsTable extends Migration
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
            'hotel_owner_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true
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
            ],
            'deleted_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('hotels');
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('hotels');
    }
}