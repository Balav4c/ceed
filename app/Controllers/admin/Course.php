<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Models\admin\CourseModel;
use App\Models\admin\CourseModuleModel;

class Course extends BaseController
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
        $data['modules'] = $id ? $this->moduleModel->where('course_id', $id)->findAll() : [];
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
    $courseData = [
        'name'          => $this->request->getPost('name'),
        'description'   => substr($this->request->getPost('description'), 0, 250),
        'duration_weeks'=> $this->request->getPost('duration_weeks'),
    ];

    if ($id) {
        $this->courseModel->update($id, $courseData);
        $courseId = $id;
    } else {
        $courseData['status'] = 1;
        $this->courseModel->insert($courseData);
        $courseId = $this->courseModel->insertID();
    }
    $this->moduleModel->where('course_id', $courseId)->delete();
    $moduleNames = $this->request->getPost('module_name');
    $durations   = $this->request->getPost('module_duration');

    if (!empty($moduleNames)) {
        foreach ($moduleNames as $index => $mname) {
            if (!empty($mname)) {
                $this->moduleModel->insert([
                    'course_id'      => $courseId,
                    'module_name'    => $mname,
                    'duration_weeks' => $durations[$index] ?? ''
                ]);
            }
        }
    }

    return $this->response->setJSON(['status' => 'success', 'message' => 'Course saved successfully']);
}

public function courseListAjax()
{
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    $request = service('request');

    $draw       = $request->getPost('draw') ?? 1;
    $fromstart  = $request->getPost('start') ?? 0;
    $tolimit    = $request->getPost('length') ?? 10;
    $search     = $request->getPost('search')['value'] ?? '';

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
    $slno   = $fromstart + 1;

    foreach ($courseRecords as $c) {
        $result[] = [
            'slno'           => $slno++,
            'course_id'      => $c->course_id,
            'name'           => $c->name,
            'description'    => $c->description,
            'duration_weeks' => $c->duration_weeks,
            'status'         => $c->status,
        ];
    }

    $totalCount       = $this->courseModel->getAllCourseCount();
    $filteredCountObj = $this->courseModel->getFilterCourseCount($condition);
    $filteredCount    = $filteredCountObj->filRecords ?? 0;

    return $this->response->setJSON([
        "draw"            => intval($draw),
        "recordsTotal"    => $totalCount,
        "recordsFiltered" => $filteredCount,
        "data"            => $result
    ]);
}
 public function toggleStatus()
    {
        if ($this->request->isAJAX()) {
            $course_id = $this->request->getPost('course_id');
           $status = (int)$this->request->getPost('status');

            if (!$course_id || !in_array($status, [1, 2])) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Invalid status value'
                ]);
            }

           $updated = $this->courseModel->update($course_id, ['status' => $status]);

            if ($updated) {
                return $this->response->setJSON(['status' => 'success','message' =>'Status Updated Successfully!']);
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

    $modules = $this->moduleModel->where('course_id', $id)->findAll();

    $data = [
        'course'  => $course,
        'modules' => $modules
    ];

    $template  = view('admin/common/header');
    $template .= view('admin/common/sidemenu');
    $template .= view('admin/add_course', $data);
    $template .= view('admin/common/footer');
    $template .= view('admin/page_scripts/coursejs'); 

    return $template;
}

public function update($id)
{
    $courseData = [
        'name'           => $this->request->getPost('name'),
        'description'    => substr($this->request->getPost('description'), 0, 250),
        'duration_weeks' => $this->request->getPost('duration_weeks'),
    ];

    $this->courseModel->update($id, $courseData);

    $this->moduleModel->where('course_id', $id)->delete();

    $moduleNames = $this->request->getPost('module_name');
    $durations   = $this->request->getPost('module_duration');

    if (!empty($moduleNames)) {
        foreach ($moduleNames as $index => $mname) {
            if (!empty($mname)) {
                $this->moduleModel->insert([
                    'course_id'      => $id,
                    'module_name'    => $mname,
                    'duration_weeks' => $durations[$index] ?? ''
                ]);
            }
        }
    }

    return $this->response->setJSON([
        'status'  => 'success',
        'message' => 'Course Updated Successfully!'
    ]);
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
