<?php
namespace App\Models;
use CodeIgniter\Model;

class CourseModel extends Model
{
    protected $table = 'course';
    protected $primaryKey = 'course_id';
    protected $allowedFields = ['name', 'description', 'duration_weeks', 'status', 'created_at', 'updated_at'];
}
