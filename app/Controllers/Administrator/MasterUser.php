<?php

namespace App\Controllers\Administrator;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class MasterUser extends BaseController
{
    private $model;
    private $toView;
    private $aksesData;
    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        // Add your code here.
        $this->model = new \App\Models\Main\UserModel();
        $this->aksesData = $this->request->fetchGlobal('akses_data');
        $this->toView['hak_akses_kode'] = $this->aksesData['hak_akses_kode'];
    }

    public function html()
    {
        $this->toView['h1'] = 'Master User';
        $db = \Config\Database::connect();
        $builder = $db->table('group')->where('deleted_at', null);
        $this->toView['groups'] = $builder->get()->getResultArray();
        return view('pages/administrator/master_user', esc($this->toView));
    }

    public function index()
    {
        if(! $this->request->isAJAX()){
            return $this->html();
        }
        $data = $this->model->listUser($this->request->getGet());
        return $this->response->setJSON($data);
    }

    public function create()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'username' => 'required|max_length[255]|min_length[3]|is_unique[user.username]',
            'email'  => 'required|max_length[255]|min_length[10]|is_unique[user.email]',
            'password'  => 'required',
            'hak_akses_id' => 'required',
        ]);
        if (!$validation->withRequest($this->request)->run()) {
            $errors = $validation->getErrors();
            return $this->response->setStatusCode(400)->setJSON([
                    'errors' => $errors,
                    'message' => array_values($errors)[0],
                ]);
        }
        $validData = $validation->getValidated();
        $validData['password'] = password_hash($validData['password'], PASSWORD_DEFAULT);
        if ($this->model->insert($validData)) {
            $newUserId = $this->model->getInsertID();
            $db = \Config\Database::connect();
            $data = [];

            $isTuanRumah = false;
            foreach($validData['hak_akses_id'] as $idGroup){
                $data[] = [
                    'user_id' => $newUserId,
                    'group_id' => $idGroup,
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                if($idGroup == 2){
                    //idGroup 2 adalah id group untuk tuan rumah, cek di db:seed GroupSeeder.php
                    $isTuanRumah = true;
                }
            }

            $db->transStart();
            $builderUserGroup = $db->table('user_group');
            if($isTuanRumah){
                $builderUserConfiguration = $db->table('user_configuration');
                $builderUserConfiguration->insert([
                    'user_id' => $newUserId,
                    'kuota' => 2,
                    'kuota_terpakai' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }
            $builderUserGroup->insertBatch($data);
            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setStatusCode(400)->setJSON(['message' => 'Gagal simpan data Tuan Rumah']);
            }

            return $this->response->setJSON([
                'message' => 'Berhasil simpan data',
                'id' => $newUserId
            ]);
        }
        return $this->response->setStatusCode(400)->setJSON(['message' => 'Gagal simpan data']);
    }

    public function edit($id = null){
        $this->toView['h1'] = 'Edit User';
        return view('pages/administrator/edit_user', esc($this->toView));
    }

    public function update($id){
        $post = $this->request->getVar();
        $user = $this->model->find($id);

        $rules = [
            'nama' => 'required',
            'hak_akses_id' => 'required',
        ];

        if($post['email'] != $user->email){
            $rules['email'] = 'required|valid_email|is_unique[user.email]';
        }
        if($post['username'] != $user->username){
            $rules['username'] = 'required|alpha_dash|min_length[4]|is_unique[user.username]';
        }
        
        foreach($rules as $ruleKey => $rule){
            if(! $this->validate([$ruleKey => $rule])){
                return $this->response->setStatusCode(400)->setJSON([
                    'code' => 400,
                    'message' => $this->validator->getError($ruleKey),
                ]);
            }
        }

        $user->username = $post['username'];
        $user->nama = $post['nama'];
        $user->email = $post['email'];
        $user->hak_akses_id = $post['hak_akses_id'];
        
        $this->model->save($user);
        
        return $this->response->setJSON([
            'code' => 200,
            'message' => 'Data Telah Diupdate',
        ]);
    }
    public function delete($id){
        $this->model
            ->where('username !=', $this->aksesData['username'])
            ->delete($id);

        return $this->response->setJSON([
            'code' => 200,
            'message' => 'Data Telah Dihapus',
        ]);
    }

    public function ubahPassword(){
        $validation = \Config\Services::validation();
        $validation->setRules([
            'id'  => "required",
            'password'  => "required",
            'password_ulang'  => "required|matches[password]",
        ]);
        if (! $validation->withRequest($this->request)->run()) {
            $errors = $validation->getErrors();
            return $this->response->setStatusCode(400)->setJSON([
                'errors' => $errors,
                'message' => array_values($errors)[0],
                'code' => 400,
            ]);
        }
        $validData = $validation->getValidated();
        $id = $validData['id'];
        $validData['password'] = password_hash($validData['password'], PASSWORD_DEFAULT);

        if($this->model->where('id', $id)->set($validData)->update()){
            return $this->response->setJSON([
                'message' => 'Berhasil ubah password',
                'id'=>$id,
                'code' => 200,
            ]);
        }
        
        return $this->response->setStatusCode(400)->setJSON([
            'message' => 'Gagal ubah password',
            'code' => 400,
        ]);
    }
}