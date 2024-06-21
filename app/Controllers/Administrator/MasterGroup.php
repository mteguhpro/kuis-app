<?php

namespace App\Controllers\Administrator;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class MasterGroup extends BaseController
{
    private $groupModel;
    private $toView;
    private $aksesData;
    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        // Add your code here.
        $this->model = new \App\Models\Main\GroupModel();
        $this->aksesData = $this->request->fetchGlobal('akses_data');
        $this->toView['hak_akses_kode'] = $this->aksesData['hak_akses_kode'];
    }

    public function html()
    {
        $this->toView['h1'] = 'Master Group';
        return view('pages/administrator/master_group', esc($this->toView));
    }

    public function index()
    {
        if(! $this->request->isAJAX()){
            return $this->html();
        }
        $data = $this->model->listGroup($this->request->getGet());
        return $this->response->setJSON($data);
    }

    public function create()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'name' => 'required|alpha_numeric_space|min_length[4]|is_unique[group.name]',
            'code' => 'required|alpha_numeric|min_length[4]|is_unique[group.code]',
        ]);
        if (! $validation->withRequest($this->request)->run()) {
            $errors = $validation->getErrors();
            return $this->response->setStatusCode(400)->setJSON([
                    'errors' => $errors,
                    'message' => array_values($errors)[0],
                ]);
        }
        $validData = $validation->getValidated();
        if($this->model->insert($validData)){
            return $this->response->setJSON([
                'message' => 'Berhasil simpan data',
                'id'=>$this->model->getInsertID()
            ]);
        }
        return $this->response->setStatusCode(400)->setJSON(['message' => 'Gagal simpan data']);
    }

    public function update($id = null){
        $validation = \Config\Services::validation();
        $validation->setRules([
            'code' => "max_length[30]|min_length[2]|is_unique[group.code,id,{$id}]",
            'name'  => "max_length[50]|min_length[2]|is_unique[group.name,id,{$id}]",
        ]);
        if (! $validation->withRequest($this->request)->run()) {
            $errors = $validation->getErrors();
            return $this->response->setStatusCode(400)->setJSON([
                    'errors' => $errors,
                    'message' => array_values($errors)[0],
                ]);
        }
        $validData = $validation->getValidated();
        if($this->model->where('id', $id)->set($validData)->update()){
            return $this->response->setJSON([
                'message' => 'Berhasil update data',
                'id'=>$id
            ]);
        }
        return $this->response->setStatusCode(400)->setJSON(['message' => 'Gagal update data']);
    }
    public function delete($id){
        $this->model->delete($id);

        return $this->response->setJSON([
            'code' => 200,
            'message' => 'Data Telah Dihapus',
        ]);
    }
}