<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\LoginModel;



class Login extends BaseController
{
    public function index(): string
    {
      
       return view('signin');

    }
      public function signup(): string
    {
      
       return view('signup');

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
                 'isLoggedIn' => true, 
                'user_id'    => $user->user_id,
                'user_name'  => $user->name,
                'user_email' => $user->email,
            ]);

            return $this->response->setJSON([
                "status"  => "success",
                "message" => "Login Successful",
                 "redirect" => base_url('/')
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
public function saveUser()
{
    $name      = $this->request->getPost('name');
    $email     = $this->request->getPost('email');
    $password  = $this->request->getPost('password');
    $cpassword = $this->request->getPost('cpassword');

    if ($name && $email && $password && $cpassword) {
        // Email format validation
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->response->setJSON([
                "status"  => "error",
                "message" => "Please enter a valid email address"
            ]);
        }

        // Check if passwords match
        if ($password !== $cpassword) {
            return $this->response->setJSON([
                "status"  => "error",
                "message" => "Passwords do not match"
            ]);
        }

        // Validate password strength
        if (strlen($password) < 8 || 
            !preg_match('/[A-Z]/', $password) ||     
            !preg_match('/[\W]/', $password)) {     
            return $this->response->setJSON([
                "status"  => "error",
                "message" => "Password must be at least 8 characters long, contain one uppercase letter and one special character."
            ]);
        }

        $loginModel = new LoginModel();

        // Check if email already exists
        $existingUser = $loginModel->where('email', $email)->first();
        if ($existingUser) {
            return $this->response->setJSON([
                "status"  => "error",
                "message" => "Email already registered"
            ]);
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert user data
        $userData = [
            'role_id'  => 2, // default role, you can change as needed
            'name'     => $name,
            'email'    => $email,
            'password' => $hashedPassword,
            'status'   => 1
        ];

        if ($loginModel->insert($userData)) {
            return $this->response->setJSON([
                "status"  => "success",
                "message" => "Account created successfully"
            ]);
        } else {
            return $this->response->setJSON([
                "status"  => "error",
                "message" => "Failed to create account. Please try again."
            ]);
        }
    }

    return $this->response->setJSON([
        "status"  => "error",
        "message" => "All fields are required"
    ]);
}

public function logout()
{
    $session = session();
    $session->destroy(); 
    return $this->response->setJSON([
        "status" => "success",
        "message" => "Logged out successfully"
    ]);
}


}