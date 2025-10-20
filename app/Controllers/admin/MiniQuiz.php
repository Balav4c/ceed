<?php

namespace App\Controllers\admin;
use App\Controllers\BaseController;
use App\Models\admin\MiniQuizModel;

class MiniQuiz extends BaseController
{
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->input = \Config\Services::request();
        $this->courseModel = new MiniQuizModel();
        // $this->courseModel = new CourseModel();
        // $this->moduleModel = new CourseModuleModel();
         if (!$this->session->has('user_id')) {
            header('Location: ' . base_url('admin'));
            exit();
        }
    }
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

        $options = [
            'A' => $this->request->getPost('option_a'),
            'B' => $this->request->getPost('option_b'),
            'C' => $this->request->getPost('option_c'),
            'D' => $this->request->getPost('option_d'),
        ];

        $data = [
            'course_id' => $this->request->getPost('course_id'),
            'module_id' => $this->request->getPost('module_id'),
            'question_text' => $this->request->getPost('question_text'),
            'options' => json_encode($options),
            'correct_answer' => $this->request->getPost('correct_answer'),
            'status' => '1'
        ];

        // Try inserting and send JSON response
        if ($model->insert($data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Quiz Added Successfully!'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to add quiz. Please try again.'
            ]);
        }
    }

    public function editQuiz($id)
    {
        $model = new MiniQuizModel();
        $quiz = $model->find($id);

        // Decode options JSON for form display
        $quiz['options'] = json_decode($quiz['options'], true);
        $data['quiz'] = $quiz;
        $template = view('admin/common/header');
        $template .= view('admin/common/sidemenu');
        $template .= view('admin/add_miniquiz', $data);
        $template .= view('admin/common/footer');
        $template .= view('admin/page_scripts/coursejs');
        return $template;
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
            'course_id' => $this->request->getPost('course_id'),
            'module_id' => $this->request->getPost('module_id'),
            'question_text' => $this->request->getPost('question_text'),
            'options' => json_encode($options),
            'correct_answer' => $this->request->getPost('correct_answer'),
            'status' => $this->request->getPost('status') ?? 'active'
        ];

        $model->update($id, $data);
        return redirect()->to('admin/mini_quiz')->with('success', 'Quiz updated successfully!');
    }

    public function deleteQuiz()
    {
        $id = $this->request->getPost('id');
        if (!$id) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Invalid quiz ID.'
            ]);
        }

        $quizModel = new MiniQuizModel();
        $updated = $quizModel->update($id, ['status' => 9]);

        if ($updated) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Quiz deleted successfully.'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to delete quiz.'
            ]);
        }
    }

    public function quizListAjax()
    {
        $quizModel = new MiniQuizModel();

        $request = service('request');

        $draw = $request->getPost('draw');
        $start = $request->getPost('start');
        $length = $request->getPost('length');
        $search = $request->getPost('search')['value'] ?? '';

        $totalRecords = $quizModel->getAllQuizCount();
        $filteredRecords = $quizModel->getAllFilteredCount($search);
        $quizList = $quizModel->getAllFilteredRecords($search, $start, $length);

        $data = [];
        $slno = $start + 1;

        foreach ($quizList as $row) {
            $data[] = [
                'slno' => $slno++,
                'course_id' => $row['course_id'],
                'module_id' => $row['module_id'],
                'question_text' => $row['question_text'],
                'correct_answer' => $row['correct_answer'],
                'quiz_id' => $row['quiz_id'],
            ];
        }

        return $this->response->setJSON([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data,
        ]);
    }
}
