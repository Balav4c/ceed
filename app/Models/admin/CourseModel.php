<?php

namespace App\Models\admin;

use CodeIgniter\Model;

class CourseModel extends Model
{
    protected $table = 'course';
    protected $primaryKey = 'course_id';
    protected $allowedFields = ['name', 'description', 'duration_weeks','status'];
    protected $useTimestamps = true;
      public function getAllCourses()
{
    return $this->where('status !=', 9)->findAll(); 
}

    
public function getAllCourseCount()
{
    return $this->db->table($this->table)
        ->where('status !=', 9)
        ->countAllResults();
}

  public function getAllFilteredRecords($condition, $start, $length, $orderBy = 'course_id', $orderDir = 'desc')
{
    return $this->db->table($this->table)
        ->where('status !=', 9) 
        ->where($condition, null, false)
        ->orderBy($orderBy, $orderDir)
        ->limit($length, $start)
        ->get()
        ->getResult();
}

 public function getFilterCourseCount($condition)
{
    return $this->db->table($this->table)
        ->select('COUNT(*) as filRecords')
        ->where('status !=', 9) 
        ->where($condition, null, false)
        ->get()
        ->getRow();
}
}
