<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\CourseModuleModel;
use App\Models\ModuleProgressModel;
class Course extends BaseController
{
    public function index()
    {
        $courseModel = new CourseModel();

        $data['courses'] = $courseModel
            ->where('status', 1)
            ->orderBy('course_id', 'ASC')
            ->findAll();

        echo view('common/course_header');
        echo view('course', $data);
        echo view('common/course_footer');
    }
    public function modules($courseId)
    {
        $courseModel = new CourseModel();
        $moduleModel = new CourseModuleModel();
        $moduleProgressModel = new ModuleProgressModel();

        $data['course'] = $courseModel->find($courseId);

        $modules = $moduleModel
            ->where('course_id', $courseId)
            ->where('status', 1)
            ->orderBy('module_id', 'ASC')
            ->findAll();

        $userId = session()->get('user_id');

        $unlockNext = true; // First module unlocked initially

        foreach ($modules as $index => &$mod) {
            // Check completion
            $completed = $moduleProgressModel
                ->where('module_id', $mod['module_id'])
                ->where('user_id', $userId)
                ->where('status', 1)
                ->first() ? 1 : 0;

            $mod['completed'] = $completed;
            $mod['lessons_count'] = 4;

            $mod['locked'] = !$unlockNext ? 1 : 0;
            $unlockNext = $completed ? true : false;

            // Assign level (example logic)
            if ($index == 0) {
                $mod['level'] = "Beginner";
            } elseif ($index == 1) {
                $mod['level'] = "Intermediate";
            } else {
                $mod['level'] = "Genius";
            }

        }

        $data['modules'] = $modules;
        $data['unlockNext'] = true; // pass to view

        echo view('common/course_header');
        echo view('course_modules', $data);
        echo view('common/course_footer');
    }
    public function moduleDetails($moduleId)
    {
        $moduleModel = new \App\Models\CourseModuleModel();
        $courseModel = new \App\Models\CourseModel();

        // Fetch module details
        $module = $moduleModel->find($moduleId);

        if (!$module) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Module not found");
        }

        // Fetch parent course (optional)
        $course = $courseModel->find($module['course_id']);

        // Send data to view
        $data = [
            'module' => $module,
            'course' => $course,
        ];

        echo view('common/course_header');
        echo view('module1', $data); // view file
        echo view('common/course_footer');
    }
    public function lesson($moduleId)
    {
        $moduleModel = new \App\Models\CourseModuleModel();
        $courseVideoModel = new \App\Models\CourseVideoModel(); // Create model for course_videos table

        $module = $moduleModel->find($moduleId);

        if (!$module) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Module not found");
        }

        // Fetch videos for this module
        $videos = $courseVideoModel->where('module_id', $moduleId)->findAll();

        $data = [
            'module' => $module,
            'videos' => $videos
        ];

        return view('lesson_viewer', $data);
    }

}
