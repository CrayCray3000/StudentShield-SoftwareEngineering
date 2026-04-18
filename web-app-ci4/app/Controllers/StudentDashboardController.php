<?php

namespace App\Controllers;

class StudentDashboardController extends BaseController
{
    public function index()
    {
        // Simple dashboard path selection screen
        return view('student_dashboard');
    }
}
