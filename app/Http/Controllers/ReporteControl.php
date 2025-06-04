<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Jobs\ReporteJob;

class ReporteControl extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
         
        $job = new ReporteJob();
        dispatch($job);

    }
}
