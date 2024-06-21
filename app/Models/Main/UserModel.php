<?php

namespace App\Models\Main;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'id';
    protected $useSoftDeletes = true;
    protected $returnType = 'object';

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $allowedFields = ['username', 'email', 'password'];

    public function listUser($dataGet){
        $listUser = $this->db->table('user')
            ->select('user.id, user.username, user.email, user.created_at')
            ->where('user.deleted_at', null);

        $datatable = new \App\Libraries\TegDatatable($listUser, $dataGet);
        $datatable->searchable(['user.username', 'user.email'])
            ->columnOrder(['user.username', 'user.email']);

        return $datatable->result();
    }

}
