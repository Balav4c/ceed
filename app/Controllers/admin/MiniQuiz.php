<?php

namespace App\Controllers\admin;
use App\Controllers\BaseController;
use App\Models\admin\MiniQuizModel;

class MiniQuiz extends BaseController
{
    public function index()
    {
          $template = view('admin/common/header');
        $template .= view('admin/common/sidemenu');
        $template .= view('admin/manage_miniquiz');
        $template .= view('admin/common/footer');
        $template .= view('admin/page_scripts/quizjs');
        return $template;
    }

    public function add()
    {
         $template = view('admin/common/header');
        $template .= view('admin/common/sidemenu');
         $template .= view('admin/add_miniquiz');
        $template .= view('admin/common/footer');
        $template .= view('admin/page_scripts/quizjs');
         return $template;
    }

    public function saveQuiz()
    {
        $model = new MiniQuizModel();

        // Build JSON options array
        $options = [
            'A' => $this->request->getPost('option_a'),
            'B' => $this->request->getPost('option_b'),
            'C' => $this->request->getPost('option_c'),
            'D' => $this->request->getPost('option_d'),
        ];

        $data = [
            'course_id'      => $this->request->getPost('course_id'),
            'module_id'      => $this->request->getPost('module_id'),
            'question_text'  => $this->request->getPost('question_text'),
            'options'        => json_encode($options), // store as JSON
            'correct_answer' => $this->request->getPost('correct_answer'),
            'status'         => 'active'
        ];

        $model->insert($data);
        return redirect()->to('admin/mini_quiz')->with('success', 'Quiz added successfully!');
    }

    public function editQuiz($id)
    {
        $model = new MiniQuizModel();
        $quiz = $model->find($id);

        // Decode options JSON for form display
        $quiz['options'] = json_decode($quiz['options'], true);
        $data['quiz'] = $quiz;

        return view('admin/add_miniquiz', $data);
    }

    public function updateQuiz($id)
    {
        $model = new MiniQuizModel();

        $options = [
            'A' => $this->request->getPost('option_a'),
            'B' => $this->request->getPost('option_b'),
            'C' => $this->request->getPost('option_c'),
            'D' => $this->request->getPost('option_d'),
        ];

        $data = [
            'course_id'      => $this->request->getPost('course_id'),
            'module_id'      => $this->request->getPost('module_id'),
            'question_text'  => $this->request->getPost('question_text'),
            'options'        => json_encode($options),
            'correct_answer' => $this->request->getPost('correct_answer'),
            'status'         => $this->request->getPost('status') ?? 'active'
        ];

        $model->update($id, $data);
        return redirect()->to('admin/mini_quiz')->with('success', 'Quiz updated successfully!');
    }

    public function deleteQuiz()
    {
        $id = $this->request->getPost('id');
        $model = new MiniQuizModel();
        $model->update($id, ['status' => 'delete']);
        return $this->response->setJSON(['status' => 'success']);
    }

    public function QuizlistAjax()
    {
        $model = new MiniQuizModel();
        $data['data'] = $model->where('status !=', 'delete')->findAll();
        return $this->response->setJSON($data);
    }
}
