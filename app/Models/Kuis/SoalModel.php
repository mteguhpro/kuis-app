<?php

namespace App\Models\Kuis;

use CodeIgniter\Model;

class SoalModel extends Model
{
    protected $table = 'soal';
    protected $primaryKey = 'id';
    protected $useSoftDeletes = true;
    protected $returnType = 'object';

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = ['pertanyaan', 'gambar', 'deleted_at'];

    
    public function listSoal($dataGet){
        $builder = $this->db->table($this->table)
            ->select('id, pertanyaan, gambar, created_at, updated_at, deleted_at');

        $datatable = new \App\Libraries\TegDatatable($builder, $dataGet);
        $datatable->searchable(['pertanyaan'])
            ->columnOrder(['id', 'pertanyaan', 'gambar', 'created_at', 'updated_at', 'deleted_at']); //sama dengan tampilan/urutan kolom pada view

        return $datatable->result();
    }
}
