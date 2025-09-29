<?php
namespace App\Models;

use CodeIgniter\Model;

class LoginModel extends Model
{

    protected $table = 'user';
    protected $primaryKey = 'user_id';
    protected $allowedFields = ['role_id', 'name', 'email', 'password', 'reset_token', 'reset_expires', 'status'];

    public function checkLoginUser($email, $password)
    {
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
 public function findByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    public function saveResetToken($user_id, $token, $expires)
    {
        return $this->update($user_id, [
            'reset_token' => $token,
            'reset_expires' => $expires
        ]);
    }

    public function verifyToken($hashedToken)
    {
        return $this->where('reset_token', $hashedToken)
            ->where('reset_expires >=', date('Y-m-d H:i:s'))
            ->first();
    }
public function resetPassword($user_id, $newPassword)
    {
        return $this->update($user_id, [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT),
            'reset_token' => null,
            'reset_expires' => null
        ]);
    }

}
