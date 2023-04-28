<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (auth()->user()->role_type == 'leader') {
            return view('index');
        }
        else if(auth()->user()->role_type == 'backend_coordinator') {
            return view('view_recruit_request_default');
        }
        else if(auth()->user()->role_type == 'recruiter') {
            return view('view_task_detail');
        }

        
    }
}
