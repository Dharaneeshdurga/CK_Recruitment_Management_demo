<?php

namespace App\Repositories;

use App\Models\recruitmentRequest;
use App\Models\User;

use DB;
class CoordinatorRepository implements ICoordinatorRepository
{  
    public function reqcruitment_requestEntry( $form_credentials ){
        $reqtbl = new recruitmentRequest();
        $reqtbl->recReqID = 'RR'.str_pad( ( $reqtbl->max( 'id' )+1 ), 9, '0', STR_PAD_LEFT );
        $reqtbl->rfh_no = $form_credentials['rfh_no'];
        $reqtbl->position_title = $form_credentials['position_title'];
        $reqtbl->no_of_position = $form_credentials['no_of_position'];
        $reqtbl->band = $form_credentials['band'];
        $reqtbl->open_date = $form_credentials['open_date'];
        $reqtbl->critical_position = $form_credentials['critical_position' ];
        $reqtbl->business = $form_credentials['business' ];
        $reqtbl->division = $form_credentials['division' ];
        $reqtbl->function = $form_credentials['function' ];
        $reqtbl->location = $form_credentials['location' ];
        $reqtbl->billing_status = $form_credentials['billing_status' ];
        $reqtbl->interviewer = $form_credentials['interviewer' ];
        $reqtbl->salary_range = $form_credentials['salary_range' ];
        $reqtbl->close_date = $form_credentials['close_date' ];
        $reqtbl->request_status = $form_credentials['request_status' ];
        $reqtbl->assigned_status = $form_credentials['assigned_status' ];
        $reqtbl->assigned_to = $form_credentials['assigned_to' ];
        $reqtbl->assigned_date = $form_credentials['assigned_date' ];
        $reqtbl->hepl_recruitment_ref_number = $form_credentials['hepl_recruitment_ref_number' ];
        
        $reqtbl->created_by = $form_credentials['created_by' ];
        $reqtbl->modified_by = $form_credentials['modified_by' ];
        $reqtbl->save();

        if($reqtbl) {
            return true;
        } else {
            return false;
        }
    }
    
    public function get_reqcruitment_request_default($where_cond){

        
        $mdlrecruitmenttbl = DB::table('recruitment_requests as rr')
        ->leftJoin('users as u', 'rr.assigned_to', '=', 'u.empID')
        ->leftJoin('tat_details_tbls as tdt', 'rr.band', '=', 'tdt.id')
        ->select('rr.*', 'u.name as recruiter_name','tdt.no_of_days as tat_days')
        ->where('rr.assigned_status', '=', $where_cond)
        ->orderBy('rr.id', 'desc')
        ->groupBy('rfh_no')
       ->get();

        return $mdlrecruitmenttbl;
    }
    public function get_reqcruitment_request_default_report(){
        $mdlrecruitmenttbl = DB::table('recruitment_requests as rr')
        ->leftJoin('users as u', 'rr.assigned_to', '=', 'u.empID')
        ->leftJoin('tat_details_tbls as tdt', 'rr.band', '=', 'tdt.id')
        ->select('rr.*', 'u.name as recruiter_name','tdt.no_of_days as tat_days')
        ->orderBy('rr.id', 'desc')
        ->groupBy('hepl_recruitment_ref_number')
       ->get();

        return $mdlrecruitmenttbl;
    }
    public function get_recruitment_edit_details($input_details){
        $mdlrecruitmenttbl = DB::table('recruitment_requests as rr')
        ->select('rr.*')
        ->where('rr.rfh_no', '=', $input_details['rfh_no'])
        ->orderBy('rr.created_at', 'desc')
        ->limit(1)
        ->get();

        return $mdlrecruitmenttbl;
    }

    public function get_tblrfh_details($input_details){
        $mdlrecruitmenttbl = DB::table('tbl_rfh as tr')
        ->select('tr.*')
        ->where('tr.res_id', '=', $input_details['rfh_no'])
        ->orderBy('tr.id', 'desc')
        ->limit(1)
        ->get();

        return $mdlrecruitmenttbl;
    }

