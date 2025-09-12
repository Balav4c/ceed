<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class RoleModel extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'role_id';
    protected $allowedFields = ['role_id','role_name'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

   public function getAllRoleCount()
{
    return $this->db->table($this->table)->countAllResults();
}

public function getAllFilteredRecords($condition, $start, $length, $orderBy = 'role_id', $orderDir = 'desc')
{
    return $this->db->table($this->table)
        ->where($condition, null, false) // raw where
        ->orderBy($orderBy, $orderDir)
        ->limit($length, $start)
        ->get()
        ->getResult();
}


public function getFilterRoleCount($condition)
{
    return $this->db->table($this->table)
        ->where($condition, null, false)
        ->countAllResults(false); // false to prevent resetting query
}


}
