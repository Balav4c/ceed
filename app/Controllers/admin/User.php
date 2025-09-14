<?php
namespace App\Controllers\admin;
use App\Controllers\BaseController;
use App\Models\Admin\UserModel;


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
    $user_id  = $this->request->getPost('user_id');
    $name     = $this->request->getPost('name');
    $email    = $this->request->getPost('email');
    $password = $this->request->getPost('password');
    $role_id  = $this->request->getPost('role_id'); // add role

    // Basic field validation
    if (!$name || !$email || !$password || !$role_id) {
        return $this->response->setJSON([
            'status'  => 0,
            'message' => 'All fields are required.'
        ]);
    }

    // Email must be Gmail
    if (!preg_match("/^[a-zA-Z0-9._%+-]+@gmail\.com$/", $email)) {
        return $this->response->setJSON([
            'status'  => 0,
            'message' => 'Email must be a valid Gmail address (e.g. user@gmail.com).'
        ]);
    }

    // Password validation: at least 6 chars, allow special characters
    if (strlen($password) < 6) {
        return $this->response->setJSON([
            'status'  => 0,
            'message' => 'Password must be at least 6 characters long.'
        ]);
    }

    // OPTIONAL: Require at least one special character
    if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
        return $this->response->setJSON([
            'status'  => 0,
            'message' => 'Password must contain at least one special character.'
        ]);
    }

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
}
public function deleteUser()
{
    $user_id = $this->request->getPost('user_id');
    if (!$user_id) {
        return $this->response->setJSON([
            'status' => 0,
            'message' => 'User ID is required.'
        ]);
    }

    // Soft delete: set status to 9
    $this->userModel->updateUser($user_id, ['status' => 9, 'updated_at' => date("Y-m-d H:i:s")]);

    return $this->response->setJSON([
        'status' => 1,
        'message' => 'User deleted successfully.'
    ]);
}



 public function userlistajax()
{
    $draw      = $this->request->getPost('draw') ?? 1;
    $start     = $this->request->getPost('start') ?? 0;
    $length    = $this->request->getPost('length') ?? 10;
    $searchVal = $this->request->getPost('search')['value'] ?? '';

    $condition = "u.status != 9";

    if (!empty($searchVal)) {
        $searchVal     = trim(preg_replace('/\s+/', ' ', $searchVal));
        $noSpaceSearch = str_replace(' ', '', strtolower($searchVal));

        $condition .= " AND (
            REPLACE(LOWER(u.name), ' ', '') LIKE '%" . $this->db->escapeLikeString($noSpaceSearch) . "%'
            OR REPLACE(LOWER(u.email), ' ', '') LIKE '%" . $this->db->escapeLikeString($noSpaceSearch) . "%'
        )";
    }

    // Map DataTables column indexes to DB columns
    $columns = ['slno', 'name', 'email', 'role_name', 'action', 'user_id'];
    $orderColumnIndex = $this->request->getPost('order')[0]['column'] ?? 0;
    $orderDir = $this->request->getPost('order')[0]['dir'] ?? 'desc';
    $orderBy = $columns[$orderColumnIndex] ?? 'user_id';

    // Fetch paginated users
    $users = $this->userModel->getAllFilteredRecords($condition, $start, $length, $orderBy, $orderDir);

    // Format result
    $result = [];
    $slno = $start + 1;
    foreach ($users as $user) {
        $result[] = [
            'slno'      => $slno++,
            'user_id'   => $user->user_id,
            'name'      => $user->name,
            'email'     => $user->email,
            'role_name' => $user->role_name ?? 'No Role'
        ];
    }

    // Counts
    $totalCount    = $this->userModel->getAllUserCount();
    $filteredCount = $this->userModel->getFilterUserCount($condition);

    return $this->response->setJSON([
        "draw"            => intval($draw),
        "recordsTotal"    => intval($totalCount),
        "recordsFiltered" => intval($filteredCount),
        "data"            => $result
    ]);
}


}