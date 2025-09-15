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
        $role_id  = $this->request->getPost('role_id');

        // Basic validations
        if (!$name || !$email || !$password || !$role_id ) {
            return $this->response->setJSON([
                'status'  => 0,
                'message' => 'All fields are required.'
            ]);
        }
        if (!preg_match("/^[a-zA-Z0-9._%+-]+@gmail\.com$/", $email)) {
            return $this->response->setJSON([
                'status'  => 0,
                'message' => 'Email must be a valid Gmail address (e.g. user@gmail.com).'
            ]);
        }
        if (strlen($password) < 6) {
            return $this->response->setJSON([
                'status'  => 0,
                'message' => 'Password must be at least 6 characters long.'
            ]);
        }
        if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
            return $this->response->setJSON([
                'status'  => 0,
                'message' => 'Password must contain at least one special character.'
            ]);
        }
        $existingUser = $this->userModel
            ->where('email', $email)
            ->where('status !=', 9) 
            ->first();

        if (empty($user_id)) {
            if ($existingUser) {
                return $this->response->setJSON([
                    'status'  => 0,
                    'message' => 'Email already exists. Please use another email.'
                ]);
            }
            $data = [
                'name'       => $name,
                'email'      => $email,
                'role_id'    => $role_id,
                'status'     => 1,
                'created_at' => date("Y-m-d H:i:s")
            ];
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
            $this->userModel->userInsert($data);
            return $this->response->setJSON([
                'status'   => 1,
                'message'  => 'User created successfully.',
                'redirect' => base_url('admin/manage_user')
            ]);
        } else {
            if ($existingUser && $existingUser['user_id'] != $user_id) {
                return $this->response->setJSON([
                    'status'  => 0,
                    'message' => 'Email already in use by another account.'
                ]);
            }

            $data = [
                'name'       => $name,
                'email'      => $email,
                'role_id'    => $role_id,
                'updated_at' => date("Y-m-d H:i:s")
            ];

            if ($password) {
                $data['password'] = password_hash($password, PASSWORD_DEFAULT);
            }

            $this->userModel->updateUser($user_id, $data);

            return $this->response->setJSON([
                'status'   => 1,
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