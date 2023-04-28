<?php

namespace App\Repositories;

use App\Models\recruitmentRequest;
use App\Models\Offer_released_details;
use App\Models\Offer_released_later_date;
use App\Models\Candidate_details;
use DB;
class RecruiterRepository implements IRecruiterRepository
{  
    public function get_assigned_reqcruitment_request( $input_details ){
        $mdlrecruitmenttbl = DB::table('recruitment_requests as rr')
        ->leftJoin('tat_details_tbls as tdt', 'rr.band', '=', 'tdt.band_title')
        ->select('rr.*','tdt.no_of_days as tat_days')
        ->where('rr.assigned_to', '=', $input_details['assigned_to'])
        ->whereDate('rr.assigned_date', '=', $input_details['current_date'])
        ->where('rr.request_status', '!=', 'On Hold')
        ->where('rr.request_status', '!=', 'Closed')

        ->orderBy('rr.created_at', 'desc')
        ->get();

        return $mdlrecruitmenttbl;
    }
    
    public function get_assigned_reqcruitment_request_old_positions( $input_details ){
        $mdlrecruitmenttbl = DB::table('recruitment_requests as rr')
        ->leftJoin('tat_details_tbls as tdt', 'rr.band', '=', 'tdt.band_title')
        ->select('rr.*','tdt.no_of_days as tat_days')
        ->where('rr.assigned_to', '=', $input_details['assigned_to'])
        ->where('rr.assigned_date', '!=', $input_details['current_date'])
        ->where('rr.request_status', '!=', 'On Hold')
        ->where('rr.request_status', '!=', 'Closed')
        ->orderBy('rr.created_at', 'desc')
        ->get();

        return $mdlrecruitmenttbl;
    }

    public function get_assigned_reqcruitment_inactive($credentials){

        $mdlrecruitmenttbl = DB::table('recruitment_requests as rr')
        ->select('rr.*')
        ->where('rr.assigned_to', '=', $credentials['assigned_to'])
        ->where('rr.request_status', '=', 'On Hold')
        ->orderBy('rr.created_at', 'desc')
        ->get();

        return $mdlrecruitmenttbl;
    }

    public function show_uploaded_cv( $hepl_recruitment_ref_number ){
        
        $mdlrecruitmenttbl = DB::table('candidate_details as cd')

        ->select('cd.*')
        ->where('cd.hepl_recruitment_ref_number', '=', $hepl_recruitment_ref_number)
        ->where('cd.red_flag_status', '=', "0")
        ->orderBy('cd.created_at', 'desc')
        ->get();

        return $mdlrecruitmenttbl;
    }

    public function process_default_status($credentials){

        $update_mdlrecruitreqtbl = new Candidate_details();
        $update_mdlrecruitreqtbl = $update_mdlrecruitreqtbl->where( 'cdID', '=', $credentials['cdID'] );
    
        $update_mdlrecruitreqtbl->update( [ 
            'status' => $credentials['action_for_the_day_status']
            
        ] );
    }

    public function process_offer_release_details($form_credentials){
        $reqtbl = new Offer_released_details();
        $reqtbl->orID = 'OR'.str_pad( ( $reqtbl->max( 'id' )+1 ), 9, '0', STR_PAD_LEFT );
        $reqtbl->cdID = $form_credentials['cdID'];
        $reqtbl->rfh_no = $form_credentials['rfh_no'];
        $reqtbl->profile_status = $form_credentials['profile_status'];
        $reqtbl->hepl_recruitment_ref_number = $form_credentials['hepl_recruitment_ref_number'];
        $reqtbl->closed_date = $form_credentials['closed_date'];
        $reqtbl->closed_salary = $form_credentials['closed_salary'];
        $reqtbl->salary_review = $form_credentials['salary_review'];
        $reqtbl->joining_type = $form_credentials['joining_type' ];
        $reqtbl->date_of_joining = $form_credentials['date_of_joining' ];
        $reqtbl->remark = $form_credentials['remark' ];
        $reqtbl->created_by = $form_credentials['created_by' ];
        $reqtbl->created_on = $form_credentials['created_on' ];
        
        $reqtbl->save();

        if($reqtbl) {
            return true;
        } else {
            return false;
        }
    }

    public function cd_status_update( $credentials ){
        $update_mdlrecruitreqtbl = new Candidate_details();
        $update_mdlrecruitreqtbl = $update_mdlrecruitreqtbl->where( 'cdID', '=', $credentials['cdID'] );
    
        $update_mdlrecruitreqtbl->update( [ 
            'status' => $credentials['status']
            
        ] );
    }

