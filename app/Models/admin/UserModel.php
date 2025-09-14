<?php
namespace App\Models\Admin;

use CodeIgniter\Model;

class UserModel extends Model {

    protected $table = 'user';
    protected $primaryKey = 'user_id';
    protected $allowedFields = ['role_id', 'name', 'email', 'password', 'status', 'created_at', 'updated_at'];

    public function userInsert($data) {
        return $this->db->table($this->table)->insert($data);
    }

    public function updateUser($user_id, $data) {
        return $this->db->table($this->table)
                        ->where('user_id', $user_id)
                        ->update($data);
    }
//  public function getAllUserCount()
// {
//     return $this->db->table($this->table)->countAllResults();
// }

// public function getAllFilteredRecords($condition, $start, $length, $orderBy = 'user_id', $orderDir = 'desc')
// {
//     $builder = $this->db->table($this->table . ' u')
//         ->select('u.user_id, u.name, u.email, r.role_name')
//         ->join('roles r', 'r.role_id = u.role_id', 'left')
//         ->where($condition, null, false);

//     // Fix ordering
//     if ($orderBy === 'role_name') {
//         $builder->orderBy('r.role_name', $orderDir);
//     } else {
//         $builder->orderBy('u.' . $orderBy, $orderDir);
//     }

//     return $builder->limit($length, $start)->get()->getResult();
// }

// public function getFilterUserCount($condition)
// {
//     return $this->db->table($this->table . ' u')
//         ->join('roles r', 'r.role_id = u.role_id', 'left')
//         ->where($condition, null, false)
//         ->countAllResults(false);
// }
public function getAllUserCount()
{
    // ✅ Count only non-deleted users
    return $this->db->table($this->table)
                    ->where('status !=', 9)
                    ->countAllResults();
}

public function getAllFilteredRecords($condition, $start, $length, $orderBy = 'user_id', $orderDir = 'desc')
{
    $builder = $this->db->table($this->table . ' u')
        ->select('u.user_id, u.name, u.email, r.role_name')
        ->join('roles r', 'r.role_id = u.role_id', 'left')
        ->where($condition, null, false);

    if ($orderBy === 'role_name') {
        $builder->orderBy('r.role_name', $orderDir);
    } else {
        $builder->orderBy('u.' . $orderBy, $orderDir);
    }

    return $builder->limit($length, $start)->get()->getResult();
}

public function getFilterUserCount($condition)
{
    return $this->db->table($this->table . ' u')
        ->join('roles r', 'r.role_id = u.role_id', 'left')
        ->where($condition, null, false)
        ->countAllResults(false);
}
     
}
