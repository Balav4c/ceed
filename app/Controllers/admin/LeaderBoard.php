<?php
namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Models\admin\Leaderboard_Model;
use App\Models\admin\CourseModel;
use App\Models\admin\CourseModuleModel;

class LeaderBoard extends BaseController
{
    public function __construct()
    {
        $this->leaderboardModel = new Leaderboard_Model();
        $this->courseModel      = new CourseModel();
        $this->moduleModel      = new CourseModuleModel();
        $this->session          = \Config\Services::session();
        $this->input            = \Config\Services::request();

        if (!$this->session->has('user_id')) {
            header('Location: ' . base_url('admin'));
            exit();
        }
    }

    public function index()
    {
        $template = view('admin/common/header');
        $template .= view('admin/common/sidemenu');
        $template .= view('admin/manage_leaderboard');       
        $template .= view('admin/common/footer');
        $template .= view('admin/page_scripts/userjs'); 
        return $template;
    }

   public function leaderboardListAjax()
{
    $draw      = $this->request->getPost('draw') ?? 1;
    $start     = $this->request->getPost('start') ?? 0;
    $length    = $this->request->getPost('length') ?? 50; 
    $search    = $this->request->getPost('search')['value'] ?? '';
    $selectedDate = $this->request->getPost('selected_date'); 

    $condition = "1=1 AND leader_board.status != 9";
    if (!empty($selectedDate)) {
        $condition .= " AND DATE(leader_board.created_at) = " . $this->leaderboardModel->db->escape($selectedDate);
    } else {
        $condition .= " AND DATE(leader_board.created_at) = CURDATE()";
    }
    if (!empty($search)) {
        $search = trim(preg_replace('/\s+/', ' ', $search));
        $noSpaceSearch = str_replace(' ', '', strtolower($search));
        $condition .= " AND (
            REPLACE(LOWER(u.name),' ','') LIKE '%" . $this->leaderboardModel->db->escapeLikeString($noSpaceSearch) . "%' OR
            REPLACE(LOWER(c.name),' ','') LIKE '%" . $this->leaderboardModel->db->escapeLikeString($noSpaceSearch) . "%' OR
            REPLACE(LOWER(m.module_name),' ','') LIKE '%" . $this->leaderboardModel->db->escapeLikeString($noSpaceSearch) . "%'
        )";
    }
    $columns = ['u.name','c.name','m.module_name','leader_board.score','leader_board.rank','leader_board.status','leader_board.created_at','leader_board.leaderboard_id'];
    $orderColumnIndex = $this->request->getPost('order')[0]['column'] ?? 0;
    $orderDir = $this->request->getPost('order')[0]['dir'] ?? 'desc';
    $orderBy  = $columns[$orderColumnIndex] ?? 'leader_board.rank';
    $records = $this->leaderboardModel->getAllFilteredRecords($condition, $start, $length, $orderBy, $orderDir);
    $totalCount    = $this->leaderboardModel->getAllLeaderboardCount();
    $filteredCount = $this->leaderboardModel->getFilterLeaderboardCount($condition);

    $data = [];
    $slno = $start + 1;
    foreach ($records as $row) {
        $data[] = [
            'slno'        => $slno++,
            'leaderboard_id'=> $row->leaderboard_id,
            'name'        => $row->user_name ?? 'N/A',
            'course_name' => $row->course_name ?? 'N/A',
            'module_name' => $row->module_name ?? 'N/A',
            'score'       => $row->score ?? 'N/A',   
            'rank'        => $row->rank ?? 'N/A',    
            'status'      => $row->status,
            'date'        => $row->created_at
        ];
    }
    return $this->response->setJSON([
        "draw"            => intval($draw),
        "recordsTotal"    => $totalCount,
        "recordsFiltered" => $filteredCount,
        "data"            => $data
    ]);
}
    // public function delete()
    // {
    //     $leaderboard_id = $this->request->getPost('leaderboard_id');
    //     if (!$leaderboard_id) {
    //         return $this->response->setJSON([
    //             'success' => false,
    //             'message' => 'Leaderboard ID Is Required.'
    //         ]);
    //     }
    //     $this->leaderboardModel->update($leaderboard_id, [
    //         'status'     => 9, 
    //         'updated_at' => date("Y-m-d H:i:s")
    //     ]);

    //     return $this->response->setJSON([
    //         'success' => true,
    //         'message' => 'Leaderboard Entry Deleted Successfully.'
    //     ]);
    // }
    public function toggleStatus()
    {
    if ($this->request->isAJAX()) {
        $leaderboard_id = $this->request->getPost('leaderboard_id');
        $status         = $this->request->getPost('status');

        if (!$leaderboard_id || !in_array($status, ['1','2'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid Status Value'
            ]);
        }
        $status = (int)$status;
        $updated = $this->leaderboardModel->update($leaderboard_id, [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        if ($updated) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Status Updated Successfully!'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Update Failed.'
            ]);
        }
    }
    return $this->response->setJSON([
        'success' => false,
        'message' => 'Invalid Request'
    ]);
    }
}
