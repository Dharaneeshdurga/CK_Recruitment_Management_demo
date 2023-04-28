<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    
    public function index()
    {
        return view('home');
    }

    public function login_check_process(Request $req){

        $credentials = [
            'empID' => $req['employee_id'],
            'password' => $req['login_password'],
            'profile_status'=>'Active',
        ];

        if(auth()->attempt($credentials, true))
        {
            if (auth()->user()->role_type == 'leader') {
            return response()->json( ['url'=>url( 'index' ), 'logstatus' => 'success'] );

            }
            else if(auth()->user()->role_type == 'backend_coordinator') {
                return response()->json( ['url'=>url( 'view_recruit_request_default' ), 'logstatus' => 'success'] );
            }
            else if(auth()->user()->role_type == 'recruiter') {
                return response()->json( ['url'=>url( 'view_task_detail' ), 'logstatus' => 'success'] );
            }
        }else{
            return response()->json( ['url'=>url( '../' ), 'logstatus' => 'failed'] );

        }

    }
}
