<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\LoginModel;
use App\Models\UserProfileModel;



class LeaderBoard extends BaseController
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
           
        
                $template = view('common/header'); 
                $template .= view('leaderboard',['profile' => $profile]);
                $template .= view('common/footer');
                return $template; 
          }
}