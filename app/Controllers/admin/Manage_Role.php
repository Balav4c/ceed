<?php
namespace App\Controllers\admin;
use App\Controllers\BaseController;

class Manage_Role extends BaseController 
{
	  
	public function __construct() 
	{
		$this->session = \Config\Services::session();
		$this->input = \Config\Services::request();

	}
	public function index()
	{
	        $template = view('admin/common/header');
            $template.= view('admin/common/sidemenu');
			$template.= view('admin/manage_role');
            $template.= view('admin/common/footer');
			return $template;
	}
}