    public function get_offer_released_tb( $credentials){

        $mdlrecruitmenttbl = DB::table('offer_released_details as ord')
        ->leftJoin('candidate_details as cd', 'ord.cdID', '=', 'cd.cdID')
        ->leftJoin('recruitment_requests as rr', 'ord.hepl_recruitment_ref_number', '=', 'rr.hepl_recruitment_ref_number')
        ->select('ord.*','cd.candidate_name as candidate_name', 'cd.candidate_cv as candidate_cv',)
        ->where('ord.created_by', '=', $credentials['assigned_to'])
        ->where('ord.joining_type', '=', $credentials['joining_type'])
        ->where('rr.request_status', '=', $credentials['request_status'])
        ->where('ord.profile_status', '!=', $credentials['profile_status_1'])
        ->where('ord.profile_status', '!=', $credentials['profile_status_2'])
        ->where('cd.red_flag_status', '=', "0")
        
        ->orderBy('ord.created_at', 'desc')
        ->get();

        return $mdlrecruitmenttbl;
    }

    public function check_offer_release_details($credentials){

        $mdlrecruitmenttbl = DB::table('offer_released_details as ord')
        ->select('ord.*')
        ->where('ord.cdID', '=', $credentials['cdID'])
        ->where('ord.hepl_recruitment_ref_number', '=', $credentials['hepl_recruitment_ref_number'])
        ->orderBy('ord.created_at', 'desc')
        ->count();

        return $mdlrecruitmenttbl;

    }

    public function offer_released_edit_process( $form_credentials ){
        $reqtbl = new Offer_released_later_date();
        $reqtbl->orldID = 'ORLD'.str_pad( ( $reqtbl->max( 'id' )+1 ), 9, '0', STR_PAD_LEFT );
        $reqtbl->cdID = $form_credentials['cdID'];
        $reqtbl->rfh_no = $form_credentials['rfh_no'];
        $reqtbl->hepl_recruitment_ref_number = $form_credentials['or_ldj_hepl_recruitment_ref_number'];
        $reqtbl->orladj_resignation_received = $form_credentials['orladj_resignation_received'];
        $reqtbl->orladj_touchbase = $form_credentials['orladj_touchbase'];
        $reqtbl->initiate_backfil = $form_credentials['initiate_backfil'];
        $reqtbl->created_by = $form_credentials['created_by' ];
        $reqtbl->created_on = $form_credentials['created_on' ];
        
        $reqtbl->save();

        if($reqtbl) {
            return true;
        } else {
            return false;
        }
    }

    public function or_ldj_history($credentials){
        $mdlrecruitmenttbl = DB::table('offer_released_later_dates as orld')

        ->select('orld.*')
        ->where('orld.cdID', '=', $credentials['cdID'])
        ->where('orld.created_by', '=', $credentials['created_by'])
        ->orderBy('orld.created_at', 'desc')
        ->get();

        return $mdlrecruitmenttbl;
    }

    public function get_no_profile_position( $credentials ){
        $mdlrecruitmenttbl = DB::table('recruitment_requests as rr')
        ->select('rr.no_of_position')
        ->where('rr.rfh_no', '=', $credentials['rfh_no'])
        ->groupBy('rr.rfh_no')
        ->count();

        return $mdlrecruitmenttbl;
    }
    public function get_no_profile_position_recreq($credentials){
        $mdlrecruitmenttbl = DB::table('recruitment_requests as rr')
        ->select('rr.*')
        ->where('rr.rfh_no', '=', $credentials['rfh_no'])
        ->where('rr.hepl_recruitment_ref_number', '=', $credentials['hepl_recruitment_ref_number'])
        ->where('rr.request_status', '=', $credentials['request_status'])
        ->count();

        return $mdlrecruitmenttbl;
    }

    public function get_no_candidate_onboarded( $credentials ){
        $mdlrecruitmenttbl = DB::table('candidate_details as cd')
        ->select('cd.no_of_position')
        ->where('cd.rfh_no', '=', $credentials['rfh_no'])
        ->where('cd.status', '=', $credentials['status'])
        
        ->groupBy('cd.rfh_no')
        ->count();

        return $mdlrecruitmenttbl;
    }

