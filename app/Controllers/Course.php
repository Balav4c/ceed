<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CourseModel;
use App\Models\CourseModuleModel;
use App\Models\CourseVideoModel;
use App\Models\ModuleProgressModel;
class Course extends BaseController
{
    public function index()
    {
        $courseModel = new CourseModel();
        $moduleModel = new CourseModuleModel();

        $courses = $courseModel
            ->where('status', 1)
            ->orderBy('course_id', 'ASC')
            ->findAll();

        // Add module count for each course
        foreach ($courses as &$course) {
            $course['module_count'] = $moduleModel
                ->where('course_id', $course['course_id'])
                ->where('status', 1)
                ->countAllResults();
        }

        $data['courses'] = $courses;

        echo view('common/course_header');
        echo view('course', $data);
        echo view('common/course_footer');
    }
    public function modules($courseId)
    {
        $userId = session()->get('user_id');
        // if (!$userId) {
        //     return redirect()->to(base_url('login')); // redirect to login if no user
        // }

        $courseModel = new CourseModel();
        $moduleModel = new CourseModuleModel();
        $moduleProgressModel = new ModuleProgressModel();

        $data['course'] = $courseModel->find($courseId);

        if (!$data['course']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Course not found');
        }

        $modules = $moduleModel
            ->where('course_id', $courseId)
            ->where('status', 1)
            ->orderBy('module_id', 'ASC')
            ->findAll();

        $unlockNext = true;

        foreach ($modules as $index => &$mod) {
            $completed = $moduleProgressModel
                ->where('module_id', $mod['module_id'])
                ->where('user_id', $userId)
                ->where('status', 3) // completed
                ->first() ? 1 : 0;

            $mod['completed'] = $completed;
            $mod['lessons_count'] = 4;
            $mod['locked'] = !$unlockNext ? 1 : 0;
            $unlockNext = $completed ? true : false;

            if ($index == 0)
                $mod['level'] = "Beginner";
            elseif ($index == 1)
                $mod['level'] = "Intermediate";
            else
                $mod['level'] = "Genius";
        }

        $data['modules'] = $modules;

        echo view('common/course_header');
        echo view('course_modules', $data);
        echo view('common/course_footer');
    }
    public function moduleDetails($moduleId)
    {
        $moduleModel = new CourseModuleModel();
        $courseModel = new CourseModel();

        // Fetch module details
        $module = $moduleModel->find($moduleId);

        if (!$module) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Module not found");
        }

        $course = $courseModel->find($module['course_id']);

        $data = [
            'module' => $module,
            'course' => $course,
        ];

        echo view('common/course_header');
        echo view('module1', $data);
        echo view('common/course_footer');
    }
    public function lesson($moduleId)
    {
        $moduleModel = new CourseModuleModel();
        $courseVideoModel = new CourseVideoModel();

        $module = $moduleModel->find($moduleId);

        if (!$module) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Module not found");
        }
        $videos = $courseVideoModel->where('module_id', $moduleId)->findAll();

        $data = [
            'module' => $module,
            'videos' => $videos
        ];

        return view('lesson_viewer', $data);
    }

}
