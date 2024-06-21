<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AksesFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
		try
		{
            $key = getenv('JWT_SECRET_KEY');
            $cookieJwtName = getenv('COOKIE_JWT_NAME');
            $jwt = $_COOKIE["$cookieJwtName"] ?? null;
            if(!$jwt){
                throw new \Exception('Belum login.');
            }
            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
            $decoded_array = (array) $decoded;
            
            if(is_array($arguments)){
                $sesuai = false;
                foreach($arguments as $argument){
                    if(in_array($argument, $decoded_array['hak_akses_kode'])){
                        $sesuai = true;
                    }
                }
                if($sesuai === false){
                    throw new \Exception('Hak Akses Tidak Sesuai.');
                }
            }
            $request->setGlobal('akses_data', $decoded_array);
		}
		catch (\Exception $e)
		{
		    setcookie(getenv('COOKIE_JWT_NAME'), '', 0, '/'); //kosongkan cookie
            $session = session();
            $session->setFlashdata('error_jwt', $e->getMessage());
			return redirect()->to(site_url('auth'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}