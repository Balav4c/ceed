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
    
    
public function saveUser() {
    $user_id = $this->request->getPost('user_id');
    $name = $this->request->getPost('name');
    $email = $this->request->getPost('email');
    $password = $this->request->getPost('password');
    $role_id = $this->request->getPost('role_id'); // add role

    if ($name && $email && $password && $role_id) {
        $data = [
            'name'       => $name,
            'email'      => $email,
            'password'   => password_hash($password, PASSWORD_DEFAULT),
            'role_id'    => $role_id,
            'status'     => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ];

        if (empty($user_id)) {
            $this->userModel->userInsert($data);
            return $this->response->setJSON([
                "status" => 1,
                "msg"    => "User Created Successfully.",
                "redirect" => base_url('admin/manage_user')
            ]);
        } else {
            $data['updated_at'] = date("Y-m-d H:i:s");
            $this->userModel->updateUser($user_id, $data);
            return $this->response->setJSON([
                "status" => 1,
                "msg"    => "User Updated Successfully.",
                "redirect" => base_url('admin/manage_user')
            ]);
        }
    } else {
        return $this->response->setJSON([
            'status'  => 0,
            'message' => 'All fields are required.'
        ]);
    }
}



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
