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
        // $data['modules'] = $id ? $this->moduleModel->where('course_id', $id)->findAll() : [];
        $template = view('admin/common/header');
        $template .= view('admin/common/sidemenu');
        $template .= view('admin/manage_module');
        $template .= view('admin/common/footer');
        $template .= view('admin/page_scripts/modulejs');
        return $template;

    }
    public function save()
    {
        $courseId = $this->request->getPost('course_id');
        $moduleNames = $this->request->getPost('module_name');
        $durations = $this->request->getPost('module_duration');
        $descriptions = $this->request->getPost('module_description');
        $videoFiles = $this->request->getFileMultiple('module_videos');

        foreach ($moduleNames as $index => $name) {
            if (empty($name)) {
                continue;
            }

            $moduleData = [
                'course_id' => $courseId,
                'module_name' => $name,
                'duration_weeks' => $durations[$index] ?? null,
                'description' => $descriptions[$index] ?? null,
                'status' => 1
            ];

            $this->moduleModel->insert($moduleData);
            $moduleId = $this->moduleModel->insertID();

            if (!empty($videoFiles[$index]) && $videoFiles[$index]->isValid() && !$videoFiles[$index]->hasMoved()) {
                $file = $videoFiles[$index];
                $newName = $file->getRandomName();

                $uploadPath = FCPATH . 'uploads/videos';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                $file->move($uploadPath, $newName);

                $videoData = [
                    'module_id' => $moduleId,
                    'video_file' => $newName
                ];
                $this->videoModel->insert($videoData);
            }
        }

        return redirect()->to(base_url('admin/manage_module'))
            ->with('success', 'Modules & Videos Added Successfully!');
    }
    public function moduleListAjax()
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
            $esc = addslashes($noSpaceSearch);

            $condition .= " AND (
            REPLACE(LOWER(m.module_name), ' ', '') LIKE '%{$esc}%'
            OR REPLACE(LOWER(m.description), ' ', '') LIKE '%{$esc}%'
            OR REPLACE(LOWER(m.duration_weeks), ' ', '') LIKE '%{$esc}%'
        )";
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
        if ($orderBy === 'slno' || $orderBy === 'module_videos') {
            $orderBy = 'module_id';
        }

        $moduleRecords = $this->courseModuleModel
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
                'module_videos' => $m->module_videos ? str_replace(',', '<br>', $m->module_videos) : 'N/A'
            ];
        }

        $totalCount = $this->courseModuleModel->getAllModuleCount();
        $filteredCountObj = $this->courseModuleModel->getFilterModuleCount($condition);
        $filteredCount = $filteredCountObj->filRecords ?? 0;

        return $this->response->setJSON([
            "draw" => intval($draw),
            "recordsTotal" => $totalCount,
            "recordsFiltered" => $filteredCount,
            "data" => $result
        ]);
    }


}
