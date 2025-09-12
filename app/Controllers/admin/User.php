<?php
namespace App\Controllers\admin;
use App\Controllers\BaseController;
use App\Models\admin\UserModel;


class User extends BaseController 
{
	  
	public function __construct() 
	{
		$this->session = \Config\Services::session();
		$this->input = \Config\Services::request();
         $this->userModel = new UserModel();

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
	        $template = view('admin/common/header');
            $template.= view('admin/common/sidemenu');
			$template.= view('admin/adduser');
            $template.= view('admin/common/footer');
            $template.= view('admin/page_scripts/userjs');
			return $template;
	} 
    
    // public function createUser()
    // {
    
    //     $userModel = new ManageUser_Model();
    //     $validation = \Config\Services::validation();
    //     $validation->setRules([
    //         'name'     => 'required|min_length[3]',
    //         'email'    => 'required|valid_email|is_unique[user.email]',
    //         'password' => 'required|min_length[6]',
    //     ]);

    //     if (!$validation->withRequest($this->request)->run()) {
    //         return $this->response->setJSON([
    //             'status'  => 'error',
    //             'message' => $validation->getErrors() 
    //         ]);
    //     }

    //     $userModel->save([
    //         'name'       => $this->request->getPost('name'),
    //         'email'      => $this->request->getPost('email'),
    //         'password'   => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
    //         'status'     => 1,
    //         'created_on' => date('Y-m-d H:i:s')
    //     ]);

    //     return $this->response->setJSON([
    //         'status'  => 'success',
    //         'message' => 'User Added Successfully.'
    //     ]);
    // }
public function saveUser() {
    $user_id = $this->request->getPost('user_id');
    $name = $this->request->getPost('name');
    $email = $this->request->getPost('email');
    $password = $this->request->getPost('password');

    if ($name && $email && $password) {
        $data = [
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'status' => 1,
            'created_on' => date("Y-m-d H:i:s"),
        ];

        if (empty($user_id)) {
            $CreateUser = $this->userModel->userInsert($data);
            return $this->response->setJSON([
                "status" => 1,
                "msg" => "User Created Successfully.",
                "redirect" => base_url('admin/manage_user')
            ]);
        } else {
            $data['modified_on'] = date("Y-m-d H:i:s");
            $modifyUser = $this->userModel->updateUser($user_id, $data);
            return $this->response->setJSON([
                "status" => 1,
                "msg" => "User Updated Successfully.",
                "redirect" => base_url('admin/manage_user')
            ]);
        }
    } else {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'All fields are required.'
        ]);
    }
}

//     public function store()
// {
//         $request = $this->request;
//         $roleModel = new RoleModel();
//         $roleMenuModel = new RoleMenuModel();
 
//         $role_id   = $request->getPost('role_id');
//         $role_name = $request->getPost('role_name');
//         $menus     = $request->getPost('menus');
//         if (empty($role_name)) {
//             return $this->response->setJSON([
//                 'status'  => 'error',
//                 'message' => 'Role Name is required'
//             ]);
//         }
 
//         if ($role_id) {
//             $roleModel->update($role_id, ['role_name' => $role_name]);
//         } else {
//             $role_id = $roleModel->insert(['role_name' => $role_name], true);
//         }
 
//         if (!$role_id) {
//             return $this->response->setJSON([
//                 'status'  => 'error',
//                 'message' => 'Failed to save role (check DB structure)'
//             ]);
//         }
 
//         $roleMenuModel->where('role_id', $role_id)->delete();
//        if (!empty($menus)) {
//         $data = [];
//         foreach ($menus as $menuName) {
//             $data[] = [
//                 'role_id'   => $role_id,
//                 'menu_name' => $menuName,
//                 'access'    => 1  
//             ];
//         }
//             $roleMenuModel->insertBatch($data);
//         }
 
//         return $this->response->setJSON(['status' => 'success']);
 
// }
//     public function userlistajax()
// {
//     header('Content-Type: application/json');

//     $draw = $_POST['draw'] ?? 1;
//     $fromstart = $_POST['start'] ?? 0;
//     $tolimit = $_POST['length'] ?? 10;
//     $search = $_POST['search']['value'] ?? '';
//     $condition = "1=1";

//     // Search filter
//     if (!empty($search)) {
//         $noSpaceSearch = str_replace(' ', '', strtolower($search));
//         $condition .= " AND (
//             REPLACE(LOWER(name), ' ', '') LIKE '%{$noSpaceSearch}%' OR
//             REPLACE(LOWER(email), ' ', '') LIKE '%{$noSpaceSearch}%'
//         )";
//     }

//     // Sorting
//     $columns = ['slno', 'name', 'email', 'user_roles', 'action', 'user_id'];
//     $orderColumnIndex = $_POST['order'][0]['column'] ?? 0;
//     $orderDir = $_POST['order'][0]['dir'] ?? 'desc';
//     $orderBy = $columns[$orderColumnIndex] ?? 'user_id';
//     $allowedOrderColumns = ['name', 'email', 'user_id'];
//     if (!in_array($orderBy, $allowedOrderColumns)) {
//         $orderBy = 'user_id';
//     }

//     $slno = $fromstart + 1;

//     // Get filtered users with limit
//     $users = $this->ManageUser_Model->getAllFilteredRecords($condition, $fromstart, $tolimit, $orderBy, $orderDir);
//     $result = [];

//     foreach ($users as $user) {
//         // Fetch user roles
//         $roles = $this->Role_Model
//             ->select('role_name')
//             ->join('roles', 'roles.role_id = user.role_id', 'left')
//             ->where('user.user_id', $user->user_id)
//             ->findAll();

//         $roleList = array_column($roles, 'role_name');

//         $result[] = [
//             'slno'       => $slno++,
//             'user_id'    => $user->user_id,
//             'name'       => $user->name,
//             'email'      => $user->email,
//             'user_roles' => $roleList,
//             'action'     => '<button class="btn btn-sm btn-primary">Edit</button> 
//                              <button class="btn btn-sm btn-danger">Delete</button>'
//         ];
//     }

//     // Counts
//     $totalCount = $this->ManageUser_Model->getAllUserCount();
//     $filteredCountObj = $this->ManageUser_Model->getFilterUserCount($condition);
//     $filteredCount = $filteredCountObj->filRecords ?? 0;

//     echo json_encode([
//         "draw" => intval($draw),
//         "recordsTotal" => $totalCount,
//         "recordsFiltered" => $filteredCount,
//         "data" => $result
//     ]);
// }

}
