<?php

namespace iBoot\Database\Migrations;

use CodeIgniter\Database\Migration;

class BootMenuImages extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();

        $this->forge->addField([
            'boot_menu_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],
            'image_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],
        ]);
        $this->forge->addKey('boot_menu_id', true);
        $this->forge->addKey('image_id', true);
        $this->forge->addForeignKey('boot_menu_id', 'boot_menu', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('image_id', 'os_images', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('boot_menu_images', true);

        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('boot_menu_images');
    }
}
