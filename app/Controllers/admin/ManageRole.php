<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\Admin\RoleModel;
use App\Models\Admin\RoleMenuModel;

class ManageRole extends BaseController
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
            $role_id = $roleModel->insert([
                'role_name' => $role_name,
                'status' => 1
            ], true);
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

        // Columns for sorting
        $columns = ['role_name', 'status', 'role_id'];
        $orderColumnIndex = $_POST['order'][0]['column'] ?? 0;
        $orderDir = $_POST['order'][0]['dir'] ?? 'desc';
        $orderBy = $columns[$orderColumnIndex] ?? 'role_id';
        $allowedOrderColumns = ['role_name', 'role_id'];
        if (!in_array($orderBy, $columns)) {
            $orderBy = 'role_id';
        }

        // Fetch roles
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
                return $this->response->setJSON(['status' => 'success']);
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Update failed'
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
            'message' => 'Role updated successfully!'
        ]);
    }
    public function delete($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $updated = $this->roleModel->update($id, ['status' => 9]);

        if ($updated) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Role deleted successfully.']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Delete failed.']);
        }
    }





}