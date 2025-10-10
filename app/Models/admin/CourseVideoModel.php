<?php

namespace App\Models\admin;

use CodeIgniter\Model;

class CourseVideoModel extends Model
{
    protected $table = 'course_videos';
    protected $primaryKey = 'video_id';
    protected $allowedFields = ['module_id','course_id','lesson_title','lesson_name','video_file','status'];
    protected $useTimestamps = true;
}