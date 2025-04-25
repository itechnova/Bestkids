<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class Apirest extends ResourceController
{

    public function __construct()
    {
        helper(['core_helper', 'option_helper', 'jwt_helper']);
    }

    public function user(){
        $Token = $this->request->getHeaderLine('Token');

        $Guest = explode('|', decrypt_token($Token));
        if(count($Guest)>=4){
            $Model = new \App\Models\AccountModel();

            $isAllowed = $Model->where('idaccount', $Guest[0])
            ->where('idrole', $Guest[1])
            ->where('username', $Guest[2])
            ->where('lastip', $Guest[3])
            ->first();

            return IS_NULL($isAllowed) ? false: $isAllowed;
        }
         
        return false;
            
    }

    public function Authenticate()
    {

        $Authorization = $this->request->getHeaderLine('Authorization');

        

        if(strpos($Authorization, 'Bearer ') === 0){
            $token = substr($Authorization, 7);
            $credentials = base64_decode($token);
            list($username, $password) = explode(':', $credentials);

            // Aquí debes validar el token Bearer
            //return $this->validateBearer($token);
        }else{
            $username = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : false;
            $password = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : false;            
        }

        if(!$username || !$password){
            return false;
        }

        /*$this->Conex = (new \App\Models\Restapi())
        ->where('username', $username)
        ->where('password', $password)
        ->first();*/

        return (strtolower($username)==='itechnova') && ($password==='0a74014ce687fe8f327645');
    }

    protected function validateBearer($token)
    {
        try {
            $decoded = $this->decode($token);
            return $decoded !== null;
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function decode($token)
    {
        $key = '325251'; // La clave para verificar el token
        try {
            return \Firebase\JWT\JWT::decode($token, new \Firebase\JWT\Key($key, 'HS256'));
        } catch (\Exception $e) {
            var_dump($e);
            return null;
        }
    }

    public function NotAuthorized($message = 'No autorizado'){
        # code...
        return $this->Response(null, 401, $message);
    }

    public function Response($data, $code = 200,  $msj = '')
    {
        if ($code === 200) {
            # code...
            return $this->respond([
                'response' => $data,
                'code' => $code
            ]);
        }else {
            # code...
            return $this->respond([
                'response' => $msj,
                'code' => $code
            ]);
        }
    }
}