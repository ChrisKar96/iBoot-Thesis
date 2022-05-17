<?php

namespace iBoot\Database\Seeds;

use CodeIgniter\Database\Seeder;

class OsImageArchsSeeder extends Seeder
{
    private $os_image_archs = [
        ['id' => '1', 'name' => 'amd64', 'description' => '64-bit PC (amd64)'],
        ['id' => '2', 'name' => 'arm64', 'description' => '64-bit ARM (AArch64)'],
        ['id' => '3', 'name' => 'armel', 'description' => 'EABI ARM (armel)'],
        ['id' => '4', 'name' => 'armhf', 'description' => 'Hard Float ABI ARM (armhf)'],
        ['id' => '5', 'name' => 'i386', 'description' => '32-bit PC (i386)'],
        ['id' => '6', 'name' => 'mipsel', 'description' => 'MIPS (little endian)'],
        ['id' => '7', 'name' => 'mips64el', 'description' => '64-bit MIPS (little endian)'],
        ['id' => '8', 'name' => 'ppc64el', 'description' => 'POWER Processors'],
        ['id' => '9', 'name' => 's390x', 'description' => 'IBM System z'],
    ];

    public function run()
    {
        foreach ($this->os_image_archs as $arch) {
            $this->db->table('os_image_archs')->insert($arch);
        }
    }
}
