<?php
namespace App\Controllers\admin;

use App\Controllers\BaseController;

class UserDashboard extends BaseController
{
  public function index()
{
    $session = session();

    if (!$session->get('user_id')) {
        return redirect()->to(base_url('admin')); 
    }

    if ($session->get('role_name') === 'admin') {
        return redirect()->to(base_url('admin/dashboard'));
    }

    $data['name'] = $session->get('user_name');

    $template  = view('admin/common/header');
    $template .= view('admin/common/sidemenu');   
    $template .= view('admin/user_dashboard', $data);
    $template .= view('admin/common/footer');

    return $template;
}

}
