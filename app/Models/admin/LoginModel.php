<?php
namespace App\Models\admin;

use CodeIgniter\Model;

class LoginModel extends Model
{
    protected $table = 'user';  
    protected $primaryKey = 'user_id';

    public function checkLoginUser($email, $password)
    {
        $user = $this->where('email', $email)->first();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                if (!isset($user['role_name']) && isset($user['role_id'])) {
                    $role = $this->db->table('role_acces')
                                     ->where('role_id', $user['role_id'])
                                     ->get()
                                     ->getRowArray();

                    $user['role_name'] = $role['role_name'] ?? 'user';
                }

                return (object) [
                    'user_id'   => $user['user_id'],
                    'name'      => $user['name'],
                    'email'     => $user['email'],
                    'role_name' => $user['role_name'], 
                ];
            }
        }

        return false;
    }
}
