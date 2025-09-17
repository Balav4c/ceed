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

    if (!$user) {
        return false; 
    }
    if (!password_verify($password, $user['password'])) {
        return false; 
    }
    if ($user['role_id'] != 1) {
        if ($user['status'] == 2) {
            return 'suspended';
        } elseif ($user['status'] == 9) {
            return false; 
        }
    }
    return (object) $user; 
}

}
