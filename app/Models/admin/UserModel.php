<?php
namespace App\Models\admin;
 
use CodeIgniter\Model;
 
class UserModel extends Model {
 
    protected $table = 'user';
    protected $primaryKey = 'user_id';
    protected $allowedFields = ['role_id','name', 'email', 'password', 'status', 'created_at', 'updated_at'];
 
    public function userInsert($data) {
        return $this->db->table($this->table)->insert($data);
    }
 
    public function updateUser($user_id, $data) {
        return $this->db->table($this->table)
                        ->where('user_id', $user_id)
                        ->update($data);
    }
 
    public function getAllRoles()
    {
        return $this->db->table('roles')
                        ->select('role_id, role_name')
                        ->where('status', 1)
                        ->get()
                        ->getResult();
    }
    public function getAllFilteredRecords($searchVal, $start, $length, $orderBy = 'u.user_id', $orderDir = 'desc')
    {
    $builder = $this->db->table($this->table . ' u')
        ->select('u.user_id, u.name, u.email, u.status, r.role_name')
        ->join('roles r', 'r.role_id = u.role_id', 'left')
        ->where('u.status !=', 9);
 
    if (!empty($searchVal)) {
        $noSpaceSearch = str_replace(' ', '', strtolower($searchVal));
 
        $builder->groupStart();
        $builder->where("LOWER(u.name) LIKE '%".$this->db->escapeLikeString(strtolower($searchVal))."%'", null, false);
        $builder->orWhere("LOWER(u.email) LIKE '%".$this->db->escapeLikeString(strtolower($searchVal))."%'", null, false);
        $builder->orWhere("LOWER(r.role_name) LIKE '%".$this->db->escapeLikeString(strtolower($searchVal))."%'", null, false);
 
        $builder->orWhere("REPLACE(LOWER(u.name), ' ', '') LIKE '%".$this->db->escapeLikeString($noSpaceSearch)."%' ", null, false);
        $builder->orWhere("REPLACE(LOWER(u.email), ' ', '') LIKE '%".$this->db->escapeLikeString($noSpaceSearch)."%' ", null, false);
        $builder->orWhere("REPLACE(LOWER(r.role_name), ' ', '') LIKE '%".$this->db->escapeLikeString($noSpaceSearch)."%' ", null, false);
        $builder->orWhere("REPLACE(LOWER(CONCAT(u.name, ' ', u.email, ' ', r.role_name)), ' ', '') LIKE '%".$this->db->escapeLikeString($noSpaceSearch)."%' ", null, false);
        $builder->groupEnd();
        }
 
    $builder->orderBy($orderBy, $orderDir);
    return $builder->limit($length, $start)->get()->getResult();
    }
 
    public function getFilterUserCount($searchVal)
    {
        $builder = $this->db->table($this->table . ' u')
        ->join('roles r', 'r.role_id = u.role_id', 'left')
        ->where('u.status !=', 9);
 
    if (!empty($searchVal)) {
        $noSpaceSearch = str_replace(' ', '', strtolower($searchVal));
 
        $builder->groupStart();
        $builder->where("LOWER(u.name) LIKE '%".$this->db->escapeLikeString(strtolower($searchVal))."%'", null, false);
        $builder->orWhere("LOWER(u.email) LIKE '%".$this->db->escapeLikeString(strtolower($searchVal))."%'", null, false);
        $builder->orWhere("LOWER(r.role_name) LIKE '%".$this->db->escapeLikeString(strtolower($searchVal))."%'", null, false);
 
        $builder->orWhere("REPLACE(LOWER(u.name), ' ', '') LIKE '%".$this->db->escapeLikeString($noSpaceSearch)."%' ", null, false);
        $builder->orWhere("REPLACE(LOWER(u.email), ' ', '') LIKE '%".$this->db->escapeLikeString($noSpaceSearch)."%' ", null, false);
        $builder->orWhere("REPLACE(LOWER(r.role_name), ' ', '') LIKE '%".$this->db->escapeLikeString($noSpaceSearch)."%' ", null, false);
        $builder->orWhere("REPLACE(LOWER(CONCAT(u.name, ' ', u.email, ' ', r.role_name)), ' ', '') LIKE '%".$this->db->escapeLikeString($noSpaceSearch)."%' ", null, false);
        $builder->groupEnd();
        }
 
        return $builder->countAllResults();
    }
 
    public function getAllUserCount()
    {
        return $this->db->table($this->table)
            ->where('status !=', 9)
            ->countAllResults();
    }
 
}