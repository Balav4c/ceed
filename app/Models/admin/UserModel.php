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
                        ->where('status', 1) // only active roles
                        ->get()
                        ->getResult();
    }
public function getAllUserCount()
{
    
    return $this->db->table($this->table)
                    ->where('status !=', 9)
                    ->countAllResults();
}

public function getAllFilteredRecords($condition, $start, $length, $orderBy = 'user_id', $orderDir = 'desc')
{
    $builder = $this->db->table($this->table . ' u')
        ->select('u.user_id, u.name, u.email, r.role_name')
        ->join('roles r', 'r.role_id = u.role_id', 'left')
        ->where('u.status !=', 9)
        ->where($condition, null, false);

    if ($orderBy === 'r.role_name') {
    $builder->orderBy('r.role_name', $orderDir);
} else {
    // remove "u." prefix if already there
    $orderBy = str_replace('u.', '', $orderBy);
    $builder->orderBy('u.' . $orderBy, $orderDir);
}


    return $builder->limit($length, $start)->get()->getResult();
}

public function getFilterUserCount($condition)
{
    return $this->db->table($this->table . ' u')
        ->join('roles r', 'r.role_id = u.role_id', 'left')
        ->where('u.status !=', 9)
        ->where($condition, null, false)
        ->countAllResults(false);
}
     
}
