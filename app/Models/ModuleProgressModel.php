<?php
namespace App\Models;

use CodeIgniter\Model;

class ModuleProgressModel extends Model
{
    protected $table = 'module_progress';
    protected $primaryKey = 'progress_id';

    protected $allowedFields = [
        'progress_id',
        'user_id',
        'course_id',
        'module_id',
        'status',
        'created_at',
        'updated_at'
    ];
}
