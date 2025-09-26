<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\LoginModel;
use App\Models\UserProfileModel;
use App\Models\admin\Leaderboard_Model;



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
           
           $leaderboard = new Leaderboard_Model();
           
           $user = $userModel->find($userId); 
           $profile = $profileModel->where('user_id', $userId)->first(); 
           
           $leaderpoints = $leaderboard->getUserStats($userId);
   
             $template = view('common/header'); 
                $template .= view('leaderboard',
                ['profile' => $profile, 
                'leaderpoints' => $leaderpoints]);
                $template .= view('common/footer');
                return $template; 
          }
}