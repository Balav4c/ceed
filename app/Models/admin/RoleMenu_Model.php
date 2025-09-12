<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleMenu_Model extends Model
{
    protected $table = 'role_menus';
    protected $primaryKey = 'rolemenu_id';
    protected $allowedFields = ['rolemenu_id','role_id','menu_name','access'];
}