<?php

namespace App\Controllers\Kuis;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class MasterSoal extends BaseController
{
    private $toView;
    private $aksesData;
    private $model;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        // Add your code here.
        $this->model = new \App\Models\Kuis\SoalModel();
        $this->aksesData = $this->request->fetchGlobal('akses_data');
        $this->toView['hak_akses_kode'] = $this->aksesData['hak_akses_kode'];
    }

    public function html()
    {
        $this->toView['h1'] = 'Master Soal';
        return view('pages/kuis/master_soal', esc($this->toView));
    }

    public function index()
    {
        if(! $this->request->isAJAX()){
            return $this->html();
        }
        $data = $this->model->listSoal($this->request->getGet());
        return $this->response->setJSON($data);
    }

    public function create()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'pertanyaan' => 'required',
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
            'pertanyaan' => "required",
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

    public function edit($id = null){
        $this->toView['data'] = $this->model->where('id', $id)->withDeleted()->first();
        return view('pages/kuis/edit_soal', esc($this->toView));
    }

    public function delete($id){
        $this->model->delete($id);

        return $this->response->setJSON([
            'code' => 200,
            'message' => 'Data Telah Dihapus',
        ]);
    }
}