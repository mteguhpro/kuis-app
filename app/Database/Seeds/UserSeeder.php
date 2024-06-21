<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        //
        $data = [
            'id' => 1,
            'username' => 'admin',
            'email'    => 'admin@web.test',
            'password'    => password_hash('password', PASSWORD_DEFAULT),
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $this->db->table('user')->insert($data);
    }
}
