<?php
namespace App\Controllers\admin;
use App\Controllers\BaseController;
use App\Models\admin\RoleModel;
use App\Models\admin\RoleMenuModel;

class ManageRole extends BaseController
{

    public function __construct()
    {
        $this->roleModel = new RoleModel();
        $this->roleMenuModel = new RoleMenuModel();
        $this->session = \Config\Services::session();
        $this->input = \Config\Services::request();
         if (!$this->session->has('user_id')) {
            header('Location: ' . base_url('admin'));
            exit();
        }
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
    $role_id = $request->getPost('role_id');
    $role_name = trim($request->getPost('role_name'));
    $menus = $request->getPost('menus') ?? [];

    $normalized_role_name = trim(preg_replace('/\s+/', ' ', strtolower($role_name)));

    $duplicate = $this->roleModel
        ->where('REPLACE(LOWER(TRIM(role_name)), " ", "") =', str_replace(' ', '', $normalized_role_name))
        ->where('role_id !=', $role_id) 
        ->first();

    if ($duplicate) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Role Already Exists.'
        ]);
    }

    if (empty($role_name)) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Role Name Is Required'
        ]);
    }

    if ($role_id) {
        $existingRole = $this->roleModel->find($role_id);
        $oldRoleName  = $existingRole['role_name'] ?? '';
        $oldMenus     = $this->roleMenuModel->where('role_id', $role_id)->findColumn('menu_name') ?? [];

        sort($oldMenus);
        $newMenus = $menus;
        sort($newMenus);

        $nameChanged  = ($oldRoleName !== $role_name);
        $menusChanged = ($oldMenus !== $newMenus);

        if (!$nameChanged && !$menusChanged) {
            return $this->response->setJSON([
                'status' => 'info',
                'message' => 'No Changes Made.'
            ]);
        }

        if ($nameChanged) {
            $this->roleModel->update($role_id, ['role_name' => $role_name]);
        }

        if ($menusChanged) {
            $this->roleMenuModel->where('role_id', $role_id)->delete();
            if (!empty($menus)) {
                $data = [];
                foreach ($menus as $menuName) {
                    $data[] = [
                        'role_id'   => $role_id,
                        'menu_name' => $menuName,
                        'access'    => 1
                    ];
                }
                $this->roleMenuModel->insertBatch($data);
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => $nameChanged && $menusChanged
                ? 'Role and permissions updated successfully!'
                : ($nameChanged ? 'Role Updated Successfully!' : 'Permissions Updated Successfully!')
        ]);

    } else {
        $role_id = $this->roleModel->insert([
            'role_name' => $role_name,
            'status'    => 1
        ], true);

        if (!$role_id) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed To Add Role'
            ]);
        }

        if (!empty($menus)) {
            $data = [];
            foreach ($menus as $menuName) {
                $data[] = [
                    'role_id'   => $role_id,
                    'menu_name' => $menuName,
                    'access'    => 1
                ];
            }
            $this->roleMenuModel->insertBatch($data);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Role Added Successfully!'
        ]);
    }
}


    public function rolelistajax()
    {
        $draw = $_POST['draw'] ?? 1;
        $fromstart = $_POST['start'] ?? 0;
        $tolimit = $_POST['length'] ?? 10;
        $search = $_POST['search']['value'];
        $condition = "1=1";
        if (!empty($search)) {
            $search = trim(preg_replace('/\s+/', ' ', $search));
            $noSpaceSearch = str_replace(' ', '', strtolower($search));
            $condition .= " AND REPLACE(LOWER(role_name), ' ', '') LIKE '%" .
                $this->roleModel->db->escapeLikeString($noSpaceSearch) . "%'";
        }

        $columns = ['role_name', 'status', 'role_id'];
        $orderColumnIndex = $_POST['order'][0]['column'] ?? 0;
        $orderDir = $_POST['order'][0]['dir'] ?? 'desc';
        $orderBy = $columns[$orderColumnIndex] ?? 'role_id';
        $allowedOrderColumns = ['role_name', 'role_id'];
        if (!in_array($orderBy, $columns)) {
            $orderBy = 'role_id';
        }

        $totalRec = $this->roleModel->getAllFilteredRecords($condition, $fromstart, $tolimit, $orderBy, $orderDir);
        $result = [];

        $result = [];
        $slno = $fromstart + 1;

        foreach ($totalRec as $role) {
            $permissions = $this->roleMenuModel
                ->where('role_id', $role->role_id)
                ->where('access', 1)
                ->findAll();

            $menuList = array_column($permissions, 'menu_name');
            $displayMenus = [];
            foreach ($menuList as $menu) {
                $displayMenus[] = ucwords(str_replace('_', ' ', $menu));
            }

            $result[] = [
                'slno' => $slno++,
                'role_id' => $role->role_id,
                'role_name' => $role->role_name,
                'status' => $role->status,
                'permissions' => $displayMenus
            ];
        }

        // Counts
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

    public function toggleStatus()
    {
        if ($this->request->isAJAX()) {
            $role_id = $this->request->getPost('role_id');
            $status = $this->request->getPost('status');

            if (!$role_id || !in_array($status, ['1', '2'])) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Invalid status value'
                ]);
            }

            $updated = $this->roleModel->update($role_id, ['status' => $status]);

            if ($updated) {
                return $this->response->setJSON(['status' => 'success','message' =>'Status Updated Successfully!']);
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Update Failed'
                ]);
            }
        }
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Invalid request'
        ]);
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
        $template .= view('admin/add_role', $data);
        $template .= view('admin/common/footer');
        $template .= view('admin/page_scripts/rolesjs');

        return $template;
    }
    public function update($id)
    {
        $roleName = $this->request->getPost('role_name');
        $menus = $this->request->getPost('menus');

        $this->roleModel->update($id, [
            'role_name' => $roleName
        ]);

        $this->roleMenuModel->where('role_id', $id)->delete();

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
            'message' => 'Role Updated Successfully!'
        ]);
    }
   public function delete()
{
    if (!$this->request->isAJAX()) {
        return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
    }

    $id = $this->request->getPost('id'); // get role_id from AJAX

    if (!$id) {
        return $this->response->setJSON(['status' => 'error', 'message' => 'Missing role ID']);
    }

    $updated = $this->roleModel->update($id, ['status' => 9]);

    if ($updated) {
        return $this->response->setJSON(['status' => 'success', 'message' => 'Role deleted successfully.']);
    } else {
        return $this->response->setJSON(['status' => 'error', 'message' => 'Delete failed.']);
    }
}


}