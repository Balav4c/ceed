<?php
namespace App\Models\admin;

use CodeIgniter\Model;

class LoginModel extends Model
{
    protected $table = 'user';  
    protected $primaryKey = 'user_id';
    protected $allowedFields = ['role_id','name', 'email', 'phone' , 'password', 'status', 'created_at', 'updated_at'];


    public function checkLoginUser($email, $password)
    {
        $users = $this->where('email', $email)
                      ->orderBy('user_id', 'DESC')
                      ->findAll();
        if (!$users) {
            return 'invalid';
        }
        foreach ($users as $user) {
            if ($user['status'] == 9) {
                continue;
            }
            if (!password_verify($password, $user['password'])) {
                continue;
            }
            if ($user['role_id'] != 1 && $user['status'] == 2) {
                return 'suspended';
            }
            return (object) $user;
        }
        return 'invalid';
    }

}
