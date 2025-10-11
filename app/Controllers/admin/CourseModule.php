<?php
namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Models\admin\CourseModuleModel;
use App\Models\admin\CourseLessonModel;

class CourseModule extends BaseController
{
    protected $moduleModel;
    protected $lessonModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->input = \Config\Services::request();
        $this->moduleModel = new CourseModuleModel();
        $this->lessonModel = new CourseLessonModel();
        if (!$this->session->has('user_id')) {
            header('Location: ' . base_url('admin'));
            exit();
        }
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
    public function add_lesson($moduleId, $courseId = null)
    {
        $template = view('admin/common/header');
        $template .= view('admin/common/sidemenu');
        $template .= view('admin/add_lesson', [
            'module_id' => $moduleId,
            'course_id' => $courseId
        ]);
        $template .= view('admin/common/footer');
        $template .= view('admin/page_scripts/lessonjs');
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
                $message = 'Module Saved Successfully! Now Add Lessons';
                $isUpdate = false;
            }
        }

        if (!empty($uploadedVideos)) {
            $videoArray = explode(',', $uploadedVideos);

            foreach ($videoArray as $video) {
                if (!empty(trim($video))) {
                    $this->lessonModel->insert([
                        'module_id' => $moduleId,
                        'video_file' => trim($video),
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
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

        $columns = ['slno', 'module_name', 'description', 'duration_weeks', 'status', 'module_id'];

        $order = $request->getPost('order') ?? [];
        $orderColumnIndex = $order[0]['column'] ?? 0;
        $orderDir = $order[0]['dir'] ?? 'desc';
        $orderBy = $columns[$orderColumnIndex] ?? 'module_id';

        $moduleRecords = $this->moduleModel
            ->getAllFilteredRecords($condition, $fromstart, $tolimit, $orderBy, $orderDir);

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
            ];
        }

        $totalCount = $this->moduleModel->getAllModuleCount();
        $filteredCountObj = $this->moduleModel->getFilterModuleCount($condition);
        $filteredCount = $filteredCountObj ? $filteredCountObj->filRecords : 0;

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

        $lessonModel = new CourseLessonModel();
        $videoFiles = $lessonModel
            ->where('module_id', $id)
            ->where('status', 1)
            ->findColumn('video_file');

        $data = [
            'module' => $module,
            'existingVideos' => $videoFiles ? implode(',', $videoFiles) : ''
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

        $lessonModel = new CourseLessonModel();

        // Deleted videos list
        $deletedVideos = $this->request->getPost('deleted_videos');
        if (!empty($deletedVideos)) {
            $deletedList = explode(',', $deletedVideos);
            foreach ($deletedList as $video) {
                if (!empty($video)) {
                    $this->lessonModel
                        ->where('module_id', $id)
                        ->where('video_file', trim($video))
                        ->set(['status' => 9, 'updated_at' => date('Y-m-d H:i:s')])
                        ->update();
                }
            }
        }

        // New uploaded videos
        $uploadedVideos = $this->request->getPost('uploaded_videos') ?? '';
        if (!empty($uploadedVideos)) {
            $videos = explode(',', $uploadedVideos);
            foreach ($videos as $video) {
                if (!empty(trim($video))) {
                    $lessonModel->insert([
                        'module_id' => $id,
                        'video_file' => trim($video),
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Module Updated Successfully.'
        ]);
    }
    public function editLesson($lessonId = null)
    {
        $moduleModel = new CourseModuleModel();
        $lessonModel = new CourseLessonModel();

        if (!$lessonId) {
            return redirect()->to(base_url('admin/manage_lesson'));
        }

        // Fetch lesson data
        $lesson = $lessonModel->where('lesson_id', $lessonId)
            ->where('status !=', 9) // exclude deleted
            ->first();

        if (!$lesson) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Lesson not found');
        }

        // Fetch module details
        $module = $moduleModel->find($lesson['module_id']);

        $existingVideos = $lesson['videos']; 

        $data = [
            'module' => $module,
            'lesson' => $lesson,
            'module_id' => $lesson['module_id'],
            'course_id' => $lesson['course_id'] ?? '',
            'existingVideos' => $existingVideos,
            'is_edit' => true
        ];

        return view('admin/common/header')
            . view('admin/common/sidemenu')
            . view('admin/add_lesson', $data)
            . view('admin/common/footer')
            . view('admin/page_scripts/lessonjs');
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
    public function deleteVideo()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }
        $videoFile = $this->request->getPost('video_file');
        if (!$videoFile) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Video file missing']);
        }
        $lessonModel = new CourseLessonModel();
        $updated = $lessonModel->where('video_file', $videoFile)->set(['status' => 9, 'updated_at' => date('Y-m-d H:i:s')])->update();
        if ($updated) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Video deleted successfully']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to delete video']);
        }
    }
    public function deleteLesson()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid request'
            ]);
        }

        $lessonId = $this->request->getPost('lesson_id');
        if (!$lessonId) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Lesson ID missing'
            ]);
        }

        $lesssonModel = new CourseLessonModel();
        $updated = $lesssonModel
            ->where('lesson_id', $lessonId)
            ->set(['status' => 9, 'updated_at' => date('Y-m-d H:i:s')])
            ->update();

        if ($updated) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Lesson deleted successfully'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to delete lesson'
            ]);
        }
    }

    public function viewModuleLessons($moduleId)
    {
        
        // Fetch module
        $module = $this->moduleModel->find($moduleId);
        if (!$module) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Module not found');
        }

        $lessons = [];

        try {
            $lessons = $this->lessonModel
                ->where('module_id', $moduleId)
                ->where('status !=', 9)
                ->orderBy('lesson_id', 'ASC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error fetching lessons for module ' . $moduleId . ': ' . $e->getMessage());
        }

        $data = [
            'module' => $module,
            'lessons' => $lessons
        ];
        return view('admin/common/header')
            . view('admin/common/sidemenu')
            . view('admin/manage_lesson', $data)
            . view('admin/common/footer')
            . view('admin/page_scripts/lessonjs');
    }

    public function saveLesson()
    {
        $moduleId = $this->request->getPost('module_id');
        $courseId = $this->request->getPost('course_id');
        $lessonTitle = $this->request->getPost('lesson_title');
        $lessonNames = $this->request->getPost('lesson_name');
        $uploadedVideos = $this->request->getPost('uploaded_videos');

        $courseLessonModel = new CourseLessonModel();

        if (empty($lessonTitle) || empty($uploadedVideos) || empty($lessonNames)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Please fill all required fields.'
            ]);
        }

        $videos = explode(',', $uploadedVideos);
        $videos = array_map('trim', $videos);
        $videos = array_filter($videos);

        if (count($videos) !== count($lessonNames)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Lesson name count does not match video count.'
            ]);
        }

        // Build the videos array
        $videosArray = [];
        foreach ($videos as $index => $video) {
            $videosArray[] = [
                'name' => $lessonNames[$index],
                'link' => $video
            ];
        }

        $data = [
            // 'course_id' => $courseId,
            'module_id' => $moduleId,
            'lesson_title' => $lessonTitle,
            'videos' => json_encode($videosArray), // store as JSON
            'status' => 1,
        ];

        $courseLessonModel->insert($data);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Lesson saved successfully!'
        ]);
    }

}
