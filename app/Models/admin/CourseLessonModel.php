<?php

namespace App\Models\admin;

use CodeIgniter\Model;

class CourseLessonModel extends Model
{
    protected $table = 'course_lessons';
    protected $primaryKey = 'lesson_id';
    protected $allowedFields = [
        'lesson_id',
        'module_id',
        'course_id',
        'lesson_title',
        'videos',  
        'status',
        'created_at',
        'updated_at'
    ];
    protected $useTimestamps = true; 
}
