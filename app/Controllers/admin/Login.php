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

    if (!$email || !$password) {
        return $this->response->setJSON([
            "status"  => "error",
            "message" => "Missing Email Or Password"
        ]);
    }

    $loginModel = new LoginModel();
    $user = $loginModel->checkLoginUser($email, $password);

    if ($user === false) {
    return $this->response->setJSON([
        "status"  => "error",
        "message" => "Invalid Email Or Password"
    ]);
}

if ($user === 'suspended') {
    return $this->response->setJSON([
        "status"  => "error",
        "message" => "Your Account Has Been Suspended By Admin."
    ]);
}
$session = session();
$session->set([
    'user_id'    => $user->user_id,
    'user_name'  => $user->name,
    'user_email' => $user->email,
    'role_id'    => $user->role_id,
]);

$redirectUrl = ($user->role_id == 1)
    ? base_url('admin/dashboard')
    : base_url('user/dashboard');

return $this->response->setJSON([
    "status"   => "success",
    "message"  => "Login successful",
    "redirect" => $redirectUrl
]);
}

public function logout()
{
    $session = session();
    $session->destroy(); 
    return redirect()->to(base_url('admin'));
}


}
