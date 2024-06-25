<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\RESTful\ResourceController;
use App\Models\SuperAdminModel;
use \Firebase\JWT\JWT;

class SuperAdminController extends ResourceController
{

      
    public function index()
    {
        $userModel = new SuperAdminModel();
   
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');
           
        $user = $userModel->where('email', $email)->first();
   
        if(is_null($user)) {
            return $this->respond(['error' => 'Invalid username or password.'], 401);
        }
   
        $pwd_verify = password_verify($password, $user['password']);
   
        if(!$pwd_verify) {
            return $this->respond(['error' => 'Invalid username or password.'], 401);
        }
  
        $key = getenv('JWT_SECRET');
        $iat = time(); 
        $exp = $iat + 3600;
  
        $payload = array(
            "iss" => "Issuer of the JWT",
            "aud" => "Audience that the JWT",
            "sub" => "Subject of the JWT",
            "iat" => $iat,
            "exp" => $exp,
            "email" => $user['email'],
        );
          
        $token = JWT::encode($payload, $key, 'HS256');
  
        $response = [
            'message' => 'Login Succesful',
            'token' => $token
        ];
          
        return $this->respond($response, 200);
    }

    public function register(){

        $rules = [
            'email' => ['rules' => 'required|min_length[4]|max_length[255]|valid_email|is_unique[superadmin.email]'],
            'password' => ['rules' => 'required|min_length[5]|max_length[255]'],
        ];
            
  
        if($this->validate($rules)){
            $model = new SuperAdminModel();
            $data = [
                'user'     => $this->request->getVar('user'),
                'email'    => $this->request->getVar('email'),
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
                'isActive' => $this->request->getVar('isActive')
            ];
            $model->save($data);
             
            return $this->respond(['message' => 'Registered Successfully'], 200);
        }else{
            $response = [
                'errors' => $this->validator->getErrors(),
                'message' => 'Invalid Inputs'
            ];
            return $this->fail($response , 409);
             
        }
            
    }
}
