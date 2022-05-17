<?php

namespace iBoot\Database\Migrations;

use CodeIgniter\Database\Migration;

class BootMenuSchedule extends Migration
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
            'time_from' => [
                'type' => 'TIME',
            ],
            'time_to' => [
                'type' => 'TIME',
            ],
            'day_of_week' => [
                'type'     => 'TINYINT',
                'unsigned' => true,
            ],
            'date' => [
                'type' => 'DATE',
            ],
            'boot_menu_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],
            'group_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],

        ]);
        $this->forge->addKey('boot_menu_id', true);
        $this->forge->addKey('image_id', true);
        $this->forge->addForeignKey('boot_menu_id', 'boot_menu', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('group_id', 'groups', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addUniqueKey('time_from, time_to, date, group_id');
        $this->forge->addUniqueKey('time_from, time_to, day_of_week, group_id');
        $this->forge->createTable('boot_menu_images');

        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('boot_menu_images');
    }
}
