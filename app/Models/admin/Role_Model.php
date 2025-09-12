<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleModel extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'role_id';
    protected $allowedFields = ['role_id','role_name'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

      public function getAllRoleCount($company_id)
{
    return $this->db->table($this->table)
        ->countAllResults();
}

   public function getAllFilteredRecords($condition, $fromstart, $tolimit, $orderBy = 'role_id', $orderDir = 'desc')
{
    return $this->db->query("SELECT * FROM roles WHERE $condition ORDER BY $orderBy $orderDir LIMIT $fromstart, $tolimit")->getResult();
}


    
public function getFilterRoleCount($condition)
{
    return $this->db->query("SELECT COUNT(*) AS filRecords FROM role_acces WHERE $condition")->getRow();
}

}
