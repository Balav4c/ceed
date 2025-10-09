<?php
namespace App\Models;

use CodeIgniter\Model;

class ModuleProgressModel extends Model
{
    protected $table = 'module_progress';
    protected $primaryKey = 'progress_id';
    protected $allowedFields = ['user_id', 'module_id', 'status'];
}
