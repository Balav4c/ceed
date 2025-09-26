<?php
namespace App\Models\admin;

use CodeIgniter\Model;

class Leaderboard_Model extends Model
{
    protected $table = 'leader_board';
    protected $primaryKey = 'leaderboard_id';
    protected $allowedFields = ['user_id','course_id','module_id','score','rank','status','points','created_at','updated_at'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getAllLeaderboardCount()
    {
        return $this->db->table($this->table)
            ->where('status !=', 9)
            ->countAllResults();
    }

    public function getFilterLeaderboardCount($condition)
    {
        $row = $this->db->table($this->table)
            ->select('COUNT(*) as filRecords')
            ->join('user u','u.user_id = leader_board.user_id','left')
            ->join('course c','c.course_id = leader_board.course_id','left')
            ->join('course_modules m','m.module_id = leader_board.module_id','left')
            ->where($condition, null, false)
            ->get()
            ->getRow();
        return $row ? $row->filRecords : 0;
    }

   public function getAllFilteredRecords($condition, $start, $length, $orderBy = 'leaderboard_id', $orderDir = 'desc')
{
    return $this->db->table($this->table)
        ->select("
            leader_board.leaderboard_id,
            leader_board.score,
            leader_board.rank,
            leader_board.status,
            DATE_FORMAT(leader_board.created_at,'%d-%m-%Y') AS created_at,
            u.name AS user_name,
            c.name AS course_name,
            m.module_name
        ", false)   
        ->join('user u','u.user_id = leader_board.user_id','left')
        ->join('course c','c.course_id = leader_board.course_id','left')
        ->join('course_modules m','m.module_id = leader_board.module_id','left')
        ->where($condition, null, false)
        ->orderBy($orderBy, $orderDir)
        ->limit($length, $start)
        ->get()
        ->getResult();
}
public function getUserStats($userId)
{
    return $this->select('COUNT(DISTINCT course_id) as total_courses, SUM(points) as total_points')
                ->where('user_id', $userId)
                ->first();
}

}
