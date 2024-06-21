<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StartDataSeeder extends Seeder
{
    public function run()
    {
        //
        $this->call('UserSeeder');
        $this->call('GroupSeeder');
        $this->call('UserGroupSeeder');
    }
}
