<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\LoginModel;
use App\Models\UserProfileModel;



class UserProfile extends BaseController
{
    public function index(): string
    {
      $session = session();
        $userId = $session->get('user_id');

        $userModel = new LoginModel();
        $profileModel = new UserProfileModel(); // user_profile table
        
        $user = $userModel->find($userId);
        $profile = $profileModel->where('user_id', $userId)->first();

         
     // Decode notification JSON into array
    if ($profile && isset($profile['notification'])) {
        $profile['notification'] = json_decode($profile['notification'], true) ?? [];
        $user = array_merge($user, $profile);
    }
       $template  = view('common/header');
      $template .= view('profile', ['user' => $user]);
      $template .= view('common/footer');

    return $template;

    }
    
    public function saveProfile()
    {
        $session = session();
        $userId = $session->get('user_id');

        $profileModel = new UserProfileModel();

        $data = [
            'user_id'     => $userId,
            'name'        => $this->request->getPost('name'),
            'grade'       => $this->request->getPost('grade'),
            'school'      => $this->request->getPost('school'),
            'bio'         => $this->request->getPost('bio'),
            'phone'       => $this->request->getPost('phone'),
            'notification'=> json_encode($this->request->getPost('notification') ?? []),
        ];

        // Check if profile exists
        $existing = $profileModel->where('user_id', $userId)->first();
        if ($existing) {
            $profileModel->update($existing['id'], $data);
        } else {
            $profileModel->insert($data);
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Profile saved successfully!']);
    }

public function changePassword()
{
    $session = session();
    $userId = $session->get('user_id');

    $userModel = new LoginModel();
    $user = $userModel->find($userId);

    $currentPassword = trim($this->request->getPost('currentpassword'));
    $newPassword     = trim($this->request->getPost('newpassword'));
    $confirmPassword = trim($this->request->getPost('confirmpassword'));

    // 0. Empty field check
    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Please fill in all password fields.'
        ]);
    }

    // 1. Check old password
    if (!password_verify($currentPassword, $user['password'])) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Current password is incorrect.'
        ]);
    }

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
    if (strlen($newPassword) < 8) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Password must be at least 8 characters long.'
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






}