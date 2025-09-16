<?php

namespace App\Models\admin;

use CodeIgniter\Model;

class CourseModel extends Model
{
    protected $table = 'courses';
    protected $primaryKey = 'course_id';
    protected $allowedFields = ['name', 'description', 'duration_weeks'];
    protected $useTimestamps = true;
}