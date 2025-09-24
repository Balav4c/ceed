<?php

namespace App\Models\admin;

use CodeIgniter\Model;

class CourseVideoModel extends Model
{
    protected $table = 'course_videos';
    protected $primaryKey = 'video_id';
    protected $allowedFields = ['module_id','video_file','status'];
    protected $useTimestamps = true;
}