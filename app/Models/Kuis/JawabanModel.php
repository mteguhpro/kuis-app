<?php

namespace App\Models\Kuis;

use CodeIgniter\Model;

class JawabanModel extends Model
{
    protected $table = 'jawaban';
    protected $primaryKey = 'id';
    protected $useSoftDeletes = false;
    protected $returnType = 'object';

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = '';
    protected $updatedField  = '';

    protected $allowedFields = ['soal_id', 'keterangan', 'gambar', 'is_true'];

}
