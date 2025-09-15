<?php
namespace App\Controllers\admin;
use App\Controllers\BaseController;
use App\Models\admin\UserModel;
use App\Models\admin\RoleModel;
 
class User extends BaseController
{
     
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->input = \Config\Services::request();
        $this->userModel = new UserModel();
        $this->roleModel = new RoleModel();
        if (!$this->session->has('user_id')) {
            header('Location: ' . base_url('admin'));
            exit();
        }
    }
    public function index()
    {
        
        $template = view('admin/common/header');
        $template.= view('admin/common/sidemenu');
        $template.= view('admin/manage_user');
        $template.= view('admin/common/footer');
        $template.= view('admin/page_scripts/userjs');
        return $template;
            
    }
     public function addUser()
    {
        $data['roles'] = $this->userModel->getAllRoles();
        $template = view('admin/common/header');
        $template.= view('admin/common/sidemenu');
        $template.= view('admin/adduser',$data);
        $template.= view('admin/common/footer');
        $template.= view('admin/page_scripts/userjs');
        return $template;
    }
    public function edit($id)
    {
        $data['userData']  = $this->userModel->find($id);
        $data['roles'] = $this->userModel->getAllRoles();
    
        $template  = view('admin/common/header');
        $template .= view('admin/common/sidemenu');
        $template .= view('admin/adduser', $data);  
        $template .= view('admin/common/footer');
        $template .= view('admin/page_scripts/userjs');
        return $template;
}
    public function saveUser() {
    $user_id  = $this->request->getPost('user_id');
    $name     = $this->request->getPost('name');
    $email    = $this->request->getPost('email');
    $password = $this->request->getPost('password');
    $new_password = $this->request->getPost('new_password');
    $confirm_password = $this->request->getPost('confirm_password');
    $role_id  = $this->request->getPost('role_id');

    if (empty($name) || empty($email) || empty($role_id)) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'All fields are required.'
        ]);
    }

    if (!preg_match("/^[a-zA-Z0-9._%+-]+@gmail\.com$/", $email)) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Email must be a valid Gmail address.'
        ]);
    }
    if (empty($user_id) && empty($password)) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Password is required for creating a new user.'
        ]);
    }
    $finalPassword = null;
    if (!empty($password) || !empty($new_password)) {
        $passToValidate = !empty($password) ? $password : $new_password;

        if (strlen($passToValidate) < 7) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Password must be at least 7 characters long.'
            ]);
        }
        if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $passToValidate)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Password must contain at least one special character.'
            ]);
        }
        if (!empty($new_password) && $new_password !== $confirm_password) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'New password and confirm password do not match.'
            ]);
        }
        $finalPassword = password_hash($passToValidate, PASSWORD_DEFAULT);
    }
    $existingUser = $this->userModel
        ->where('email', $email)
        ->where('status !=', 9)
        ->first();

    if (empty($user_id)) {
        if ($existingUser) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Email already exists. Please use another email.'
            ]);
        }
        $data = [
            'name'       => $name,
            'email'      => $email,
            'role_id'    => $role_id,
            'password'   => $finalPassword,
            'status'     => 1,
            'created_at' => date("Y-m-d H:i:s")
        ];
        $this->userModel->userInsert($data);
        return $this->response->setJSON([
            'success'  => true,
            'message'  => 'User created successfully.',
            'redirect' => base_url('admin/manage_user')
        ]);
    } 
    else {
        if ($existingUser && $existingUser['user_id'] != $user_id) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Email already in use by another account.'
            ]);
        }
        $data = [
            'name'       => $name,
            'email'      => $email,
            'role_id'    => $role_id,
            'updated_at' => date("Y-m-d H:i:s")
        ];
        if ($finalPassword) {
            $data['password'] = $finalPassword;
        }
        $this->userModel->updateUser($user_id, $data);
        return $this->response->setJSON([
            'success'  => true,
            'message'  => 'User updated successfully.',
            'redirect' => base_url('admin/manage_user')
        ]);
    }
}

    public function deleteUser()
{
    $user_id = $this->request->getPost('user_id');
    if (!$user_id) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'User ID is required.'
        ]);
    }

    $this->userModel->updateUser($user_id, [
        'status'     => 9,
        'updated_at' => date("Y-m-d H:i:s")
    ]);

    return $this->response->setJSON([
        'success' => true,
        'message' => 'User deleted successfully.'
    ]);
}

    public function userlistajax()
    {
        $draw      = $this->request->getPost('draw') ?? 1;
        $start     = $this->request->getPost('start') ?? 0;
        $length    = $this->request->getPost('length') ?? 10;
        $searchVal = $this->request->getPost('search')['value'] ?? '';
    
        $columns = [
            0 => 'u.user_id', 
            1 => 'u.name',
            2 => 'u.email',
            3 => 'r.role_name',
            4 => 'u.user_id'  
        ];
    
        $orderColumnIndex = $this->request->getPost('order')[0]['column'] ?? 0;
        $orderDir = $this->request->getPost('order')[0]['dir'] ?? 'desc';
        $orderBy = $columns[$orderColumnIndex] ?? 'u.user_id';
        $users = $this->userModel->getAllFilteredRecords($searchVal, $start, $length, $orderBy, $orderDir);
        $result = [];
        $slno = $start + 1;
        foreach ($users as $user) {
        $result[] = [
            'slno'      => $slno++,
            'name'      => $user->name,
            'email'     => $user->email,
            'role_name' => $user->role_name ?? 'No Role',
            'user_id'   => $user->user_id
            ];
        }
    
        $totalCount    = $this->userModel->getAllUserCount();
        $filteredCount = $this->userModel->getFilterUserCount($searchVal);
        return $this->response->setJSON([
            "draw" => intval($draw),
            "recordsTotal" => intval($totalCount),
            "recordsFiltered" => intval($filteredCount),
            "data" => $result
        ]);
    
    }
}