<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    
    public function problem()
    {
        return view('reports.problem');
    }

    public function finance()
    {
        return view('reports.finance');
    }
}
