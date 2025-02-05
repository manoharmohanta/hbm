<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRolesTable extends Migration
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
            ],
            'deleted_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('roles');

        // Insert default roles
        $data = [
            ['name' => 'super_admin', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'hotel_owner', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'hotel_manager', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'staff', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'customer', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'housekeeping', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'front_office', 'created_at' => date('Y-m-d H:i:s')],
            ['name' => 'kitchen', 'created_at' => date('Y-m-d H:i:s')]
        ];
        $this->db->table('roles')->insertBatch($data);
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('roles');
    }
}