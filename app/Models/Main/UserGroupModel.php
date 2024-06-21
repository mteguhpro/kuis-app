<?php

namespace App\Models\Main;

use CodeIgniter\Model;

class UserGroupModel extends Model
{
    protected $table = 'user_group';
    protected $primaryKey = 'id';
    protected $useSoftDeletes = true;
    protected $returnType = 'object';

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    protected $allowedFields = ['user_id', 'group_id'];

    public function codeByUser($id){
        $builder = $this->db->table('user_group');
        $builder->select('group.code');
        $builder->join('group', 'group.id = user_group.group_id');
        $builder->where('user_group.user_id', $id);
        $query = $builder->get();
        $code = [];
        foreach($query->getResult() as $row){
            $code[] = $row->code;
        }
        return $code;
    }
}
