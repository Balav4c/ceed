<?php
namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Models\admin\CourseModuleModel;
use App\Models\admin\CourseVideoModel;

class CourseModule extends BaseController
{
    protected $moduleModel;
    protected $videoModel;

    public function __construct()
    {
        $this->moduleModel = new CourseModuleModel();
        $this->videoModel = new CourseVideoModel();
    }
    public function index($courseId)
    {
        $template = view('admin/common/header');
        $template .= view('admin/common/sidemenu');
        $template .= view('admin/add_module', ['course_id' => $courseId]);
        $template .= view('admin/common/footer');
        $template .= view('admin/page_scripts/modulejs');
        return $template;
    }
    public function addModule($id = null)
    {
        $data['course'] = $id ? $this->moduleModel->find($id) : null;
        $template = view('admin/common/header');
        $template .= view('admin/common/sidemenu');
        $template .= view('admin/manage_module');
        $template .= view('admin/common/footer');
        $template .= view('admin/page_scripts/modulejs');
        return $template;

    }

    public function save()
{
    $moduleId = $this->request->getPost('module_id');
    $courseId = $this->request->getPost('course_id');
    $moduleNames = $this->request->getPost('module_name');
    $durations = $this->request->getPost('module_duration');
    $descriptions = $this->request->getPost('module_description');
    $uploadedVideos = $this->request->getPost('uploaded_videos');

    foreach ($moduleNames as $index => $name) {
        if (empty(trim($name)) || empty(trim($durations[$index] ?? ''))) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Please Fill All Mandatory Fields.'
            ]);
        }
    }

    foreach ($moduleNames as $index => $name) {
        $plainDescription = isset($descriptions[$index]) ? $descriptions[$index] : null;

        $moduleData = [
            'course_id' => $courseId,
            'module_name' => $name,
            'duration_weeks' => $durations[$index] ?? null,
            'description' => $plainDescription,
            'status' => 1
        ];

        if ($moduleId) {
            $this->moduleModel->update($moduleId, $moduleData);
            $message = 'Module Updated Successfully!';
            $isUpdate = true;
        } else {
            $this->moduleModel->insert($moduleData);
            $moduleId = $this->moduleModel->insertID(); 
            $message = 'Module Saved Successfully!.';
            $isUpdate = false;
        }
    }

    if (!empty($uploadedVideos)) {
        $videoArray = explode(',', $uploadedVideos);
        foreach ($videoArray as $video) {
            $this->videoModel->insert([
                'module_id' => $moduleId,
                'video_file' => trim($video),
                'status' => 1
            ]);
        }
    }

    return $this->response->setJSON([
        'status' => 'success',
        'message' => $message,
        'module_id' => $moduleId,
        'course_id' => $courseId,
        'is_update' => $isUpdate
    ]);
}

    public function uploadVideo()
    {
        $videoFiles = $this->request->getFileMultiple('module_videos');

        $uploadedFiles = [];
        $errors = [];

        $allowedTypes = ['video/mp4', 'video/webm', 'video/ogg'];
        $uploadPath = WRITEPATH . '../public/uploads/videos/';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        foreach ($videoFiles as $file) {
            if ($file && $file->isValid() && !$file->hasMoved()) {
                if (!in_array($file->getClientMimeType(), $allowedTypes)) {
                    $errors[] = $file->getClientName() . " is not valid. Please upload video files only (MP4, WEBM, OGG).";
                    continue;
                }

                $newName = $file->getRandomName();

                if ($file->move($uploadPath, $newName)) {
                    $uploadedFiles[] = $newName;
                } else {
                    $errors[] = $file->getClientName() . "could not be uploaded.";
                }
            }
        }

        return $this->response->setJSON([
            'status' => empty($errors) ? 'success' : 'error',
            'message' => empty($errors) ? 'Videos uploaded successfully!' : 'Please Upload Video Files Only.',
            'uploaded' => $uploadedFiles,
            'errors' => $errors
        ]);
    }
    public function moduleListAjax()
    {
        $request = service('request');
        $draw = $request->getPost('draw') ?? 1;
        $fromstart = $request->getPost('start') ?? 0;
        $tolimit = $request->getPost('length') ?? 10;
        $search = $request->getPost('search')['value'] ?? '';
        $courseId = $request->getPost('course_id');
        $condition = "1=1";

        if (!empty($search)) {
            $search = trim(preg_replace('/\s+/', ' ', $search));
            $noSpaceSearch = str_replace(' ', '', strtolower($search));
            $esc = $this->moduleModel->db->escapeLikeString($noSpaceSearch);

            $condition .= " AND (
            REPLACE(LOWER(m.module_name), ' ', '') LIKE '%{$esc}%'
            OR REPLACE(LOWER(m.description), ' ', '') LIKE '%{$esc}%'
            OR REPLACE(LOWER(m.duration_weeks), ' ', '') LIKE '%{$esc}%'
        )";
        }
        if (!empty($courseId)) {
            $condition .= " AND m.course_id = " . (int) $courseId;
        }
        $columns = [
            'slno',
            'module_name',
            'description',
            'duration_weeks',
            'module_videos',
            'status',
            'module_id'
        ];

        $order = $request->getPost('order');
        $orderColumnIndex = $order[0]['column'] ?? 0;
        $orderDir = $order[0]['dir'] ?? 'desc';
        $orderBy = $columns[$orderColumnIndex] ?? 'module_id';
        $m_id = $request->getPost('module_id');
        if ($orderBy === 'slno' || $orderBy === 'module_videos') {
            $orderBy = 'module_id';
        }

        $moduleRecords = $this->moduleModel
            ->getAllFilteredRecords($condition, $fromstart, $tolimit, $orderBy, $orderDir, $m_id);

        $result = [];
        $slno = $fromstart + 1;

        foreach ($moduleRecords as $m) {
            $result[] = [
                'slno' => $slno++,
                'module_id' => $m->module_id,
                'course_id' => $m->course_id,
                'module_name' => $m->module_name,
                'description' => $m->description,
                'duration_weeks' => $m->duration_weeks,
                'status' => $m->status,
                'module_videos' => $m->module_videos ?? ''
            ];
        }

        $totalCount = $this->moduleModel->getAllModuleCount();
        $filteredCountObj = $this->moduleModel->getFilterModuleCount($condition);
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
            $module_id = $this->request->getPost('module_id');
            $status = (int) $this->request->getPost('status');

            if (!$module_id || !in_array($status, [1, 2])) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Invalid status value'
                ]);
            }

            $updated = $this->moduleModel->update($module_id, ['status' => $status]);

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
    public function editModule($id)
    {
        $module = $this->moduleModel->find($id);

        if (!$module) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Module not found');
        }

        $videoModel = new CourseVideoModel();
        $videos = $videoModel->where('module_id', $id)->where('status', 1)->findAll();
        $videoFiles = array_column($videos, 'video_file');
        $existingVideos = implode(',', $videoFiles);

        $data = [
            'module' => $module,
            'existingVideos' => $existingVideos
        ];
    
        $template = view('admin/common/header');
        $template .= view('admin/common/sidemenu');
        $template .= view('admin/add_module', $data);
        $template .= view('admin/common/footer');
        $template .= view('admin/page_scripts/modulejs');

        return $template;
    }



    public function update($id)
    {
        $moduleData = [
            'module_name' => $this->request->getPost('module_name'),
            'description' => $this->request->getPost('description'),
            'duration_weeks' => $this->request->getPost('duration_weeks'),
        ];

        $this->moduleModel->update($id, $moduleData);
        $videoFiles = $this->request->getPost('module_videos');

        $videoModel = new CourseVideoModel();

        if ($videoFiles) {
            $videoModel->where('module_id', $id)->delete();
            $videos = explode(',', $videoFiles);
            foreach ($videos as $video) {
                $videoModel->insert([
                    'module_id' => $id,
                    'video_file' => $video,
                    'status' => 1,
                ]);
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Module Updated Successfully.'
        ]);
    }
    public function delete()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $id = $this->request->getPost('id');

        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Missing module ID']);
        }

        $updated = $this->moduleModel->update($id, ['status' => 9]);

        if ($updated) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Module deleted successfully.']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Delete failed.']);
        }
    }


}
