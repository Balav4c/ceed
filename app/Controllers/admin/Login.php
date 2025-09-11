<?php
namespace App\Controllers\admin;
use App\Controllers\BaseController;

class Login extends BaseController 
{
	  
	 public function index(): string
    {
         return view('admin/login');
          
       
    }
}