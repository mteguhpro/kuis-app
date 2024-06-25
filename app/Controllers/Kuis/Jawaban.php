<?php

namespace App\Controllers\Kuis;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Jawaban extends BaseController
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
        $this->model = new \App\Models\Kuis\JawabanModel();
    }

    public function listData(){

    }


    public function create()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'soal_id' => 'required',
            'opsi_jawaban' => 'required',
            'jawaban_benar' => 'required',
        ]);
        if (! $validation->withRequest($this->request)->run()) {
            $errors = $validation->getErrors();
            return $this->response->setStatusCode(400)->setJSON([
                    'errors' => $errors,
                    'message' => array_values($errors)[0],
                ]);
        }
        $validData = $validation->getValidated();

        if($validData['jawaban_benar'] === 'YA'){
            //reset semua opsi jawaban benar yg lain
            $this->model->where('soal_id', $validData['soal_id'])->set(['is_true' => false])->update();
        }

        if($this->model->insert([
            'soal_id' => $validData['soal_id'],
            'keterangan' => $validData['opsi_jawaban'],
            'is_true' => $validData['jawaban_benar'] === 'YA' ? true : false,
        ])){
            return $this->response->setJSON([
                'message' => 'Berhasil simpan data',
                'id'=>$this->model->getInsertID()
            ]);
        }
        return $this->response->setStatusCode(400)->setJSON(['message' => 'Gagal simpan data']);
    }


    public function delete($id){
        $this->model->delete($id);

        return $this->response->setJSON([
            'code' => 200,
            'message' => 'Data Telah Dihapus',
        ]);
    }
}