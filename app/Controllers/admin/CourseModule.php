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
        $template .= view('admin/add_module',['course_id' => $courseId]);
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
        $courseId     = $this->request->getPost('course_id');
        $moduleNames  = $this->request->getPost('module_name');
        $durations    = $this->request->getPost('module_duration');
        $descriptions = $this->request->getPost('module_description');
        $videoFiles   = $this->request->getFiles();

        foreach ($moduleNames as $index => $name) {
            $moduleData = [
                'course_id'      => $courseId,
                'module_name'    => $name,
                'duration_weeks' => $durations[$index],
                'description'    => $descriptions[$index],
                'status'         => 1 
            ];

            $this->moduleModel->insert($moduleData);
            $moduleId = $this->moduleModel->getInsertID();
            if (isset($videoFiles['module_videos'][$index])) {
                $file = $videoFiles['module_videos'][$index];
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move(FCPATH . 'uploads/videos', $newName);

                    $videoData = [
                        'module_id'  => $moduleId,
                        'video_file' => $newName  
                    ];
                    $this->videoModel->insert($videoData);
                }
            }
        }
        return redirect()->to(base_url('admin/manage_module'))
                        ->with('success', 'Modules & Videos Added Successfully!');
    }
}
