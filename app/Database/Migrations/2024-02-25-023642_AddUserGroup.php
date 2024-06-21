<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserGroup extends Migration
{
    public function up()
    {
        //
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => '11',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'           => 'INT',
                'constraint'     => '11',
                'unsigned'       => true,
            ],
            'group_id' => [
                'type'           => 'INT',
                'constraint'     => '11',
                'unsigned'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['user_id', 'group_id'], false, true, 'unique_user_id_group_id'); // gives UNIQUE KEY `unique_user_id_group_id` (`user_id`, `group_id`)
        
        $this->forge->addForeignKey('user_id', 'user', 'id', 'RESTRICT', 'RESTRICT', 'fk_user_id');
        // gives CONSTRAINT `fk_user_id` FOREIGN KEY(`user_id`) REFERENCES `user`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT

        $this->forge->addForeignKey('group_id', 'group', 'id', 'RESTRICT', 'RESTRICT', 'fk_group_id');
    
        $this->forge->createTable('user_group');
    }

    public function down()
    {
        //
        $this->forge->dropTable('user_group');
    }
}
