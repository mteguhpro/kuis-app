<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddJawaban extends Migration
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
            'soal_id' => [
                'type'           => 'INT',
                'constraint'     => '11',
                'unsigned'       => true,
            ],
            'keterangan' => [
                'type'       => 'TEXT',
            ],
            'gambar' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'is_true' => [
                'type' => 'BOOLEAN',
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
        
        $this->forge->addForeignKey('soal_id', 'soal', 'id', 'RESTRICT', 'RESTRICT', 'fk_soal_id');
    
        $this->forge->createTable('jawaban');
    }

    public function down()
    {
        //
        $this->forge->dropTable('jawaban');
    }
}
