<?php
namespace App\Models\admin;

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
}
