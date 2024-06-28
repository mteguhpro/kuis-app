<?php

namespace App\Controllers\Kuis;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Exam extends BaseController
{
    private $soalModel;
    private $jawabanModel;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        // Add your code here.
        $this->soalModel = new \App\Models\Kuis\SoalModel();
        $this->jawabanModel = new \App\Models\Kuis\JawabanModel();
    }

    public function play()
    {
        return view('pages/kuis/play');
    }

    public function soal()
    {
        $listSoalTampil = $this->request->getPost('list-soal-tampil');
        if(is_array($listSoalTampil)){
            $soal = $this->soalModel->whereNotIn('id', $listSoalTampil)->first();
        }else{
            $soal = $this->soalModel->first();
        }
        if(!$soal){
            return $this->response->setJSON([
                'soal' => null,
                'jawaban' => null,
            ]);
        }
        $jawaban = $this->jawabanModel->where('soal_id', $soal->id)->get()->getResult();

        return $this->response->setJSON([
            'soal' => $soal,
            'jawaban' => $jawaban,
        ]);
    }

    public function hasil()
    {
        $jawabanTerpilih = $this->request->getPost('jawaban-terpilih');
        $jawaban = $this->jawabanModel
                    ->selectCount('id')
                    ->whereIn('id', $jawabanTerpilih)
                    ->where('is_true', true)
                    ->first();
        return $this->response->setJSON([
            'message' => $jawaban->id,
        ]);
    }

    public function listIdSoal(){
        $query = $this->soalModel->select('id')->get()->getResult();
        $data = [];
        foreach($query as $hasil){
            $data[] = intval($hasil->id);
        }
        return $this->response->setJSON([
            'message' => $data,
        ]);
    }

}