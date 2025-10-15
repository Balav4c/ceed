<?php

namespace App\Models\admin;
use CodeIgniter\Model;

class MiniQuizModel extends Model
{
    protected $table = 'mini_quizzes';
    protected $primaryKey = 'quiz_id';

    protected $allowedFields = [
        'course_id',
        'module_id',
        'question_text',
        'options',
        'correct_answer',
        'status',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $casts = [
        'options' => 'json'
    ];

    // Total count excluding soft-deleted
    public function getAllQuizCount()
    {
        return $this->db->table($this->table)
            ->where('status !=', 'delete')
            ->countAllResults();
    }

    // Get filtered records with limit and order
    public function getAllFilteredRecords($condition, $start, $length, $orderBy = 'quiz_id', $orderDir = 'desc')
    {
        return $this->db->table($this->table)
            ->where('status !=', 'delete')
            ->where($condition, null, false)
            ->orderBy($orderBy, $orderDir)
            ->limit($length, $start)
            ->get()
            ->getResult();
    }

    // Count of filtered records
    public function getFilterQuizCount($condition)
    {
        return $this->db->table($this->table)
            ->select('COUNT(*) as filRecords')
            ->where('status !=', 'delete')
            ->where($condition, null, false)
            ->get()
            ->getRow();
    }
}
