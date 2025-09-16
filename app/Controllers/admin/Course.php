<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Models\admin\CourseModel;
use App\Models\admin\CourseModuleModel;

class ManageCourse extends BaseController
{
    protected $courseModel;
    protected $moduleModel;

    public function __construct()
    {
        $this->courseModel = new CourseModel();
        $this->moduleModel = new CourseModuleModel();
    }

    public function index()
    {
        return view('admin/manage_course');
    }

    public function courseListAjax()
    {
        $courses = $this->courseModel->findAll();
        return $this->response->setJSON(['data' => $courses]);
    }

    public function form($id = null)
    {
        $data['course'] = $id ? $this->courseModel->find($id) : null;
        $data['modules'] = $id ? $this->moduleModel->where('course_id', $id)->findAll() : [];
        return view('admin/add_course', $data);
    }

    public function save()
    {
        $id = $this->request->getPost('course_id');
        $courseData = [
            'name'          => $this->request->getPost('name'),
            'description'   => substr($this->request->getPost('description'), 0, 250),
            'duration_weeks'=> $this->request->getPost('duration_weeks')
        ];

        if ($id) {
            $this->courseModel->update($id, $courseData);
            $courseId = $id;
        } else {
            $this->courseModel->insert($courseData);
            $courseId = $this->courseModel->insertID();
        }

        // Save modules
        $this->moduleModel->where('course_id', $courseId)->delete(); // reset modules
        $moduleNames = $this->request->getPost('module_name');
        $durations   = $this->request->getPost('module_duration');

        if (!empty($moduleNames)) {
            foreach ($moduleNames as $index => $mname) {
                if (!empty($mname)) {
                    $this->moduleModel->insert([
                        'course_id' => $courseId,
                        'module_name' => $mname,
                        'duration_weeks' => $durations[$index] ?? ''
                    ]);
                }
            }
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Course saved successfully']);
    }

    public function delete($id)
    {
        $this->courseModel->delete($id);
        return $this->response->setJSON(['status' => 'success', 'message' => 'Course deleted successfully']);
    }
}
