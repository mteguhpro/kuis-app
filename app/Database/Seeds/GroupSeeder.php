<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class GroupSeeder extends Seeder
{
    public function run()
    {
        //
        $data = [
            [
                'id' => 1,
                'code' => 'administrator',
                'name'    => 'administrator',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];
        $this->db->table('group')->insertBatch($data);
    }
}
