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
}
