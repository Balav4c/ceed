<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\LoginModel;
use App\Models\UserProfileModel;



class UserProfile extends BaseController
{


    public function __construct() 
	{
		$this->session = \Config\Services::session();
		$this->input = \Config\Services::request();

	}
public function index(): string { 
    $userId = $this->session->get('user_id'); 
    if (!$userId) {
         return redirect()->to(base_url(''));
         } 
         $userModel = new LoginModel(); 
         $profileModel = new UserProfileModel(); 
         $user = $userModel->find($userId); 
         $profile = $profileModel->where('user_id', $userId)->first(); 
         if ($profile) { 
            $profile['notification'] = json_decode($profile['notification'] ?? '[]', true); 
            $user = array_merge($user, $profile); $progress = $profile['profile_percentage'] ?? 0;
             // use stored percentage 
             } else { 
                $progress = 0; $user['notification'] = []; } 
                $template = view('common/header'); 
                $template .= view('profile', ['user' => $user,'progress' => $progress]);
                $template .= view('common/footer'); return $template; 
            }

public function saveProfile() { 
    $session = session();
     $userId = $session->get('user_id'); 
     $profileModel = new UserProfileModel(); 
     // Fields to calculate profile percentage
      $fields = [ 'name' => $this->request->getPost('name'), 
      'grade' => $this->request->getPost('grade'), 
      'school' => $this->request->getPost('school'), 
      'bio' => $this->request->getPost('bio'), 
      'phone' => $this->request->getPost('phone'),
      'email' => $this->request->getPost('email'), ]; 
      // Count filled fields
       $filled = 0; foreach ($fields as $value) { 
        if (!empty($value)) { $filled++; } 
    } 
    $totalFields = count($fields); 
    $percentage = ($filled / $totalFields) * 100; 
    $data = [ 'user_id' => $userId, 
    'name' => $fields['name'],
     'grade' => $fields['grade'], 
     'school' => $fields['school'], 
     'bio' => $fields['bio'], 
     'phone' => $fields['phone'], 
     'profile_percentage' => round($percentage), 
    // store as integer
     'notification'=> json_encode($this->request->getPost('notification') ?? []), ]; 
     // Check if profile exists 
     $existing = $profileModel->where('user_id', $userId)->first(); 
     if ($existing) 
        { 
            $profileModel->update($existing['id'], $data); 
        } else
         { 
            $profileModel->insert($data);
         } 
         return $this->response->setJSON(['status' => 'success', 'message' => 'Profile saved successfully!', 'percentage' => round($percentage)]); 
        }

    



public function changePassword()
{
    $session = session();
    $userId = $session->get('user_id');

    $userModel = new LoginModel();
    $user = $userModel->find($userId);

    // $currentPassword = trim($this->request->getPost('currentpassword'));
    $newPassword     = trim($this->request->getPost('newpassword'));
    $confirmPassword = trim($this->request->getPost('confirmpassword'));

    // 0. Empty field check
    if ( empty($newPassword) || empty($confirmPassword)) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Please fill in all password fields.'
        ]);
    }

    // 1. Check old password
    // if (!password_verify($currentPassword, $user['password'])) {
    //     return $this->response->setJSON([
    //         'status' => 'error',
    //         'message' => 'Current password is incorrect.'
    //     ]);
    // }

    // 1a. Check if new password is same as old password
    if (password_verify($newPassword, $user['password'])) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'New password cannot be the same as the current password.'
        ]);
    }

    // 2. Check new and confirm match
    if ($newPassword !== $confirmPassword) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'New password and confirm password do not match.'
        ]);
    }

    // 3. Password policy
   
if (strlen($newPassword) < 8 ||
    !preg_match('/[A-Za-z]/', $newPassword) ||      
    !preg_match('/[\W_]/', $newPassword)) {         
    return $this->response->setJSON([
        'status'  => 'error',
        'message' => 'Password must be at least 8 characters long, contain at least one letter, and one special character.'
    ]);
}


    // 4. Update password
    $userModel->update($userId, [
        'password' => password_hash($newPassword, PASSWORD_DEFAULT)
    ]);

    return $this->response->setJSON([
        'status' => 'success',
        'message' => 'Password updated successfully!'
    ]);
}


//get profile completion percentage

private function getProfileCompletion($user)
 { 
    $fields = ['name', 'grade', 'school', 'bio', 'phone','email'];
     $filled = 0; foreach ($fields as $field)
      {
         if (!empty($user[$field]))
             {
                 $filled++; 
                } 
            } 
            $total = count($fields); 
            return round(($filled / $total) * 100); 
        }




}