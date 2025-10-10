<?php

namespace App\Models\admin;

use CodeIgniter\Model;

class CourseModuleModel extends Model
{
    protected $table = 'course_modules';
    protected $primaryKey = 'module_id';
    protected $allowedFields = ['course_id', 'module_name', 'description', 'about','module_level','duration_weeks', 'status'];
    protected $useTimestamps = true;

    public function getAllModules()
    {
        return $this->where('status !=', 9)->findAll();
    }

    public function getAllModuleCount()
    {
        return $this->where('status !=', 9)->countAllResults();
    }

    public function getAllFilteredRecords($condition, $start, $length, $orderBy = 'module_id', $orderDir = 'desc')
    {
        return $this->db->table($this->table . ' m')
            ->select('m.*, GROUP_CONCAT(cv.video_file) as module_videos')
            ->join('course_videos cv', 'cv.module_id = m.module_id AND cv.status = 1', 'left')
            ->where('m.status !=', 9)
            ->where($condition, null, false)
            ->groupBy('m.module_id')
            ->orderBy($orderBy, $orderDir)
            ->limit($length, $start)
            ->get()
            ->getResult();

    }

    public function getFilterModuleCount($condition)
    {
        return $this->db->table($this->table . ' m')
            ->join('course_videos cv', 'cv.module_id = m.module_id AND cv.status = 1', 'left')
            ->select('COUNT(DISTINCT m.module_id) as filRecords')
            ->where('m.status !=', 9)
            ->where($condition, null, false)
            ->get()
            ->getRow();

    }


}
