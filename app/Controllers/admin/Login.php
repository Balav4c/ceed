<?php
namespace App\Controllers\admin;
use App\Controllers\BaseController;
use App\Models\admin\LoginModel;

class Login extends BaseController 
{
	  
	 public function index(): string
    {
         return view('admin/login');
          
     }
//     public function login()
// {
//     $email = $this->request->getPost('email');
//     $password = $this->request->getPost('password');

//     if($email && $password){
//         $loginModel = new LoginModel();
//         $user = $loginModel->checkLoginUser($email, $password);

//         if($user){
//             $session = session();
//             $session->set([
//                 'user_id'    => $user->user_id,
//                 'user_name'  => $user->name,
//                 'user_email' => $user->email,
//             ]);

//             return $this->response->setJSON([
//                 "status"  => "success",
//                 "message" => "Login Successful"
//             ]);
//         }else{
//             return $this->response->setJSON([
//                 "status"  => "error",
//                 "message" => "Invalid Email or Password"
//             ]);
//         }
//     }

//     return $this->response->setJSON([
//         "status"  => "error",
//         "message" => "Missing Email or Password"
//     ]);
// }
public function login()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        if ($email && $password) {
            $loginModel = new LoginModel();
            $user = $loginModel->checkLoginUser($email, $password);

            if ($user) {
                $session = session();
                $session->set([
                    'user_id'    => $user->user_id,
                    'user_name'  => $user->name,
                    'user_email' => $user->email,
                    'role_name'  => $user->role_name, 
                ]);

               if ($user->role_name === 'admin') {
                    return $this->response->setJSON([
                        "status"  => "success",
                        "redirect" => base_url('admin/dashboard'),
                        "message" => "Login Successful (Admin)"
                    ]);
                } else {
                    return $this->response->setJSON([
                        "status"  => "success",
                        "redirect" => base_url('user/dashboard'),
                        "message" => "Login Successful (User)"
                    ]);
                }
            } else {
                return $this->response->setJSON([
                    "status"  => "error",
                    "message" => "Invalid Email or Password"
                ]);
            }
        }

        return $this->response->setJSON([
            "status"  => "error",
            "message" => "Missing Email or Password"
        ]);
    }
public function logout()
{
    $session = session();
    $session->destroy(); 
    return redirect()->to(base_url('admin'));
}


}
