<?php
namespace App\Controllers\admin;
use App\Controllers\BaseController;
use App\Models\Role_Model;
use App\Models\RoleMenu_Model;

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
			$template.= view('admin/rolelist');
            $template.= view('admin/common/footer');
			$template.= view('admin/page_scripts/rolesjs');
			return $template;
	}
	public function rolelist()
	{
	        $template = view('admin/common/header');
            $template.= view('admin/common/sidemenu');
			$template.= view('admin/manage_role');
            $template.= view('admin/common/footer');
			$template.= view('admin/page_scripts/rolesjs');
			return $template;
	}
	 public function rolelistajax()
{
    header('Content-Type: application/json');

    $draw = $_POST['draw'] ?? 1;
    $fromstart = $_POST['start'] ?? 0;
    $tolimit = $_POST['length'] ?? 10;
    $search = $_POST['search']['value'];
    $condition = "1=1";

    if (!empty($search)) {
        $noSpaceSearch = str_replace(' ', '', strtolower($search));

        $condition .= " AND (
            REPLACE(LOWER(role_name), ' ', '') LIKE '%{$noSpaceSearch}%'
            
        )";
    }

    // Sorting
    $columns = ['slno', 'role_name', 'permissions', 'created_at', 'updated_at', 'action', 'role_id'];
    $orderColumnIndex = $_POST['order'][0]['column'] ?? 0;
    $orderDir = $_POST['order'][0]['dir'] ?? 'desc';
    $orderBy = $columns[$orderColumnIndex] ?? 'role_id';
    $allowedOrderColumns = ['role_name', 'created_at', 'updated_at', 'role_id'];
    if (!in_array($orderBy, $allowedOrderColumns)) {
        $orderBy = 'role_id';
    }

    $slno = $fromstart + 1;
    $totalRec = $this->roleModel->getAllFilteredRecords($condition, $fromstart, $tolimit, $orderBy, $orderDir);
    $result = [];
    

    foreach ($totalRec as $role) {
        $permissions = $this->roleMenuModel
            ->where('role_id', $role->role_id)
            ->where('access', 1)
            ->findAll();

        $menuList = [];
        foreach ($permissions as $perm) {
            $menuKey = $perm['menu_name'];
            $menuLabel = $this->menus[$menuKey] ?? $menuKey;
            $menuList[] = $menuLabel;
        }

        if (!empty($searchLower)) {
            $allPermissions = implode(' ', array_map('strtolower', $menuList));
            $created = date('d-m-Y', strtotime($role->created_at ?? ''));
            $updated = date('d-m-Y', strtotime($role->updated_at ?? ''));
            $roleName = strtolower($role->role_name);
            $searchText = "$roleName $allPermissions $created $updated";

            if (strpos($searchText, $searchLower) === false) {
                continue;
            }
        }

        $result[] = [
            'slno'        => $slno++,
            'role_id'     => $role->role_id,
            'role_name'   => $role->role_name,
            'created_at'  => $role->created_at,
            'updated_at'  => $role->updated_at,
            'permissions' => $menuList
        ];
    }
   $totalRec = $this->roleModel->getAllFilteredRecords($condition, $fromstart, $tolimit, $orderBy, $orderDir);
    $totalCount = $this->roleModel->getAllRoleCount();
    $filteredCountObj = $this->roleModel->getFilterRoleCount($condition);
    $filteredCount = $filteredCountObj->filRecords ?? 0;

    echo json_encode([
        "draw" => intval($draw),
        "recordsTotal" => $totalCount,
        "recordsFiltered" => $filteredCount,
        "data" => $result
    ]);
}
	
    
}