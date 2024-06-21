<?php

namespace App\Controllers;
use Firebase\JWT\JWT;

class Auth extends BaseController
{
	public function index()
	{
        $cookieJwtName = getenv('COOKIE_JWT_NAME');
		if(isset($_COOKIE["$cookieJwtName"])){
			return redirect()->to(site_url('welcome'));
		}
		$session = session();
		$errorJwt = $session->getFlashdata('error_jwt');
		$errorLogin = $session->getFlashdata('error_login');
		$err = [];
		if($errorJwt){
			$err[] = $errorJwt; 
		}
		if($errorLogin){
			$err[] = $errorLogin; 
		}
		helper('form');
		return view('pages/auth/form_login', ['error' => $err]);
	}
	public function create()
	{
		$email = $this->request->getPost('email');
		$password = $this->request->getPost('password');

		$userModel = new \App\Models\Main\UserModel();

		$user = $userModel->where("email", $email)->first();
		
		if(isset($user) && password_verify($password, $user->password)){
            //list grup
			$userGroup = model(\App\Models\Main\UserGroupModel::class);
			//jwt-proses
			$key = getenv('JWT_SECRET_KEY');
			$payload = [
				'username' => $user->username,
				'hak_akses_kode' => $userGroup->codeByUser($user->id),
				'id' => $user->id,
				'nbf' => date("U", strtotime('-1 seconds')),
				'exp' => date("U", strtotime('+1 days')),
			];

			$jwt = JWT::encode($payload, $key, 'HS256');
			setcookie(getenv('COOKIE_JWT_NAME'), $jwt, 0, '/', '', false, true); //setcookie PHP nativ, bukan dari framework CI
			//.
			return redirect()->to(site_url('welcome'));
		}else{
			$session = session();
            $session->setFlashdata('error_login', 'Email atau Password salah');
			return redirect()->to(site_url('auth'));
		}
	}
	public function logout(){
		setcookie(getenv('COOKIE_JWT_NAME'), '', 0, '/');  //kosongkan cookie
		return redirect()->to(site_url('auth'));
	}

	public function welcome()
    {
		$aksesData = $this->request->fetchGlobal('akses_data');
        if($aksesData){
            $toView['hak_akses_kode'] = $aksesData['hak_akses_kode'];
            $toView['username'] = $aksesData['username'];
            $toView['h1'] = 'Welcome';
            return view('pages/welcome_page', $toView);
        }
        return view('pages/auth/belum_login');
    }

}