    public function update_position_status_closed( $credentials ){
        $update_mdlrecruitreqtbl = new recruitmentRequest();
        $update_mdlrecruitreqtbl = $update_mdlrecruitreqtbl->where( 'hepl_recruitment_ref_number', '=', $credentials['hepl_recruitment_ref_number'] );
        // $update_mdlrecruitreqtbl = $update_mdlrecruitreqtbl->where( 'assigned_to', '=', $credentials['assigned_to'] );
    
        $update_mdlrecruitreqtbl->update( [ 
            'close_date' => $credentials['closed_date'],
            'request_status' => $credentials['request_status']
            
        ] );
    }

    public function update_position_status_orldj( $credentials ){
        $update_mdlrecruitreqtbl = new Offer_released_details();
        $update_mdlrecruitreqtbl = $update_mdlrecruitreqtbl->where( 'rfh_no', '=', $credentials['rfh_no'] );
        $update_mdlrecruitreqtbl = $update_mdlrecruitreqtbl->where( 'cdID', '=', $credentials['cdID'] );
    
        $update_mdlrecruitreqtbl->update( [ 
            'profile_status' => $credentials['profile_status']
            
        ] );
    }

    public function candidate_follow_up_history( $input_details ){
        $mdlrecruitmenttbl = DB::table('candidate_followup_details as cfd')

        ->select('cfd.*')
        ->where('cfd.cdID', '=', $input_details['cdID'])
        ->orderBy('cfd.created_at', 'desc')
        ->get();

        return $mdlrecruitmenttbl;
    }

    public function update_red_flag( $credentials ){
        $update_mdlrecruitreqtbl = new Candidate_details();
        $update_mdlrecruitreqtbl = $update_mdlrecruitreqtbl->where( 'cdID', '=', $credentials['cdID'] );
    
        $update_mdlrecruitreqtbl->update( [ 
            'red_flag_status' => $credentials['red_flag_status']
            
        ] );
    }

    // public function initiate_backfil_reopen($credentials){
    //     $update_mdlrecruitreqtbl = new recruitmentRequest();
    //     $update_mdlrecruitreqtbl = $update_mdlrecruitreqtbl->where( 'rfh_no', '=', $credentials['rfh_no'] );
    
    //     $update_mdlrecruitreqtbl->update( [ 
    //         'request_status' => $credentials['request_status']
            
    //     ] );
    // }
    
    public function get_offer_released_report( $credentials ){

        $mdlrecruitmenttbl = DB::table('offer_released_details as ord')

        ->select('ord.*')
        ->where('ord.cdID', '=', $credentials['cdID'])
        ->orderBy('ord.created_at', 'desc')
        ->get();

        return $mdlrecruitmenttbl;
    }

    public function get_offer_released_ld_report( $credentials ){
        
        $mdlrecruitmenttbl = DB::table('offer_released_later_dates as orld')

        ->select('orld.*')
        ->where('orld.cdID', '=', $credentials['cdID'])
        ->orderBy('orld.created_at', 'desc')
        ->get();

        return $mdlrecruitmenttbl;
    }

    public function get_candidate_onborded_history($credentials){
        $mdlrecruitmenttbl = DB::table('candidate_details as cd')
        ->leftJoin('recruitment_requests as rr', 'cd.rfh_no', '=', 'rr.rfh_no')

        ->select('cd.*','rr.position_title')
        ->where('cd.status', '=', $credentials['status'])
        ->where('cd.created_by', '=', $credentials['created_by'])
        ->orderBy('cd.created_at', 'desc')
        ->groupBy('rr.rfh_no')
        ->get();

        return $mdlrecruitmenttbl;
    }
    

    public function get_candidate_profile( $input_details ){

        $mdlrecruitmenttbl = DB::table('candidate_details as cd')
        ->join('recruitment_requests as rr', 'cd.rfh_no', '=', 'rr.rfh_no', 'left outer')

        ->select('cd.*','rr.position_title')
        ->where('cd.status', '!=', $input_details['status'])
        ->where('cd.created_by', '=', $input_details['created_by'])
        ->orderBy('cd.created_at', 'desc')
        ->groupBy('cd.cdID')
        ->get();

        return $mdlrecruitmenttbl;

        
    }

    public function get_ticket_report_recruiter( $input_details ){
        $mdlrecruitmenttbl = DB::table('recruitment_requests as rr')
        ->leftJoin('users as u', 'rr.assigned_to', '=', 'u.empID')
        ->leftJoin('tat_details_tbls as tdt', 'rr.band', '=', 'tdt.band_title')
        ->select('rr.*', 'u.name as recruiter_name','tdt.no_of_days as tat_days')
        ->where('rr.assigned_to', '=', $input_details['assigned_to'])
        ->orderBy('rr.created_at', 'desc')
        ->groupBy('rr.hepl_recruitment_ref_number')
       ->get();

        return $mdlrecruitmenttbl;
    }