    public function get_reqcruitment_request_afilter( $advanced_filter ){

        $mdlrecreqtbl = DB::table('recruitment_requests as rr');

        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'users as u', 'rr.assigned_to', '=', 'u.empID' );
        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'tat_details_tbls as tdt', 'rr.band', '=', 'tdt.id' );
        $mdlrecreqtbl = $mdlrecreqtbl->select( 'rr.*', 'u.name as recruiter_name','tdt.no_of_days as tat_days' );

        if($advanced_filter['af_from_date'] !=''  && $advanced_filter['af_to_date'] !=''){

            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.open_date', '>=', $advanced_filter['af_from_date'] );
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.open_date', '<=', $advanced_filter['af_to_date']);
        }
        
        if($advanced_filter['af_from_date'] !=''  && $advanced_filter['af_to_date'] ==''){

            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.open_date', '=', $advanced_filter['af_from_date'] );
        }
        if($advanced_filter['af_from_date'] ==''  && $advanced_filter['af_to_date'] !=''){

            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.open_date', '=', $advanced_filter['af_to_date'] );

        }

        if ($advanced_filter['af_position_title'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.position_title',  $advanced_filter['af_position_title']);
        }
        
        if ($advanced_filter['af_critical_position'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.critical_position',  $advanced_filter['af_critical_position']);
        }
        if ($advanced_filter['af_position_status'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.request_status',  $advanced_filter['af_position_status']);
        }
        if ($advanced_filter['af_assigned_status'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.assigned_status',  $advanced_filter['af_assigned_status']);
        }
        if ($advanced_filter['af_salary_range'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.salary_range',  $advanced_filter['af_salary_range']);
        }
        if ($advanced_filter['af_band'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.band',  $advanced_filter['af_band']);
        }
        if ($advanced_filter['af_location'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.location',  $advanced_filter['af_location']);
        }
        if ($advanced_filter['af_business'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.business',  $advanced_filter['af_business']);
        }
        if ($advanced_filter['af_billing_status'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.billing_status',  $advanced_filter['af_billing_status']);
        }
        if ($advanced_filter['af_function'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.function',  $advanced_filter['af_function']);
        }
        if ($advanced_filter['af_billable'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.billing_status',  $advanced_filter['af_billable']);
        }
        return $mdlrecreqtbl
        ->orderBy( 'rr.id', 'desc' )->groupBy('rfh_no');
    }

    public function get_reqcruitment_request( $rfh_no){
        $mdlrecruitmenttbl = DB::table('recruitment_requests as rr')
        ->leftJoin('users as u', 'rr.assigned_to', '=', 'u.empID')
        // ->leftJoin('subcategory_tbls as sct', 'pt.subcategory_id', '=', 'sct.subcategory_id')
        // ->leftJoin( 'subcategorytwo_tbls as sctt', 'pt.subcategorytwo_id', '=', 'sctt.subcategorytwo_id' )
        ->select('rr.*', 'u.name as recruiter_name')
        ->where('rr.rfh_no', '=', $rfh_no)
        ->orderBy('rr.id', 'desc')
        // ->groupBy('rfh_no')
       ->get();

        return $mdlrecruitmenttbl;
    }

    public function get_recruiter_list(){
        $mdlrecruitmenttbl = DB::table('users as u')
        ->select('u.*')
        ->where('u.role_type', '=', 'recruiter')
        ->orderBy('u.id', 'desc')
        ->get();

        return $mdlrecruitmenttbl;
    }

    public function process_recruitment_assign( $credentials ){

        $update_mdlrecruitreqtbl = new recruitmentRequest();
        $update_mdlrecruitreqtbl = $update_mdlrecruitreqtbl->where( 'recReqID', '=', $credentials['recReqID'] );
    
        $update_mdlrecruitreqtbl->update( [ 
            'assigned_to' => $credentials['assigned_to'],
            'assigned_date' => $credentials['assigned_date'],
            'hepl_recruitment_ref_number'=> $credentials['hepl_recruitment_ref_number'], 
            'assigned_status'=> $credentials['assigned_status']
            
        ] );
    }
    
    public function get_last_hepl_reference_no(){
        $mdlrecruitmenttbl = DB::table('recruitment_requests as rr')
        ->select('rr.hepl_recruitment_ref_number')
        ->where('rr.hepl_recruitment_ref_number', '!=', '')
        ->orderBy('rr.hepl_recruitment_ref_number', 'desc')
        ->limit(1)
        ->get();

        return $mdlrecruitmenttbl;
    }

    public function getlast_rfhno(){
        $mdlrecruitmenttbl = DB::table('tbl_rfh as tr')
        ->select('tr.res_id')
        ->where('tr.res_id', '!=', '')
        ->orderBy('tr.res_id', 'desc')
        ->limit(1)
        ->get();

        return $mdlrecruitmenttbl;
    }

    public function get_recruitment_for_duplicate( $input_details ){
        $mdlrecruitmenttbl = DB::table('recruitment_requests as rr')
        ->select('rr.*')
        ->where('rr.recReqID', '=', $input_details['recReqID'])
        ->orderBy('rr.created_at', 'desc')
        ->limit(1)
        ->get();

        return $mdlrecruitmenttbl;
    }

    public function get_candidate_profile_all( $input_details ){
        $mdlrecruitmenttbl = DB::table('candidate_details as cd')
        ->join('recruitment_requests as rr', 'cd.rfh_no', '=', 'rr.rfh_no', 'left outer')
        ->leftjoin('users as u', 'cd.created_by', '=', 'u.empID')

        ->select('cd.*','rr.position_title','u.name as created_name')
        ->where('cd.status', '!=', $input_details['status'])
        ->orderBy('cd.created_at', 'desc')
        ->groupBy('cd.cdID')
        ->get();

        return $mdlrecruitmenttbl;
    }

    public function get_candidate_profile_all_af($advanced_filter){

        $mdlrecreqtbl = DB::table('candidate_details as cd');

        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'recruitment_requests as rr', 'cd.rfh_no', '=', 'rr.rfh_no' );
        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'users as u', 'cd.created_by', '=', 'u.empID' );
        $mdlrecreqtbl = $mdlrecreqtbl->select( 'cd.*','rr.position_title','u.name as created_name' );

        if($advanced_filter['af_from_date'] !=''  && $advanced_filter['af_to_date'] !=''){

            $mdlrecreqtbl = $mdlrecreqtbl->where( 'cd.created_on', '>=', $advanced_filter['af_from_date'] );
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'cd.created_on', '<=', $advanced_filter['af_to_date']);
        }
        
        if($advanced_filter['af_from_date'] !=''  && $advanced_filter['af_to_date'] ==''){

            $mdlrecreqtbl = $mdlrecreqtbl->where( 'cd.created_on', '=', $advanced_filter['af_from_date'] );
        }
        if($advanced_filter['af_from_date'] ==''  && $advanced_filter['af_to_date'] !=''){

            $mdlrecreqtbl = $mdlrecreqtbl->where( 'cd.created_on', '=', $advanced_filter['af_to_date'] );

        }

        if ($advanced_filter['af_position_title'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.position_title',  $advanced_filter['af_position_title']);
        }
        if ($advanced_filter['af_position_status'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'cd.status',  $advanced_filter['af_position_status']);
        }
        if ($advanced_filter['af_created_by'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'cd.created_by',  $advanced_filter['af_created_by']);
        }

        $mdlrecreqtbl = $mdlrecreqtbl->where( 'cd.status', '!=', $advanced_filter['status']);
       
        return $mdlrecreqtbl
        ->orderBy( 'cd.created_at', 'desc' )->groupBy('cd.cdID')->get();
    }

    public function process_ticket_edit( $credentials ){

        $update_mdlrecruitreqtbl = new recruitmentRequest();
        $update_mdlrecruitreqtbl = $update_mdlrecruitreqtbl->where( 'rfh_no', '=', $credentials['rfh_no'] );
    
        $update_mdlrecruitreqtbl->update( [ 
            'request_status' => $credentials['request_status']
        ] );
    }

    public function process_ticket_delete( $input_details ){

        DB::table('tbl_rfh')->where('res_id', $input_details['res_id'])->delete();

        DB::table('recruitment_requests')->where('rfh_no', $input_details['res_id'])->delete();

    }

    public function process_recruiter_delete( $input_details ){

        DB::table('users')->where('empID', $input_details['empID'])->delete();


    }
    public function get_ticket_candidate_details( $input_details ){
        $mdlrecruitmenttbl = DB::table('candidate_details as cd')
        ->join('recruitment_requests as rr', 'cd.rfh_no', '=', 'rr.rfh_no', 'left outer')
        ->leftjoin('users as u', 'cd.created_by', '=', 'u.empID')

        ->select('cd.*','rr.position_title','u.name')
        ->where('cd.hepl_recruitment_ref_number', '=', $input_details['hepl_recruitment_ref_number'])
        ->orderBy('cd.created_at', 'desc')
        ->groupBy('cd.cdID')
        ->get();

        

        return $mdlrecruitmenttbl;
    }
    public function get_ticket_candidate_details_af($advanced_filter ){
        $mdlrecreqtbl = DB::table('candidate_details as cd');

        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'recruitment_requests as rr', 'cd.rfh_no', '=', 'rr.rfh_no' );
        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'users as u', 'cd.created_by', '=', 'u.empID' );
        
        $mdlrecreqtbl = $mdlrecreqtbl->select( 'cd.*','rr.position_title','u.name' );

        if($advanced_filter['af_from_date'] !=''  && $advanced_filter['af_to_date'] !=''){

            $mdlrecreqtbl = $mdlrecreqtbl->where( 'cd.created_on', '>=', $advanced_filter['af_from_date'] );
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'cd.created_on', '<=', $advanced_filter['af_to_date']);
        }
        
        if($advanced_filter['af_from_date'] !=''  && $advanced_filter['af_to_date'] ==''){

            $mdlrecreqtbl = $mdlrecreqtbl->where( 'cd.created_on', '=', $advanced_filter['af_from_date'] );
        }
        if($advanced_filter['af_from_date'] ==''  && $advanced_filter['af_to_date'] !=''){

            $mdlrecreqtbl = $mdlrecreqtbl->where( 'cd.created_on', '=', $advanced_filter['af_to_date'] );

        }

        if ($advanced_filter['af_position_status'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'cd.status',  $advanced_filter['af_position_status']);
        }
        if ($advanced_filter['af_created_by'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'cd.created_by',  $advanced_filter['af_created_by']);
        }
        
        
        $mdlrecreqtbl = $mdlrecreqtbl->where('cd.hepl_recruitment_ref_number', '=', $advanced_filter['hepl_recruitment_ref_number']);

        return $mdlrecreqtbl
        ->orderBy( 'cd.created_at', 'desc' )->groupBy('cd.cdID')->get();
    }

    public function get_band_details(  ){
        $mdltat_detailstbl = DB::table('tat_details_tbls as td')
        ->select('td.*')
        ->orderBy('td.created_at', 'asc')
        ->get();

        return $mdltat_detailstbl;
    }

    public function get_position_title_af(){
        $get_position_title_details = DB::table('recruitment_requests as rr')
        ->select('rr.position_title')
        ->orderBy('rr.created_at', 'asc')
        ->groupBy('rr.position_title')
        ->get();

        return $get_position_title_details;
    }

    public function get_location_af(){

        $get_location_af_details = DB::table('recruitment_requests as rr')
        ->select('rr.location')
        ->orderBy('rr.created_at', 'asc')
        ->groupBy('rr.location')
        ->get();

        return $get_location_af_details;
    }

    public function get_business_af(){

        $get_business_af_details = DB::table('recruitment_requests as rr')
        ->select('rr.business')
        ->orderBy('rr.created_at', 'asc')
        ->groupBy('rr.business')
        ->get();

        return $get_business_af_details;
    }

    public function get_function_af(){

        $get_function_af_details = DB::table('recruitment_requests as rr')
        ->select('rr.function')
        ->orderBy('rr.created_at', 'asc')
        ->groupBy('rr.function')
        ->get();

        return $get_function_af_details;
    }

    public function get_user_row($empID) 
    {
        $querytbl = new User(); 
        $querytbl = $querytbl->where( 'empID','=', $empID );
        return $querytbl = $querytbl->get();
    }
    
    public function get_recruiter_list_af(){
        $get_recruiter_list_af_details = DB::table('users as u')
        ->select('u.empID','u.name')
        ->where('u.role_type', '=', 'recruiter')
        ->orderBy('u.created_at', 'asc')
        ->get();

        return $get_recruiter_list_af_details;
    }

    
    public function reqcruitment_request_editprocess( $form_credentials ){
        $update_mdlrecruitreqtbl = new recruitmentRequest();
        $update_mdlrecruitreqtbl = $update_mdlrecruitreqtbl->where( 'rfh_no', '=', $form_credentials['rfh_no'] );
    
        $update_mdlrecruitreqtbl->update( [ 
            'position_title' => $form_credentials['position_title'],
            'band' => $form_credentials['band'],
            'open_date' => $form_credentials['open_date'],
            'critical_position' => $form_credentials['critical_position'],
            'business' => $form_credentials['business'],
            'division' => $form_credentials['division'],
            'function' => $form_credentials['function'],
            'location' => $form_credentials['location'],
            'billing_status' => $form_credentials['billing_status'],
            'interviewer' => $form_credentials['interviewer'],
            'salary_range' => $form_credentials['salary_range']
        ] );
    }

    public function reqcruitment_request_editprocess_new( $form_credentials ){

        $update_mdlrecruitreqtbl = DB::table('tbl_rfh');
        $update_mdlrecruitreqtbl = $update_mdlrecruitreqtbl->where( 'res_id', '=', $form_credentials['res_id'] );
    
        $update_mdlrecruitreqtbl->update( [ 
            'rolls_option' => $form_credentials['rolls_option'],
            'name' => $form_credentials['name'],
            'mobile' => $form_credentials['mobile'],
            'email' => $form_credentials['email'],
            'position_reports' => $form_credentials['position_reports'],
            'approved_by' => $form_credentials['approved_by'],
            'ticket_number' => $form_credentials['ticket_number'],
            'position_title' => $form_credentials['position_title'],
            'location' => $form_credentials['location'],
            'location_preferred' => $form_credentials['location_preferred'],
            'business' => $form_credentials['business'],
            'division' => $form_credentials['division'],
            'band' => $form_credentials['band'],
            'function' => $form_credentials['function'],
            'no_of_positions' => $form_credentials['no_of_positions'],
            'jd_roles' => $form_credentials['jd_roles'],
            'qualification' => $form_credentials['qualification'],
            'essential_skill' => $form_credentials['essential_skill'],
            'good_skill' => $form_credentials['good_skill'],
            'experience' => $form_credentials['experience'],
            'salary_range' => $form_credentials['salary_range'],
            'any_specific' => $form_credentials['any_specific'],
        ] );

        $rolls_option = $form_credentials['rolls_option'];
        if($rolls_option == 'Activity Outsourcing to HEPL'){
            $rolls_option_new = 'Non Billable';
        }else if($rolls_option == 'Manpower Outsourcing to HEPL' || $rolls_option == 'On Rolls'){
            $rolls_option_new = 'Billable';
        }


        $update_mdlrecruitreqtbl =new recruitmentRequest();
        $update_mdlrecruitreqtbl = $update_mdlrecruitreqtbl->where( 'rfh_no', '=', $form_credentials['res_id'] );
    
        $update_mdlrecruitreqtbl->update( [ 
            'position_title' => $form_credentials['position_title'],
            'band' => $form_credentials['band'],
            'business' => $form_credentials['business'],
            'division' => $form_credentials['division'],
            'function' => $form_credentials['function'],
            'location' => $form_credentials['location'],
            'billing_status'=> $rolls_option_new,
			'interviewer'=>$form_credentials['approved_by'],

            'salary_range' => $form_credentials['salary_range'],
        ] );


    }

    public function change_password_process( $form_credentials ){


        $update_mdlusertbl = new User();
        $update_mdlusertbl = $update_mdlusertbl->where( 'empID', '=', $form_credentials['empID'] );
    
        $update_mdlusertbl->update( [ 
            'password' => $form_credentials['confirm_password']
        ] );
    }

    public function add_recruiter_process( $form_credentials ){
        $usertbl = new User();

        $usertbl->empID = $form_credentials['empID'];
        $usertbl->name = $form_credentials['name'];
        $usertbl->designation = $form_credentials['designation'];
        $usertbl->email = $form_credentials['email'];
        $usertbl->role_type = $form_credentials['role_type'];
        $usertbl->profile_status = $form_credentials['profile_status' ];
        $usertbl->password = $form_credentials['password' ];
        
        $usertbl->save();

        if($usertbl) {
            return true;
        } else {
            return false;
        }
    }

    public function get_ticket_report_recruiter_afilter( $advanced_filter ){
        
        $mdlrecreqtbl = DB::table('recruitment_requests as rr');

        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'users as u', 'rr.assigned_to', '=', 'u.empID' );
        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'tat_details_tbls as tdt', 'rr.band', '=', 'tdt.id' );
        $mdlrecreqtbl = $mdlrecreqtbl->select( 'rr.*', 'u.name as recruiter_name','tdt.no_of_days as tat_days' );

        if($advanced_filter['af_from_date'] !=''  && $advanced_filter['af_to_date'] !=''){

            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.open_date', '>=', $advanced_filter['af_from_date'] );
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.open_date', '<=', $advanced_filter['af_to_date']);
        }
        
        if($advanced_filter['af_from_date'] !=''  && $advanced_filter['af_to_date'] ==''){

            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.open_date', '=', $advanced_filter['af_from_date'] );
        }
        if($advanced_filter['af_from_date'] ==''  && $advanced_filter['af_to_date'] !=''){

            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.open_date', '=', $advanced_filter['af_to_date'] );

        }

        if ($advanced_filter['af_position_title'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.position_title',  $advanced_filter['af_position_title']);
        }
        
        if ($advanced_filter['af_critical_position'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.critical_position',  $advanced_filter['af_critical_position']);
        }
        if ($advanced_filter['af_position_status'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.request_status',  $advanced_filter['af_position_status']);
        }
        if ($advanced_filter['af_assigned_status'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.assigned_status',  $advanced_filter['af_assigned_status']);
        }
        if ($advanced_filter['af_salary_range'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.salary_range',  $advanced_filter['af_salary_range']);
        }
        if ($advanced_filter['af_band'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.band',  $advanced_filter['af_band']);
        }
        if ($advanced_filter['af_location'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.location',  $advanced_filter['af_location']);
        }
        if ($advanced_filter['af_business'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.business',  $advanced_filter['af_business']);
        }
        if ($advanced_filter['af_billing_status'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.billing_status',  $advanced_filter['af_billing_status']);
        }
        if ($advanced_filter['af_function'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.function',  $advanced_filter['af_function']);
        }

        if ($advanced_filter['assigned_to'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where('rr.assigned_to', '=', $advanced_filter['assigned_to']);

        }

        return $mdlrecreqtbl
        ->orderBy( 'rr.id', 'desc' )->groupBy('rr.hepl_recruitment_ref_number');

    }

    public function get_ticket_report_recruiter(  ){
        $mdlrecreqtbl = DB::table('recruitment_requests as rr');

        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'users as u', 'rr.assigned_to', '=', 'u.empID' );
        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'tat_details_tbls as tdt', 'rr.band', '=', 'tdt.id' );
        $mdlrecreqtbl = $mdlrecreqtbl->select( 'rr.*', 'u.name as recruiter_name','tdt.no_of_days as tat_days' );
        
        $mdlrecreqtbl = $mdlrecreqtbl->where('rr.assigned_status', '=', "Assigned");


        return $mdlrecreqtbl
        ->orderBy( 'rr.id', 'desc' )->groupBy('rr.hepl_recruitment_ref_number');
    }
    public function recruiter_ageing_details($input_details){

        $get_recruiter_list_af_details = DB::table('candidate_details as cd')
        ->select('*')
        ->where('cd.rfh_no', '=', $input_details['rfh_no'])
        ->where('cd.hepl_recruitment_ref_number', '=', $input_details['hepl_recruitment_ref_number'])
        ->where('cd.created_by', '=', $input_details['created_by'])
        ->where('cd.created_on', '<=', $input_details['check_date'])
        ->orderBy('cd.id', 'asc')
        ->get();

        return $get_recruiter_list_af_details;
    }

    public function recruiter_last_modified_date($credentials){
        $get_recruiter_list_af_details = DB::table('candidate_details as cd')
        ->select('*')
        ->where('cd.rfh_no', '=', $credentials['rfh_no'])
        ->where('cd.hepl_recruitment_ref_number', '=', $credentials['hepl_recruitment_ref_number'])
        ->where('cd.created_by', '=', $credentials['created_by'])
        ->orderBy('cd.id', 'desc')
        ->limit(1)
        ->get();

        return $get_recruiter_list_af_details;
    }

    public function get_assigned_recruiters($hepl_recruitment_ref_number){

        $mdlrecruitmenttbl = DB::table('recruitment_requests as rr')
        ->leftjoin('users as u', 'rr.assigned_to', '=', 'u.empID')
        ->select('u.name')
        ->where('rr.hepl_recruitment_ref_number', '=', $hepl_recruitment_ref_number)
        ->orderBy('rr.created_at', 'desc')
        ->groupBy('rr.assigned_to')
        ->get();

        

        return $mdlrecruitmenttbl;
    }

    public function get_interviewer_self( $rfh_no ){

        $get_interviewer_self = DB::table('tbl_rfh')
        ->select('name')
        ->where('res_id', '=', $rfh_no)
        ->orderBy('id', 'desc')
        ->get();

        return $get_interviewer_self;
    }
    
    public function get_cv_count($hepl_recruitment_ref_number){
        $mdlrecruitmenttbl = DB::table('candidate_details as cd')
        ->select('cd.*')
        ->where('cd.hepl_recruitment_ref_number', '=', $hepl_recruitment_ref_number)
        ->orderBy('cd.created_at', 'desc')
        ->count();

        return $mdlrecruitmenttbl;
    }
}