<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CourseModel;

class Course extends BaseController
{
    public function index()
    {
         $template  = view('common/course_header');
    $template .= view('course');
    $template .= view('common/course_footer');

    return $template;
       
    }
}
