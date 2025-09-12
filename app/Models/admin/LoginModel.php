<?php 
namespace App\Models\admin;

use CodeIgniter\Model;

class LoginModel extends Model {

    protected $table = 'user';
    protected $primaryKey = 'user_id';
    protected $allowedFields = ['role_id','name','email','password','status'];

    public function checkLoginUser($email, $password) {
        // get user by email
        $user = $this->where('email', $email)->first();

        if (!$user) {
            return false; // user not found
        }

        // verify password hash
        if (password_verify($password, $user['password'])) {
            return (object) $user;
        }

        return false;
    }
}
