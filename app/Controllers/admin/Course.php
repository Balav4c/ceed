<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Models\admin\CourseModel;
use App\Models\admin\CourseModuleModel;
use App\Models\admin\CourseVideoModel;

class Course extends BaseController
{
    protected $courseModel;
    protected $moduleModel;
    protected $videoModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->input = \Config\Services::request();
        $this->courseModel = new CourseModel();
        $this->moduleModel = new CourseModuleModel();
        $this->videoModel = new CourseVideoModel();
         if (!$this->session->has('user_id')) {
            header('Location: ' . base_url('admin'));
            exit();
        }
    }

    public function index()
    {
        $template = view('admin/common/header');
        $template .= view('admin/common/sidemenu');
        $template .= view('admin/manage_course');
        $template .= view('admin/common/footer');
        $template .= view('admin/page_scripts/coursejs');
        return $template;
    }
    public function addCourse($id = null)
    {
        $data['course'] = $id ? $this->courseModel->find($id) : null;
        // $data['modules'] = $id ? $this->moduleModel->where('course_id', $id)->findAll() : [];
        $template = view('admin/common/header');
        $template .= view('admin/common/sidemenu');
        $template .= view('admin/add_course');
        $template .= view('admin/common/footer');
        $template .= view('admin/page_scripts/coursejs');
        return $template;

    }
    public function save()
    {
        $id = $this->request->getPost('course_id');
        $name = trim($this->request->getPost('name'));
        $description = $this->request->getPost('description');
        $plainDescription = $description;
        $duration_weeks = trim($this->request->getPost('duration_weeks'));

        if (empty($name) || empty($duration_weeks)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Please Fill In All Mandatory Fields.'
            ]);
        }

        $courseData = [
            'name' => $name,
            'description' => $plainDescription,
            'duration_weeks' => $duration_weeks,
            'status' => 1
        ];

        if ($id) {
            $this->courseModel->update($id, $courseData);
            $courseId = $id;
            $message = 'Course Updated Successfully!';
            $isUpdate = true;
        } else {
            $this->courseModel->insert($courseData);
            $courseId = $this->courseModel->insertID();
            $message = 'Course Saved Successfully! Now Add Modules.';
            $isUpdate = false;
        }
        $moduleNames = $this->request->getPost('module_name');
        $durations = $this->request->getPost('module_duration');

        if (!empty($moduleNames)) {
            $this->moduleModel->where('course_id', $courseId)->delete();

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

        return $this->response->setJSON([
            'status' => 'success',
            'message' => $message,
            'course_id' => $courseId,
            'is_update' => $isUpdate
        ]);
    }

    public function courseListAjax()
    {

        $request = service('request');

        $draw = $request->getPost('draw') ?? 1;
        $fromstart = $request->getPost('start') ?? 0;
        $tolimit = $request->getPost('length') ?? 10;
        $search = $request->getPost('search')['value'] ?? '';

        $condition = "1=1";
        if (!empty($search)) {
            $search = trim(preg_replace('/\s+/', ' ', $search));
            $noSpaceSearch = str_replace(' ', '', strtolower($search));
            $esc = $this->courseModel->db->escapeLikeString($noSpaceSearch);

            $condition .= " AND (
            REPLACE(LOWER(name), ' ', '') LIKE '%{$esc}%'
            OR REPLACE(LOWER(description), ' ', '') LIKE '%{$esc}%'
            OR REPLACE(LOWER(duration_weeks), ' ', '') LIKE '%{$esc}%'
        )";
        }

        $columns = ['slno', 'name', 'description', 'duration_weeks', 'status', 'course_id'];
        $orderColumnIndex = $request->getPost('order')[0]['column'] ?? 0;
        $orderDir = $request->getPost('order')[0]['dir'] ?? 'desc';
        $orderBy = $columns[$orderColumnIndex] ?? 'course_id';
        if ($orderBy === 'slno') {
            $orderBy = 'course_id';
        }

        $courseRecords = $this->courseModel
            ->getAllFilteredRecords($condition, $fromstart, $tolimit, $orderBy, $orderDir);

        $result = [];
        $slno = $fromstart + 1;

        foreach ($courseRecords as $c) {
            $result[] = [
                'slno' => $slno++,
                'course_id' => $c->course_id,
                'name' => $c->name,
                'description' => $c->description,
                'duration_weeks' => $c->duration_weeks,
                'status' => $c->status,
            ];
        }

        $totalCount = $this->courseModel->getAllCourseCount();
        $filteredCountObj = $this->courseModel->getFilterCourseCount($condition);
        $filteredCount = $filteredCountObj->filRecords ?? 0;

        return $this->response->setJSON([
            "draw" => intval($draw),
            "recordsTotal" => $totalCount,
            "recordsFiltered" => $filteredCount,
            "data" => $result
        ]);
    }
    public function toggleStatus()
    {
        if ($this->request->isAJAX()) {
            $course_id = $this->request->getPost('course_id');
            $status = (int) $this->request->getPost('status');

            if (!$course_id || !in_array($status, [1, 2])) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Invalid status value'
                ]);
            }

            $updated = $this->courseModel->update($course_id, ['status' => $status]);

            if ($updated) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Status Updated Successfully!']);
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Update Failed'
                ]);
            }
        }
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Invalid request'
        ]);
    }
    public function edit($id)
    {
        $course = $this->courseModel->find($id);

        // $modules = $this->moduleModel->where('course_id', $id)->findAll();

        $data = [
            'course' => $course,
            // 'modules' => $modules
        ];

        $template = view('admin/common/header');
        $template .= view('admin/common/sidemenu');
        $template .= view('admin/add_course', $data);
        $template .= view('admin/common/footer');
        $template .= view('admin/page_scripts/coursejs');

        return $template;
    }


    public function update($id)
    {
        $courseData = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'duration_weeks' => $this->request->getPost('duration_weeks'),
        ];

        $this->courseModel->update($id, $courseData);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Course Updated Successfully.'
        ]);
    }
    public function viewCourseModules($courseId)
    {


        $course = $this->courseModel->find($courseId);

        if (!$course) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Course not found');
        }

        $modules = $this->moduleModel->where('course_id', $courseId)->findAll();



        $videoModel = new CourseVideoModel();


        // Pass data to view
        $data = [
            'course' => $course,
            'modules' => $modules,
            'videoModel' => $videoModel
        ];

        $template = view('admin/common/header');
        $template .= view('admin/common/sidemenu');
        $template .= view('admin/manage_module', $data);
        $template .= view('admin/common/footer');
        $template .= view('admin/page_scripts/modulejs');

        return $template;
    }
    public function delete()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $id = $this->request->getPost('id');

        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Missing course ID']);
        }

        $updated = $this->courseModel->update($id, ['status' => 9]);

        if ($updated) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Course deleted successfully.']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Delete failed.']);
        }
    }

}
