<?php

namespace App\Http\Controllers;
use App\Repositories\ICoordinatorRepository; 

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function __construct(ICoordinatorRepository $corepo)
    {
        $this->corepo = $corepo;

    }
    public function index()
    {
        return view('login');
    }
    
    public function error(){
        return view('error');
    }
    public function notfound(){
        return view('notfound');
    }
    public function Logout(){
        auth()->logout();
        return redirect()->to( 'index' );
    }

    public function get_band_details(){
        
        $get_band_details_result = $this->corepo->get_band_details(  );

        return $get_band_details_result;
    }

    public function get_position_title_af(){

        $get_position_title_af_result = $this->corepo->get_position_title_af(  );

        return $get_position_title_af_result;
    }

    public function get_location_af(){

        $get_location_af_result = $this->corepo->get_location_af(  );

        return $get_location_af_result;
    }

    public function get_business_af(){

        $get_business_af_result = $this->corepo->get_business_af(  );

        return $get_business_af_result;
    }

    public function get_function_af(){

        $get_function_af_result = $this->corepo->get_function_af(  );

        return $get_function_af_result;
    }

    public function change_password(){
        return view('change_password');

    }

    public function change_password_process(Request $req){
        // get all data
        $session_user_details = auth()->user();
        $empID = $session_user_details->empID;

        $input_details = array(
            'empID'=>$empID,
            'confirm_password'=>bcrypt($req->input('confirm_password')),
        );

        $change_password_process_result = $this->corepo->change_password_process( $input_details );

        $response = 'Updated';
        return response()->json( ['response' => $response] );

    }
}
