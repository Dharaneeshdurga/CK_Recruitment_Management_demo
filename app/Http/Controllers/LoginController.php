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
           // print_r(auth()->user());
            if (auth()->user()->role_type == 'leader') {
                return response()->json( ['url'=>url( 'ol_leader_verify' ), 'logstatus' => 'success'] );
            }
            else if(auth()->user()->role_type == 'virtual_audit') {
                return response()->json( ['url'=>url( 'candidate_profile' ), 'logstatus' => 'success'] );
            }
            else if(auth()->user()->role_type == 'finance') {
                return response()->json( ['url'=>url( 'offers_finance' ), 'logstatus' => 'success'] );
            }
            else if(auth()->user()->role_type == 'approver') {
                return response()->json( ['url'=>url( 'ol_leader_verify' ), 'logstatus' => 'success'] );
            }

            else if(auth()->user()->role_type == 'super_admin') {
                return response()->json( ['url'=>url( 'view_recruit_request_default' ), 'logstatus' => 'success'] );
            }
            else if(auth()->user()->role_type == 'backend_coordinator') {
                return response()->json( ['url'=>url( 'view_recruit_request_default' ), 'logstatus' => 'success'] );
            }
            else if(auth()->user()->role_type == 'recruiter') {
                return response()->json( ['url'=>url( 'view_task_detail' ), 'logstatus' => 'success'] );
            }
            else if(auth()->user()->role_type == 'payroll') {
                return response()->json( ['url'=>url( 'ol_payroll_verify' ), 'logstatus' => 'success'] );
            }
            else if(auth()->user()->role_type == 'po_team') {
                return response()->json( ['url'=>url( 'offers_poteam' ), 'logstatus' => 'success'] );
            }
        }else{
            return response()->json( ['url'=>url( '/' ), 'logstatus' => 'failed'] );

        }

    }
}
