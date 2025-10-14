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
        $about = $this->request->getPost('module_about');
        $moduleLevels = $this->request->getPost('module_level');

        // Validation check
        foreach ($moduleNames as $index => $name) {
            if (
                empty(trim($name)) ||
                empty(trim($durations[$index] ?? '')) ||
                empty(trim($about[$index] ?? '')) ||
                empty(trim($moduleLevels[$index] ?? ''))
            ) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Please fill all mandatory fields.'
                ]);
            }
        }

        // Loop through modules
        foreach ($moduleNames as $index => $name) {
            $moduleData = [
                'course_id' => $courseId,
                'module_name' => $name,
                'duration_weeks' => $durations[$index] ?? null,
                'description' => $descriptions[$index] ?? null,
                'about' => $about[$index] ?? null,
                'module_level' => $moduleLevels[$index] ?? 'Beginner',
                'status' => 1
            ];

            if ($moduleId) {
                $this->moduleModel->update($moduleId, $moduleData);
                $message = 'Module updated successfully!';
                $isUpdate = true;
            } else {
                $this->moduleModel->insert($moduleData);
                $moduleId = $this->moduleModel->insertID();
                $message = 'Module saved successfully! Now add lessons.';
                $isUpdate = false;
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
        $orderColumnIndex = $order[0]['column'] ?? 6;
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
            ->findColumn('videos');

        $data = [
            'module' => $module,
            'existingVideos' => $videoFiles ? implode(',', $videoFiles) : '',
            'isEdit' => true
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
            'about' => $this->request->getPost('about'),
            'duration_weeks' => $this->request->getPost('duration_weeks'),
        ];

        $this->moduleModel->update($id, $moduleData);

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
    public function deleteVideo()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid request'
            ]);
        }

        $videoFile = $this->request->getPost('video_file');
        if (!$videoFile) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Video file missing'
            ]);
        }

        $lessonModel = new CourseLessonModel();
        $videoFound = false;

        $lessons = $lessonModel->findAll();

        foreach ($lessons as $lesson) {
            $videos = json_decode($lesson['videos'], true);

            if (is_array($videos) && in_array($videoFile, $videos)) {
                $videos = array_values(array_diff($videos, [$videoFile]));

                $lessonModel->update($lesson['lesson_id'], [
                    'videos' => json_encode($videos),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                $videoFound = true;
                break;
            }
        }
        $filePath = FCPATH . 'public/uploads/videos/' . $videoFile;
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        if ($videoFound) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Video deleted successfully'
            ]);
        } elseif (file_exists($filePath) === false) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Temporary video deleted successfully'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Video not found in database'
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
        helper('text'); 

        try {
            $lessonModel = new CourseLessonModel();

            $lessonId = $this->request->getPost('lesson_id');
            $moduleId = $this->request->getPost('module_id');
            $lessonTitle = $this->request->getPost('lesson_title');
            $lessonNames = $this->request->getPost('lesson_name'); 
            $uploadedVideos = $this->request->getPost('uploaded_videos');

            if (empty($uploadedVideos)) {
                $videosJson = '[]';
            } else {
                $decoded = json_decode($uploadedVideos, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    if (strpos($uploadedVideos, ',') !== false) {
                        $videosArray = explode(',', $uploadedVideos);
                        $videosWithNames = [];
                        foreach ($videosArray as $index => $link) {
                            $videosWithNames[] = [
                                'name' => isset($lessonNames[$index]) ? $lessonNames[$index] : '',
                                'link' => trim($link)
                            ];
                        }
                        $videosJson = json_encode($videosWithNames);
                    } else {
                      
                        $videosJson = json_encode([['name' => $lessonNames[0] ?? '', 'link' => $uploadedVideos]]);
                    }
                } else {
                    if (is_array($decoded)) {
                        foreach ($decoded as $index => &$video) {
                            $video['name'] = $lessonNames[$index] ?? ($video['name'] ?? '');
                            $video['link'] = $video['link'] ?? '';
                        }
                        $videosJson = json_encode($decoded);
                    } else {
                        $videosJson = json_encode([['name' => $lessonNames[0] ?? '', 'link' => '']]);
                    }
                }
            }


            $data = [
                'lesson_title' => $this->request->getPost('lesson_title'),
                'module_id' => $moduleId,
                'videos' => $videosJson,
                'status'       => 1,
            ];


            if ($lessonId) {
                $updated = $lessonModel->update($lessonId, $data);
                if (!$updated) {
                    throw new \Exception('Failed to update lesson.');
                }
                $msg = 'Lesson updated successfully.';
            } else {
                $insertId = $lessonModel->insert($data);
                if (!$insertId) {
                    throw new \Exception('Failed to create lesson.');
                }
                $msg = 'Lesson created successfully.';
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => $msg,
                'module_id' => $moduleId,
            ]);

        } catch (\Exception $ex) {
            log_message('error', 'saveLesson error: ' . $ex->getMessage());

            return $this->response->setJSON([
                'status' => 'error',
                'message' => $ex->getMessage(),
            ]);
        }
    }

    public function editLesson($lessonId = null)
    {
        $moduleModel = new CourseModuleModel();
        $lessonModel = new CourseLessonModel();

        if (!$lessonId) {
            return redirect()->to(base_url('admin/manage_lesson'));
        }

        $lesson = $lessonModel->where('lesson_id', $lessonId)
            ->where('status !=', 9)
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
  public function updateLesson()
{
    $lessonModel = new CourseLessonModel();

    $lessonId = $this->request->getPost('lesson_id');
    if (!$lessonId) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Lesson ID missing'
        ]);
    }

    $moduleId     = $this->request->getPost('module_id');
    $courseId     = $this->request->getPost('course_id');
    $lessonTitle  = $this->request->getPost('lesson_title');

    if (empty($lessonTitle) || empty($moduleId)) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Please fill all required fields.'
        ]);
    }
    $existingVideos = json_decode($this->request->getPost('existing_videos'), true) ?? [];
    $deletedVideos  = json_decode($this->request->getPost('deleted_videos'), true) ?? [];
    $filteredVideos = array_filter($existingVideos, function($video) use ($deletedVideos) {
        return !in_array($video['link'], $deletedVideos);
    });

    $uploadedVideosStr = $this->request->getPost('uploaded_videos');
    $newVideos = array_filter(array_map('trim', explode(',', $uploadedVideosStr)));

    $lessonNames = $this->request->getPost('lesson_name') ?? [];

    $finalVideos = [];

    foreach ($filteredVideos as $video) {
        $finalVideos[] = [
            'name' => $video['name'],
            'link' => $video['link']
        ];
    }

    $existingCount = count($filteredVideos);
    foreach ($newVideos as $i => $videoLink) {
        $nameIndex = $existingCount + $i;
        $videoName = $lessonNames[$nameIndex] ?? '';
        $finalVideos[] = [
            'name' => $videoName,
            'link' => $videoLink
        ];
    }

    foreach ($finalVideos as $v) {
        if (empty($v['link'])) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Each video must have a valid link.'
            ]);
        }
    }

    if (empty($finalVideos)) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'At least one video is required.'
        ]);
    }

    $lessonData = [
        'module_id'    => $moduleId,
        'course_id'    => $courseId,
        'lesson_title' => $lessonTitle,
        'videos'       => json_encode($finalVideos),
        'updated_at'   => date('Y-m-d H:i:s'),
        'status'       => 1
    ];

    $updated = $lessonModel->update($lessonId, $lessonData);

    if ($updated) {
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Lesson updated successfully'
        ]);
    } else {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to update lesson'
        ]);
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

}
