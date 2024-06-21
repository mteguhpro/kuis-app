<?php

namespace App\Libraries;

class TegDatatable
{
    private $builder;
    private $dataReq;

    public function __construct($builder, $dataReq){
        $this->builder = $builder;
        $this->dataReq = $dataReq;
    }

    public function searchable($search = []){
        $maxIndex = count($search) - 1;
        if($this->dataReq['search']['value']){
            foreach($search as $i => $col){
                if($i === 0){
                    $this->builder->groupStart();
                    $this->builder->Like($col, $this->dataReq['search']['value']);
                }else{
                    $this->builder->orLike($col, $this->dataReq['search']['value']);
                }
                if($i === ($maxIndex)){
                    $this->builder->groupEnd();
                }
            }
        }
        return $this;
    }

    public function columnOrder($column = []){
        $key = $this->dataReq['order'][0]['column'];
        $dir = $this->dataReq['order'][0]['dir'] ?? 'ASC';

        if(isset($column[$key])){
            $this->builder->orderBy($column[$key], $dir);
        }
        return $this;
    }

    public function result(){
        $limit = $this->dataReq['length'] ?? 10;
        $offset = $this->dataReq['start'] ?? 0;
        $this->builder->limit($limit, $offset);
        
        $res['recordsTotal'] = $this->builder->countAllResults(false);
        $res['recordsFiltered'] = $res['recordsTotal'];
        $res['data'] = $this->builder->get()->getResultArray();
        return $res;
    }

}
