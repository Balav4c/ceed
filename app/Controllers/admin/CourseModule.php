<?php
namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Models\admin\CourseModuleModel;

class CourseModule extends BaseController
{
    protected $moduleModel;

    public function __construct()
    {
        $this->moduleModel = new CourseModuleModel();
    }
    public function index()
    {
         $template = view('admin/common/header');
        $template .= view('admin/common/sidemenu');
        $template .= view('admin/add_module');
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
    $moduleNames = $this->request->getPost('module_name');
    $durations   = $this->request->getPost('module_duration');
    $descriptions= $this->request->getPost('module_description');
    $videoFiles  = $this->request->getFiles(); // get multiple files

    foreach($moduleNames as $index => $name){
        $data = [
            'module_name'    => $name,
            'duration_weeks' => $durations[$index],
            'description'    => $descriptions[$index],
        ];

        // Handle file upload for each module
        if(isset($videoFiles['module_videos'][$index])){
            $file = $videoFiles['module_videos'][$index];
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move(FCPATH . 'uploads/videos', $newName);
                $data['module_videos'] = $newName;
            }
        }

        $this->moduleModel->insert($data);
    }

    return redirect()->to(base_url('admin/manage_module'))
                     ->with('success', 'Modules added successfully!');
}

}
