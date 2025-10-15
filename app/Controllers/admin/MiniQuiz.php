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
            'course_id' => $this->request->getPost('course_id'),
            'module_id' => $this->request->getPost('module_id'),
            'question_text' => $this->request->getPost('question_text'),
            'options' => json_encode($options), // store as JSON
            'correct_answer' => $this->request->getPost('correct_answer'),
            'status' => 'active'
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
        $model = new MiniQuizModel();
        $model->update($id, ['status' => 'delete']);
        return $this->response->setJSON(['status' => 'success']);
    }

   public function quizListAjax()
{
    try {
        $quizModel = new MiniQuizModel();

        $request = service('request');

        $draw = $request->getPost('draw') ?? 1;
        $start = $request->getPost('start') ?? 0;
        $length = $request->getPost('length') ?? 10;
        $search = $request->getPost('search')['value'] ?? '';

        $condition = "1=1";
        if (!empty($search)) {
            $search = trim(preg_replace('/\s+/', ' ', $search));
            $esc = $quizModel->db->escapeLikeString($search);
            $condition .= " AND (
                question_text LIKE '%{$esc}%'
                OR correct_answer LIKE '%{$esc}%'
            )";
        }

        $columns = ['quiz_id', 'course_id', 'module_id', 'question_text', 'correct_answer', 'status'];
        $orderColumnIndex = $request->getPost('order')[0]['column'] ?? 6;
        $orderDir = $request->getPost('order')[0]['dir'] ?? 'desc';
        $orderBy = $columns[$orderColumnIndex] ?? 'quiz_id';

        $quizRecords = $quizModel->getAllFilteredRecords($condition, $start, $length, $orderBy, $orderDir);

        $result = [];
        $slno = $start + 1;
        foreach ($quizRecords as $q) {
            $result[] = [
                'slno' => $slno++,
                'quiz_id' => $q->quiz_id,
                'course_id' => $q->course_id,
                'module_id' => $q->module_id,
                'question_text' => $q->question_text,
                'correct_answer' => $q->correct_answer,
                'status' => $q->status
            ];
        }

        $totalCount = $quizModel->getAllQuizCount();
        $filteredCountObj = $quizModel->getFilterQuizCount($condition);
        $filteredCount = $filteredCountObj->filRecords ?? 0;

        return $this->response->setJSON([
            "draw" => intval($draw),
            "recordsTotal" => $totalCount,
            "recordsFiltered" => $filteredCount,
            "data" => $result
        ]);
    } catch (\Throwable $e) {
        log_message('error', $e->getMessage());
        return $this->response->setStatusCode(500)
            ->setJSON(['error' => $e->getMessage()]);
    }
}

}
