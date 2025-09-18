<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\LoginModel;



class UserProfile extends BaseController
{
    public function index(): string
    {
      
    //    return view('profile');
      $template  = view('common/header');
      $template .= view('profile');
      $template .= view('common/footer');

    return $template;

    }



}