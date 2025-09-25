<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\LoginModel;



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
        
            $template = view('common/header'); 
                $template .= view('leaderboard');
                $template .= view('common/footer');
                return $template; 
          





}
}