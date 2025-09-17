<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\LoginModel;



class Login extends BaseController
{
    public function index(): string
    {
      
       return view('login');

    }
   public function login()
{
    $email = $this->request->getPost('email');
    $password = $this->request->getPost('password');
   

    if($email && $password){
        $loginModel = new LoginModel();
        
        $user = $loginModel->checkLoginUser($email, $password);

        if($user){
            $session = session();
            $session->set([
                'user_id'    => $user->user_id,
                'user_name'  => $user->name,
                'user_email' => $user->email,
            ]);

            return $this->response->setJSON([
                "status"  => "success",
                "message" => "Login Successful"
            ]);
        }else{
            return $this->response->setJSON([
                "status"  => "error",
                "message" => "Invalid Email or Password"
            ]);
        }
    }

    return $this->response->setJSON([
        "status"  => "error",
        "message" => "Email and Password are required"
    ]);
}

}
