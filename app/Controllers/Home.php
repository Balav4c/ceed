<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
      
            // Load views
    $template  = view('common/header');
    $template .= view('buddybloom');
    $template .= view('common/footer');

    return $template;
    }
}
