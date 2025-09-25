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
    public function forgetPassword(){
         return view('forget_password');
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
     list($localPart, $domain) = explode('@', $email);

// 1. Require at least one letter in the local part
if (!preg_match('/[a-zA-Z]/', $localPart)) {
    return $this->response->setJSON([
        "status"  => "error",
        "message" => "Email address must contain at least one letter."
    ]);
}

// 2. Block if too many digits (e.g., 6+ digits in a row)
if (preg_match('/\d{6,}/', $localPart)) {
    return $this->response->setJSON([
        "status"  => "error",
        "message" => "Email address is not valid. Please use a proper email."
    ]);
}

// 3. Check MX record for real domain
if (!checkdnsrr($domain, 'MX')) {
    return $this->response->setJSON([
        "status"  => "error",
        "message" => "Email domain is not valid or cannot receive emails."
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