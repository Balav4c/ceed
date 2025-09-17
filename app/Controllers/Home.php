<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
      
            // Load views
    $template  = view('common/header');
    $template .= view('buddybloom');
    $template .= view('deepdive');
    $template .= view('program_benefits');
    $template .= view('cherish_voice');
    $template .= view('aboutceed');
    $template .= view('common/footer');

    return $template;
    }
}
