<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\LoginModel;


class Login extends BaseController
{
    protected $loginModel;
    protected $email;
    public function __construct()
    {
        $this->loginModel = new LoginModel();
        $this->email = \Config\Services::email();
    }
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


        if ($email && $password) {
            $loginModel = new LoginModel();

            $user = $loginModel->checkLoginUser($email, $password);

            if ($user) {
                $session = session();
                $session->set([
                    'isLoggedIn' => true,
                    'user_id' => $user->user_id,
                    'user_name' => $user->name,
                    'user_email' => $user->email,
                ]);

                return $this->response->setJSON([
                    "status" => "success",
                    "message" => "Login Successful",
                    "redirect" => base_url('/')
                ]);
            } else {
                return $this->response->setJSON([
                    "status" => "error",
                    "message" => "Invalid Email or Password"
                ]);
            }
        }

        return $this->response->setJSON([
            "status" => "error",
            "message" => "Email and Password are required"
        ]);
    }
    public function saveUser()
    {
        $name = $this->request->getPost('name');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $cpassword = $this->request->getPost('cpassword');

        if ($name && $email && $password && $cpassword) {
            list($localPart, $domain) = explode('@', $email);

            // 1. Require at least one letter in the local part
            if (!preg_match('/[a-zA-Z]/', $localPart)) {
                return $this->response->setJSON([
                    "status" => "error",
                    "message" => "Email address must contain at least one letter."
                ]);
            }

            // 2. Block if too many digits (e.g., 6+ digits in a row)
            if (preg_match('/\d{6,}/', $localPart)) {
                return $this->response->setJSON([
                    "status" => "error",
                    "message" => "Email address is not valid. Please use a proper email."
                ]);
            }

            // 3. Check MX record for real domain
            if (!checkdnsrr($domain, 'MX')) {
                return $this->response->setJSON([
                    "status" => "error",
                    "message" => "Email domain is not valid or cannot receive emails."
                ]);
            }





            // Check if passwords match
            if ($password !== $cpassword) {
                return $this->response->setJSON([
                    "status" => "error",
                    "message" => "Passwords do not match"
                ]);
            }

            // Validate password strength
            if (
                strlen($password) < 8 ||
                !preg_match('/[A-Z]/', $password) ||
                !preg_match('/[\W]/', $password)
            ) {
                return $this->response->setJSON([
                    "status" => "error",
                    "message" => "Password must be at least 8 characters long, contain one uppercase letter and one special character."
                ]);
            }

            $loginModel = new LoginModel();

            // Check if email already exists
            $existingUser = $loginModel->where('email', $email)->first();
            if ($existingUser) {
                return $this->response->setJSON([
                    "status" => "error",
                    "message" => "Email already registered"
                ]);
            }

            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $userData = [
                'role_id' => 2,
                'name' => $name,
                'email' => $email,
                'password' => $hashedPassword,
                'status' => 1
            ];

            if ($loginModel->insert($userData)) {
                return $this->response->setJSON([
                    "status" => "success",
                    "message" => "Account created successfully"
                ]);
            } else {
                return $this->response->setJSON([
                    "status" => "error",
                    "message" => "Failed to create account. Please try again."
                ]);
            }
        }

        return $this->response->setJSON([
            "status" => "error",
            "message" => "All fields are required"
        ]);
    }
    // forget password
    public function forgetPassword()
    {
        return view('forget_password');
    }

    public function sendResetLink()
    {
        $email = $this->request->getPost('email');
        $user = $this->loginModel->findByEmail($email);

        if (!$user) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Email not found!'
            ]);
        }

        $token = bin2hex(random_bytes(32)); 
        $hashedToken = hash('sha256', $token); 
        $expireTime = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $this->loginModel->saveResetToken($user['user_id'], $hashedToken, $expireTime);

       $resetLink = site_url("reset_password/" . urlencode($token)); 

        // Send email
        $emailService = \Config\Services::email();
        $emailService->setFrom('smartloungework@gmail.com', 'Smart Lounge');
        $emailService->setTo($email);
        $emailService->setSubject('Password Reset Request');
        $emailService->setMessage("
            <p>Hello {$user['name']},</p>
            <p>You requested to reset your password. Click the link below to reset it:</p>
            <p><a href='{$resetLink}' target='_blank'>Reset Password</a></p>
            <p>This link will expire in 1 hour.</p>
            <p>If you did not request this, please ignore this email.</p>
        ");
        $emailService->setMailType('html');

        if ($emailService->send()) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Reset Link Sent To Your Email!'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $emailService->printDebugger(['headers', 'subject', 'body'])
            ]);
        }
    }

 public function resetPassword($token = null)
    {
        if (!$token) {
            return redirect()->to('/forget_password');
        }

        $hashedToken = hash('sha256', $token);
        $user = $this->loginModel->verifyToken($hashedToken);

        if (!$user) {
            return redirect()->to('/forget_password')
                ->with('error', 'Invalid or Expired Token!');
        }

        return view('reset_password', ['token' => $token]);
    }

    public function updatePassword()
    {
        $token = $this->request->getPost('token');
        $password = $this->request->getPost('password');
        $confirm = $this->request->getPost('confirm_password');

        if ($password !== $confirm) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Passwords Do Not Match!'
            ]);
        }

        $hashedToken = hash('sha256', $token);
        $user = $this->loginModel->verifyToken($hashedToken);

        if (!$user) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid or Expired Token!'
            ]);
        }

        $this->loginModel->resetPassword($user['user_id'], $password);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Password Updated Successfully!'
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