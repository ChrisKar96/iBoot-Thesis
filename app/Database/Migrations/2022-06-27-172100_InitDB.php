<?php

/**
 * This file is part of iBoot.
 *
 * (c) 2021 Christos Karamolegkos <iboot@ckaramolegkos.gr>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace iBoot\Database\Migrations;

use CodeIgniter\Database\Migration;

class InitDB extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();

        // BOOT MENU
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
            ],
            'description' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'ipxe_block' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('boot_menu', true);
        // END BOOT MENU

        // BOOT MENU x IPXE_BLOCKS
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'boot_menu_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],
            'block_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],
            'key' => [
                'type'       => 'VARCHAR',
                'constraint' => '1',
                'null'       => true,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey(['boot_menu_id', 'image_id']);
        $this->forge->addForeignKey('boot_menu_id', 'boot_menu', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('block_id', 'ipxe_blocks', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('boot_menu_blocks', true);
        // END BOOT MENU x IPXE_BLOCKS

        // COMPUTERS
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
                'null'       => true,
            ],
            'uuid' => [
                'type'       => 'VARCHAR',
                'constraint' => '36',
            ],
            'mac' => [
                'type'       => 'CHAR',
                'constraint' => '17',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'lab' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
                'null'       => true,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('lab', 'labs', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('computers', true);
        // END COMPUTERS

        // COMPUTERS x GROUPS
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'group_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],
            'computer_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey(['group_id', 'computer_id']);
        $this->forge->addForeignKey('group_id', 'groups', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('computer_id', 'computers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('computer_groups', true);
        // END COMPUTERS x GROUPS

        // FORGOT PASSWORD TOKENS
        $this->forge->addField([
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],
            'token' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'exp_date' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);
        $this->forge->addPrimaryKey('user_id');
        $this->forge->addUniqueKey('token');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('forgot_password_tokens', true);
        // END FORGOT PASSWORD TOKENS

        // GROUPS
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
            ],
            'image_server_ip' => [
                'type'       => 'VARCHAR',
                'constraint' => '15',
            ],
            'image_server_path_prefix' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('groups', true);
        // END GROUPS

        // IPXE_BLOCKS
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
            ],
            'ipxe_block' => [
                'type' => 'TEXT',
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('ipxe_blocks', true);
        // END IPXE_BLOCKS

        // LABS
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
            ],
            'address' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => '15',
                'null'       => true,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('labs', true);
        // END LABS

        // SCHEDULES
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'time_from' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'time_to' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'day_of_week' => [
                'type'     => 'TINYINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'date' => [
                'type' => 'DATE',
                'null' => true,
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
            'isActive' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('boot_menu_id', 'boot_menu', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('group_id', 'groups', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addUniqueKey(['time_from', 'time_to', 'date', 'group_id']);
        $this->forge->addUniqueKey(['time_from', 'time_to', 'day_of_week', 'group_id']);
        $this->forge->createTable('schedules', true);
        // END SCHEDULES

        // USERS
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '40',
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '320',
            ],
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => '15',
                'null'       => true,
            ],
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => '40',
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'isAdmin' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'verifiedEmail' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('username');
        $this->forge->addUniqueKey('email');
        $this->forge->createTable('users', true);
        // END USERS

        // USERS x LABS
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 10,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],
            'lab_id' => [
                'type'       => 'INT',
                'constraint' => 10,
                'unsigned'   => true,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey(['user_id', 'lab_id']);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('lab_id', 'labs', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_labs', true);
        // END USERS x LABS

        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->forge->dropTable('boot_menu', true);
        $this->forge->dropTable('boot_menu_blocks', true);
        $this->forge->dropTable('computers', true);
        $this->forge->dropTable('computer_groups', true);
        $this->forge->dropTable('forgot_password_tokens', true);
        $this->forge->dropTable('groups', true);
        $this->forge->dropTable('ipxe_blocks', true);
        $this->forge->dropTable('labs', true);
        $this->forge->dropTable('schedules', true);
        $this->forge->dropTable('users', true);
        $this->forge->dropTable('user_labs', true);
    }
}
