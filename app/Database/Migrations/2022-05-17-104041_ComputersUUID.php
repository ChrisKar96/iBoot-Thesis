<?php

namespace iBoot\Database\Migrations;

use CodeIgniter\Database\Migration;

class ComputersUUID extends Migration
{
    public function up()
    {
        $fieldsToModify = [
            'name' => [
                'name'       => 'name',
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
        ];

        $fieldsToAdd = [
            'uuid' => [
                'type'       => 'VARCHAR',
                'constraint' => '36',
                'after'      => 'name',
            ],
            'validated' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
        ];

        $this->forge->dropColumn('computers', 'mac');
        $this->forge->dropColumn('computers', 'ipv4');
        $this->forge->dropColumn('computers', 'ipv6');

        $this->forge->modifyColumn('computers', $fieldsToModify);

        $this->forge->addColumn('computers', $fieldsToAdd);
    }

    public function down()
    {
        $fieldsToModify = [
            'name' => [
                'name'       => 'name',
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => false,
            ],
        ];

        $fieldsToAdd = [
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
        ];

        $this->forge->dropColumn('computers', 'uuid');
        $this->forge->dropColumn('computers', 'validated');

        $this->forge->modifyColumn('computers', $fieldsToModify);

        $this->forge->addColumn('computers', $fieldsToAdd);
    }
}
