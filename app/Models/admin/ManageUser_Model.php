<?php
namespace App\Models;

use CodeIgniter\Model;
class ManageUser_Model extends Model
{
    protected $table      = 'user';       
    protected $primaryKey = 'user_id';    

    protected $allowedFields = [
        'name', 
        'email', 
        'password', 
        'role_id', 
        'status', 
        'created_on', 
        'updated_on'
    ];

    protected $useTimestamps = false; 
}
