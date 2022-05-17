<?php

namespace iBoot\Database\Migrations;

use CodeIgniter\Database\Migration;

class Computers extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();

        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => false,
            ],
            'mac' => [
                'type'       => 'CHAR',
                'constraint' => '17',
                'null'       => false,
            ],
            'ipv4' => [
                'type'       => 'VARCHAR',
                'constraint' => '15',
                'null'       => false,
            ],
            'ipv6' => [
                'type'       => 'VARCHAR',
                'constraint' => '45',
                'null'       => false,
            ],
            'room' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('room', 'rooms', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('computers', true);

        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('computers');
    }
}