    public function ticket_candidate_details( $input_details ){
        $mdlrecruitmenttbl = DB::table('candidate_details as cd')
        ->leftjoin('recruitment_requests as rr', 'cd.rfh_no', '=', 'rr.rfh_no')
        ->leftjoin('users as u', 'cd.created_by', '=', 'u.empID')
        ->leftJoin('tat_details_tbls as tdt', 'rr.band', '=', 'tdt.band_title')
        
        ->select('cd.*','rr.position_title','u.name','tdt.no_of_days as tat_days')
        ->where('cd.hepl_recruitment_ref_number', '=', $input_details['hepl_recruitment_ref_number'])
        // ->where('cd.created_by', '=', $input_details['created_by'])

        ->orderBy('cd.created_at', 'desc')
        ->groupBy('cd.cdID')
        ->get();

        

        return $mdlrecruitmenttbl;
    }

    public function ticket_candidate_details_af( $advanced_filter ){

        $mdlrecreqtbl = DB::table('candidate_details as cd');

        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'recruitment_requests as rr', 'cd.rfh_no', '=', 'rr.rfh_no' );
        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'users as u', 'cd.created_by', '=', 'u.empID' );
        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'tat_details_tbls as tdt', 'rr.band', '=', 'tdt.band_title' );
        
        $mdlrecreqtbl = $mdlrecreqtbl->select( 'cd.*','rr.position_title','u.name','tdt.no_of_days as tat_days' );

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
        
        if ($advanced_filter['created_by'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where('cd.created_by', '=', $advanced_filter['created_by']);

        }
        
        
        $mdlrecreqtbl = $mdlrecreqtbl->where('cd.hepl_recruitment_ref_number', '=', $advanced_filter['hepl_recruitment_ref_number']);

        return $mdlrecreqtbl
        ->orderBy( 'cd.created_at', 'desc' )->groupBy('cd.cdID')->get();
    }


    public function get_ticket_report_recruiter_afilter( $advanced_filter ){
        

        $mdlrecreqtbl = DB::table('recruitment_requests as rr');

        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'users as u', 'rr.assigned_to', '=', 'u.empID' );
        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'tat_details_tbls as tdt', 'rr.band', '=', 'tdt.band_title' );
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
        $mdlrecreqtbl = $mdlrecreqtbl->where('rr.assigned_to', '=', $advanced_filter['assigned_to']);

        return $mdlrecreqtbl
        ->orderBy( 'rr.id', 'desc' )->groupBy('rr.hepl_recruitment_ref_number');

    }

    public function get_candidate_profile_af( $advanced_filter ){
        

        $mdlrecreqtbl = DB::table('candidate_details as cd');

        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'recruitment_requests as rr', 'cd.rfh_no', '=', 'rr.rfh_no' );
        
        $mdlrecreqtbl = $mdlrecreqtbl->select( 'cd.*','rr.position_title' );

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
        

        $mdlrecreqtbl = $mdlrecreqtbl->where( 'cd.status', '!=', $advanced_filter['status']);
        $mdlrecreqtbl = $mdlrecreqtbl->where( 'cd.created_by', '=', $advanced_filter['created_by']);
       
        return $mdlrecreqtbl
        ->orderBy( 'cd.created_at', 'desc' )->groupBy('cd.cdID')->get();


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

    public function get_interviewer_self( $rfh_no ){

        $get_interviewer_self = DB::table('tbl_rfh')
        ->select('name')
        ->where('res_id', '=', $rfh_no)
        ->orderBy('id', 'desc')
        ->get();

        return $get_interviewer_self;
    }
    
    public function get_recr_req($hepl_recruitment_ref_number)
    {
        # code...
        $querytbl = new recruitmentRequest(); 
        $querytbl = $querytbl->where( 'hepl_recruitment_ref_number','=', $hepl_recruitment_ref_number );
        $querytbl = $querytbl->orderBy('id', 'desc');
        $querytbl = $querytbl->limit(1);
        return $querytbl = $querytbl->get();
    }

    public function get_candidate_row($cdID)
    {
        # code...
        $querytbl = new Candidate_details(); 
        $querytbl = $querytbl->where( 'cdID','=', $cdID );
        return $querytbl = $querytbl->get();
    }
}