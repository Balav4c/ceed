<?php
namespace App\Controllers\admin;
use App\Controllers\BaseController;
use App\Models\Admin\RoleModel;
use App\Models\Admin\RoleMenuModel;

class Manage_Role extends BaseController
{

    public function __construct()
    {
        $this->roleModel = new RoleModel();
        $this->roleMenuModel = new RoleMenuModel();
        $this->session = \Config\Services::session();
        $this->input = \Config\Services::request();

    }
    public function index()
    {
        $template = view('admin/common/header');
        $template .= view('admin/common/sidemenu');
        $template .= view('admin/manage_role');
        $template .= view('admin/common/footer');
        $template .= view('admin/page_scripts/rolesjs');
        return $template;
    }
    public function addrole()
    {
        $template = view('admin/common/header');
        $template .= view('admin/common/sidemenu');
        $template .= view('admin/add_role');
        $template .= view('admin/common/footer');
        $template .= view('admin/page_scripts/rolesjs');
        return $template;
    }
    public function store()
    {
        $request = $this->request;
        $roleModel = new RoleModel();
        $roleMenuModel = new RoleMenuModel();

        $role_id = $request->getPost('role_id');
        $role_name = $request->getPost('role_name');
        $menus = $request->getPost('menus');
        if (empty($role_name)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Role Name is required'
            ]);
        }

        if ($role_id) {
            $roleModel->update($role_id, ['role_name' => $role_name]);
        } else {
            $role_id = $roleModel->insert(['role_name' => $role_name], true);
        }

        if (!$role_id) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to save role (check DB structure)'
            ]);
        }

        $roleMenuModel->where('role_id', $role_id)->delete();
        if (!empty($menus)) {
            $data = [];
            foreach ($menus as $menuName) {
                $data[] = [
                    'role_id' => $role_id,
                    'menu_name' => $menuName,
                    'access' => 1
                ];
            }
            $roleMenuModel->insertBatch($data);
        }

        return $this->response->setJSON(['status' => 'success']);

    }
    public function rolelistajax()
    {
        try {
            $draw = $this->request->getPost('draw') ?? 1;
            $start = $this->request->getPost('start') ?? 0;
            $length = $this->request->getPost('length') ?? 10;
            $searchVal = $this->request->getPost('search')['value'] ?? '';

            $condition = "1=1";
            if (!empty($searchVal)) {
                $searchVal = trim(preg_replace('/\s+/', ' ', $searchVal));
                $noSpaceSearch = str_replace(' ', '', strtolower($searchVal));
                $condition .= " AND REPLACE(LOWER(role_name), ' ', '') LIKE '%" . $this->roleModel->db->escapeLikeString($noSpaceSearch) . "%'";
            }

            $columns = ['role_name', 'created_at', 'updated_at', 'role_id'];
            $orderColumnIndex = $this->request->getPost('order')[0]['column'] ?? 0;
            $orderDir = $this->request->getPost('order')[0]['dir'] ?? 'desc';
            $orderBy = $columns[$orderColumnIndex] ?? 'role_id';
            if (!in_array($orderBy, $columns))
                $orderBy = 'role_id';

            $roles = $this->roleModel->getAllFilteredRecords($condition, $start, $length, $orderBy, $orderDir);

            $result = [];
            $slno = $start + 1;

            foreach ($roles as $role) {
                $permissions = $this->roleMenuModel
                    ->select('menu_name')
                    ->where('role_id', $role->role_id)
                    ->where('access', 1)
                    ->findAll();

                $menuList = array_column($permissions, 'menu_name');

                $result[] = [
                    'slno' => $slno++,
                    'role_id' => $role->role_id,
                    'role_name' => $role->role_name,
                    'created_at' => $role->created_at,
                    'updated_at' => $role->updated_at,
                    'permissions' => $menuList
                ];
            }

            $totalCount = $this->roleModel->getAllRoleCount();
            $filteredCountObj = $this->roleModel->getFilterRoleCount($condition);
            $filteredCount = $filteredCountObj->filRecords ?? 0;

            return $this->response->setJSON([
                "draw" => intval($draw),
                "recordsTotal" => intval($totalCount),
                "recordsFiltered" => intval($filteredCount),
                "data" => $result
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'draw' => intval($this->request->getPost('draw') ?? 1),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage()
            ]);
        }
    }
    public function edit($id)
    {
        $role = $this->roleModel->find($id);
        $permissions = $this->roleMenuModel->where('role_id', $id)->findAll();
        $access = [];

        foreach ($permissions as $perm) {
            $access[$perm['menu_name']] = $perm['access'];
        }

        $data = [
            'role' => $role,
            'access' => $access,
        ];

        $template = view('admin/common/header');
        $template .= view('admin/common/sidemenu');
        $template .= view('admin/add_role',$data);  
        $template .= view('admin/common/footer');
        $template .= view('admin/page_scripts/rolesjs');

        return $template;
    }
   public function update($id)
{
    $roleName = $this->request->getPost('role_name');
    $menus = $this->request->getPost('menus'); // this is an array
    
    // update role name
    $this->roleModel->update($id, [
        'role_name' => $roleName
    ]);

    // clear old permissions
    $this->roleMenuModel->where('role_id', $id)->delete();

    // insert new permissions
    if (!empty($menus)) {
        foreach ($menus as $menu) {
            $this->roleMenuModel->insert([
                'role_id' => $id,
                'menu_name' => $menu,
                'access' => 1
            ]);
        }
    }

    return $this->response->setJSON([
        'status' => 'success',
        'message' => 'Role updated successfully!'
    ]);
}


}