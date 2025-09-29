<?php
namespace App\Controllers\admin;
use App\Controllers\BaseController;
use App\Models\admin\LoginModel;
use App\Models\admin\RoleMenuModel;

class Login extends BaseController 
{
	  
	 public function index(): string
    {
        return view('admin/login');     
    }
     public function __construct()  
    {
        $this->session = \Config\Services::session();
        $this->input = \Config\Services::request();
    }

public function login()
{
    $session = session();
    $email = $this->request->getPost('email');
    $password = $this->request->getPost('password');

    if (!$email || !$password) {
        return $this->response->setJSON([
            "success"  => false,
            "message" => "Missing Email Or Password"
        ]);
    }
    $loginModel = new LoginModel();
    $user = $loginModel->checkLoginUser($email, $password);
    if ($user === 'invalid') {
        return $this->response->setJSON([
            "success"  => false,
            "message" => "Invalid Email Or Password."
        ]);
    }
    if ($user === 'removed') {
        return $this->response->setJSON([
            "success"  => false,
            "message" => "Access Denied. Your Account Has Been Removed."
        ]);
    }
    if ($user === 'suspended') {
        return $this->response->setJSON([
            "success"  => false,
            "message" => "Your Account Has Been Suspended By Admin."
        ]);
    }
    $roleMenuModel = new RoleMenuModel();
    $menus = $roleMenuModel->where('role_id', $user->role_id)->findAll();
    $menuNames = array_map(function($menu) {
        return $menu['menu_name'];
    }, $menus);
    // Set session data
    $session->set([
        'user_id'    => $user->user_id,
        'user_name'  => $user->name,
        'user_email' => $user->email,
        'role_id'    => $user->role_id,
        'role_menu'  => $menuNames,
    ]);
    $redirectUrl = ($user->role_id == 1)
        ? base_url('admin/dashboard')
        : base_url('admin/user_dashboard'); 
    return $this->response->setJSON([
        "success"   => true,
        "message"  => "Login Successful",
        "redirect" => $redirectUrl
    ]);
}

public function logout()
{
    $session = session();
    $session->destroy(); 
    return redirect()->to(base_url('admin'));
}

// public function logout()
//     {
//         $session = session();
//         $session->remove(['user_id', 'user_name', 'user_email', 'role_id', 'role_menu']);
//         return redirect()->to(base_url('admin'));
//     }
}
