<?php

namespace App\Models\admin;

use CodeIgniter\Model;

class RoleModel extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'role_id';
    protected $allowedFields = ['role_id', 'role_name', 'status'];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getAllRoles()
{
    return $this->where('status !=', 9)->findAll(); // Show only active/inactive, not deleted
}

    
public function getAllRoleCount()
{
    return $this->db->table($this->table)
        ->where('status !=', 9)
        ->countAllResults();
}

  public function getAllFilteredRecords($condition, $start, $length, $orderBy = 'role_id', $orderDir = 'desc')
{
    return $this->db->table($this->table)
        ->where('status !=', 9) // exclude deleted
        ->where($condition, null, false)
        ->orderBy($orderBy, $orderDir)
        ->limit($length, $start)
        ->get()
        ->getResult();
}

 public function getFilterRoleCount($condition)
{
    return $this->db->table($this->table)
        ->select('COUNT(*) as filRecords')
        ->where('status !=', 9) // exclude deleted
        ->where($condition, null, false)
        ->get()
        ->getRow();
}

}
