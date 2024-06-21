<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserGroupSeeder extends Seeder
{
    public function run()
    {
        //
        $data = [
            'id' => 1,
            'user_id' => 1,
            'group_id'    => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $this->db->table('user_group')->insert($data);
    }
}
