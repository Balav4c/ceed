<?php
namespace App\Models;

use CodeIgniter\Model;

class UserProfileModel extends Model
{
    protected $table = 'user_profile';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id', 'name', 'grade', 'school', 'bio',
        'phone', 'notification', 'status','profile_percentage'
    ];

    protected $useTimestamps = true; 
}
