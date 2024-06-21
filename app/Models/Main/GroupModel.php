<?php

namespace App\Models\Main;

use CodeIgniter\Model;

class GroupModel extends Model
{
    protected $table = 'group';
    protected $primaryKey = 'id';
    protected $useSoftDeletes = true;
    protected $returnType = 'object';

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = ['code', 'name'];

    
    public function listGroup($dataGet){
        $builder = $this->db->table('group')
            ->select('id, code, name, created_at, updated_at')
            ->where('deleted_at', null);

        $datatable = new \App\Libraries\TegDatatable($builder, $dataGet);
        $datatable->searchable(['name', 'code'])
            ->columnOrder(['name', 'code', 'created_at', 'updated_at']); //sama dengan tampilan/urutan kolom pada view

        return $datatable->result();
    }
}
