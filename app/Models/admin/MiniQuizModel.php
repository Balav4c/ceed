<?php

namespace App\Models\admin;

use CodeIgniter\Model;

class MiniQuizModel extends Model
{
    protected $table = 'mini_quizzes';
    protected $primaryKey = 'quiz_id';
    protected $allowedFields = [
        'course_id', 'module_id', 'question_text', 'options',
        'correct_answer', 'status', 'created_at', 'updated_at'
    ];

    //  Get total count of all quizzes (except deleted)
    public function getAllQuizCount()
    {
        return $this->db->table($this->table)
            ->where('status !=', 'delete')
            ->countAllResults();
    }

    //  Get total count after applying search
    public function getAllFilteredCount($search = '')
    {
        $builder = $this->db->table($this->table)
            ->where('status !=', 'delete');

        if ($search !== '') {
            $builder->groupStart()
                ->like('question_text', $search)
                ->orLike('correct_answer', $search)
                ->orLike('course_id', $search)
                ->orLike('module_id', $search)
                ->groupEnd();
        }

        return $builder->countAllResults();
    }

    //  Get records after search + pagination
    public function getAllFilteredRecords($search = '', $start = 0, $length = 10)
    {
        $builder = $this->db->table($this->table)
            ->where('status !=', 'delete');

        if ($search !== '') {
            $builder->groupStart()
                ->like('question_text', $search)
                ->orLike('correct_answer', $search)
                ->orLike('course_id', $search)
                ->orLike('module_id', $search)
                ->groupEnd();
        }

        return $builder->orderBy('quiz_id', 'DESC')
            ->limit($length, $start)
            ->get()
            ->getResultArray();
    }
}
