<?php

namespace App\Models\admin;

use CodeIgniter\Model;

class CourseModuleModel extends Model
{
    protected $table = 'course_modules';
    protected $primaryKey = 'module_id';
    protected $allowedFields = [
        'course_id',
        'module_name',
        'description',
        'about',
        'module_level',
        'duration_weeks',
        'status'
    ];
    protected $useTimestamps = true;

    /**
     * Get all active modules
     */
    public function getAllModules()
    {
        return $this->where('status !=', 9)->findAll();
    }

    /**
     * Get total module count (excluding deleted)
     */
    public function getAllModuleCount()
    {
        return $this->where('status !=', 9)->countAllResults();
    }

    /**
     * Get filtered and paginated module records
     */
    public function getAllFilteredRecords($condition, $start, $length, $orderBy = 'module_id', $orderDir = 'desc')
    {
        return $this->db->table($this->table . ' m')
            ->select('m.*')
            ->where('m.status !=', 9)
            ->where($condition, null, false)
            ->orderBy($orderBy, $orderDir)
            ->limit($length, $start)
            ->get()
            ->getResult();
    }

    /**
     * Get filtered module count (used for DataTables)
     */
    public function getFilterModuleCount($condition)
    {
        return $this->db->table($this->table . ' m')
            ->select('COUNT(DISTINCT m.module_id) as filRecords')
            ->where('m.status !=', 9)
            ->where($condition, null, false)
            ->get()
            ->getRow();
    }
}
