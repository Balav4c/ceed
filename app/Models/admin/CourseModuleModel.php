<?php

namespace App\Models\admin;

use CodeIgniter\Model;

class CourseModuleModel extends Model
{
    protected $table = 'course_modules';
    protected $primaryKey = 'module_id';
    protected $allowedFields = ['course_id', 'module_name','description', 'duration_weeks','status'];
    protected $useTimestamps = true;
}