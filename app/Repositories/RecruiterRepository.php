<?php

namespace App\Repositories;

use App\Models\recruitmentRequest;
use App\Models\Offer_released_details;
use App\Models\Offer_released_later_date;
use App\Models\Candidate_details;
use App\Models\Ctc_calculation;
use App\Models\Offer_release_followup_details;
use App\Models\Departments;
use App\Models\Customusers;


use DB;
class RecruiterRepository implements IRecruiterRepository
{
    public function get_assigned_reqcruitment_request( $input_details ){

        $mdlrecruitmenttbl = DB::table('recruitment_requests as rr')
        ->leftJoin('tbl_rfh as tr', 'rr.rfh_no', '=', 'tr.res_id')
        ->leftJoin('tat_details_tbls as tdt', 'rr.band', '=', 'tdt.id')
        ->select('rr.*','tr.name as raised_by', 'tr.approved_by as approved_by','tdt.no_of_days as tat_days','tdt.band_title as band_title')
        ->where('rr.assigned_to', '=', $input_details['assigned_to'])
        ->whereDate('rr.assigned_date', '=', $input_details['current_date'])
        ->where('rr.request_status', '!=', 'On Hold')
        ->where('rr.request_status', '!=', 'Closed')
        ->where('rr.delete_status',  '=', "0")
        ->orderBy('rr.created_at', 'desc')
        ->groupBy('rr.hepl_recruitment_ref_number')

        ->get();

        return $mdlrecruitmenttbl;
    }

    public function get_assigned_reqcruitment_request_old_positions( $input_details ){
        $mdlrecruitmenttbl = DB::table('recruitment_requests as rr')
        ->leftJoin('tbl_rfh as tr', 'rr.rfh_no', '=', 'tr.res_id')
        ->leftJoin('tat_details_tbls as tdt', 'rr.band', '=', 'tdt.id')
        ->select('rr.*','tr.name as raised_by', 'tr.approved_by as approved_by','tdt.no_of_days as tat_days','tdt.band_title as band_title')
        ->where('rr.assigned_to', '=', $input_details['assigned_to'])
        ->where('rr.assigned_date', '!=', $input_details['current_date'])
        ->where('rr.request_status', '!=', 'On Hold')
        // ->where('rr.request_status', '!=', 'Closed')
        ->where('rr.delete_status',  '=', "0")
        ->orderBy('rr.created_at', 'desc')
        ->groupBy('rr.hepl_recruitment_ref_number')
        ->get();

        return $mdlrecruitmenttbl;
    }

    public function get_assigned_reqcruitment_inactive($credentials){

        $mdlrecruitmenttbl = DB::table('recruitment_requests as rr')
        ->select('rr.*','tr.name as raised_by', 'tr.approved_by as approved_by','tdt.band_title as band_title')
        ->leftJoin( 'tbl_rfh as tr', 'rr.rfh_no', '=', 'tr.res_id' )
        ->leftJoin('tat_details_tbls as tdt', 'rr.band', '=', 'tdt.id')
        ->where('rr.assigned_to', '=', $credentials['assigned_to'])
        ->where('rr.request_status', '=', 'On Hold')
        ->where('rr.delete_status',  '=', "0")
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

    public function show_uploaded_cv_recruiter( $input_details ){
        $mdlrecruitmenttbl = DB::table('candidate_details as cd')

        ->select('cd.*')
        ->where('cd.hepl_recruitment_ref_number', '=', $input_details['hepl_recruitment_ref_number'])
        ->where('cd.red_flag_status', '=', "0")
        ->where('cd.created_by', '=', $input_details['created_by'])
        ->orderBy('cd.created_at', 'desc')
        ->get();

        return $mdlrecruitmenttbl;
    }

    public function process_default_status($credentials){

        $update_mdlrecruitreqtbl = new Candidate_details();
        $update_mdlrecruitreqtbl = $update_mdlrecruitreqtbl->where( 'cdID', '=', $credentials['cdID'] );

        if($credentials['action_for_the_day_status'] =='Offer Accepted'){
            $update_mdlrecruitreqtbl->update( [
                'candidate_email' => $credentials['candidate_email'],
                'status' => $credentials['action_for_the_day_status']

            ] );
        }
        elseif ($credentials['action_for_the_day_status'] =='Document Collection') {
            $update_mdlrecruitreqtbl->update( [
                'candidate_email' => $credentials['candidate_email'],
                'candidate_type' => $credentials['candidate_type'],
                'doc_status' => $credentials['doc_status'],
                'status' => $credentials['action_for_the_day_status']

            ] );
        }
        else{
            $update_mdlrecruitreqtbl->update( [
                'status' => $credentials['action_for_the_day_status']

            ] );
        }

    }
    public function process_default_status_cd( $credentials ){
        $update_mdlrecruitreqtbl = new Candidate_details();
        $update_mdlrecruitreqtbl = $update_mdlrecruitreqtbl->where( 'cdID', '=', $credentials['cdID'] );

        $update_mdlrecruitreqtbl->update( [
            'candidate_email' => $credentials['candidate_email'],
            'status' => $credentials['action_for_the_day_status'],
            'offer_rel_status' => $credentials['offer_rel_status']

        ] );
    }
    public function update_request_status($credentials){

        $update_mdlrecruitreqtbl = new recruitmentRequest();
        $update_mdlrecruitreqtbl = $update_mdlrecruitreqtbl->where( 'rfh_no', '=', $credentials['rfh_no'] );
        $update_mdlrecruitreqtbl = $update_mdlrecruitreqtbl->where( 'hepl_recruitment_ref_number', '=', $credentials['hepl_recruitment_ref_number'] );

        $update_mdlrecruitreqtbl->update( [
            'request_status' => $credentials['request_status'],
            'open_date' => $credentials['open_date'],
            'assigned_date' => $credentials['assigned_date']

        ] );
    }

    public function process_offer_release_details($form_credentials){
        $reqtbl = new Offer_released_details();
        $reqtbl->orID = 'OR'.str_pad( ( $reqtbl->max( 'id' )+1 ), 9, '0', STR_PAD_LEFT );
        $reqtbl->cdID = $form_credentials['cdID'];
        $reqtbl->rfh_no = $form_credentials['rfh_no'];
        $reqtbl->profile_status = $form_credentials['profile_status'];
        $reqtbl->hepl_recruitment_ref_number = $form_credentials['hepl_recruitment_ref_number'];
        // $reqtbl->closed_date = $form_credentials['closed_date'];
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
    public function cd_status_update_ors( $credentials ){
        $update_mdlrecruitreqtbl = new Candidate_details();
        $update_mdlrecruitreqtbl = $update_mdlrecruitreqtbl->where( 'cdID', '=', $credentials['cdID'] );

        $update_mdlrecruitreqtbl->update( [
            'status' => $credentials['status'],
            'offer_rel_status' => $credentials['offer_rel_status']

        ] );
    }

    public function get_offer_released_tb( $credentials){

        $mdlrecruitmenttbl = DB::table('offer_released_details as ord')
        ->leftJoin('candidate_details as cd', 'ord.cdID', '=', 'cd.cdID')
        ->leftJoin('recruitment_requests as rr', 'ord.hepl_recruitment_ref_number', '=', 'rr.hepl_recruitment_ref_number')
        ->select('ord.*','rr.id as rr_id','rr.position_title as position_title','cd.candidate_name as candidate_name', 'cd.candidate_cv as candidate_cv',)
        ->where('ord.created_by', '=', $credentials['assigned_to'])
        // ->where('ord.joining_type', '=', $credentials['joining_type'])
        ->where('rr.request_status', '=', $credentials['request_status'])
        ->where('ord.profile_status', '!=', $credentials['profile_status_1'])
        ->where('ord.profile_status', '!=', $credentials['profile_status_2'])
        ->where('cd.red_flag_status', '=', "0")
        ->where('rr.delete_status',  '=', "0")
        ->groupBy('ord.cdID')
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
    public function get_offer_release_details( $input_details ){

        $mdlrecruitmenttbl = DB::table('offer_released_details as ord')
        ->select('ord.*')
        ->where('ord.cdID', '=', $input_details['cdID'])
        //->where('ord.hepl_recruitment_ref_number', '=', $input_details['hepl_recruitment_ref_number'])
      //  ->orderBy('ord.created_at', 'desc')
        ->get();

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
        ->where('rr.delete_status',  '=', "0")
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
        ->where('rr.delete_status',  '=', "0")
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

        if($credentials['action_for_the_day_status'] =='Candidate Onboarded'){
            $update_mdlrecruitreqtbl = new recruitmentRequest();
            $update_mdlrecruitreqtbl = $update_mdlrecruitreqtbl->where( 'hepl_recruitment_ref_number', '=', $credentials['hepl_recruitment_ref_number'] );

            $update_mdlrecruitreqtbl->update( [
                'closed_by' => $credentials['closed_by'],
                'request_status' => $credentials['request_status']
            ] );

        }else{
            $update_mdlrecruitreqtbl = new recruitmentRequest();
            $update_mdlrecruitreqtbl = $update_mdlrecruitreqtbl->where( 'hepl_recruitment_ref_number', '=', $credentials['hepl_recruitment_ref_number'] );

            $update_mdlrecruitreqtbl->update( [
                'closed_by' => $credentials['closed_by'],
                'close_date' => $credentials['closed_date'],
                'request_status' => $credentials['request_status']
            ] );

        }

        $update_mdlofferreltbl = new Offer_released_details();
        $update_mdlofferreltbl = $update_mdlofferreltbl->where( 'hepl_recruitment_ref_number', '=', $credentials['hepl_recruitment_ref_number'] );

        $update_mdlofferreltbl->update( [
            'closed_date' => $credentials['closed_date']
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
    public function update_reject_status( $credentials ){
        $update_mdlrecruitreqtbl = new Offer_released_details();
        $update_mdlrecruitreqtbl = $update_mdlrecruitreqtbl->where( 'rfh_no', '=', $credentials['rfh_no'] );
        $update_mdlrecruitreqtbl = $update_mdlrecruitreqtbl->where( 'cdID', '=', $credentials['cdID'] );

        $update_mdlrecruitreqtbl->update( [
            'profile_status' => $credentials['profile_status'],
            'remark' => $credentials['remark']

        ] );
    }

    public function candidate_follow_up_history( $input_details ){
        $mdlrecruitmenttbl = DB::table('candidate_followup_details as cfd')

        ->select('cfd.*')
        ->where('cfd.cdID', '=', $input_details['cdID'])
        ->orderBy('cfd.created_at', 'asc')
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

        ->select('cd.*','rr.position_title','rr.sub_position_title')
        ->where('cd.status', '=', $credentials['status'])
        ->where('cd.created_by', '=', $credentials['created_by'])
        ->where('rr.delete_status',  '=', "0")
        ->orderBy('cd.created_at', 'desc')

        ->groupBy('rr.rfh_no')
        ->get();

        return $mdlrecruitmenttbl;
    }


    public function get_candidate_profile( $input_details ){

        $mdlrecruitmenttbl = DB::table('candidate_details as cd')
        ->join('recruitment_requests as rr', 'cd.hepl_recruitment_ref_number', '=', 'rr.hepl_recruitment_ref_number', 'left outer')

        ->select('cd.*','rr.position_title','rr.sub_position_title')
        // ->where('cd.status', '!=', $input_details['status'])
        ->where('cd.created_by', '=', $input_details['created_by'])
        ->where('rr.delete_status',  '=', "0")
        ->where('cd.candidate_status',  '=', "1")
        ->orderBy('cd.created_at', 'desc')
        ->groupBy('cd.cdID')
        ->get();

        return $mdlrecruitmenttbl;


    }

    public function get_ticket_report_recruiter( $input_details ){
        $mdlrecruitmenttbl = DB::table('recruitment_requests as rr')

        ->leftJoin( 'tbl_rfh as tr', 'rr.rfh_no', '=', 'tr.res_id' )
        ->leftJoin('users as u', 'rr.assigned_to', '=', 'u.empID')
        ->leftJoin('tat_details_tbls as tdt', 'rr.band', '=', 'tdt.id')
        ->select('rr.*', 'tr.name as raised_by', 'tr.approved_by as approved_by','u.name as recruiter_name','tdt.no_of_days as tat_days','tdt.band_title as band_title')
        ->where('rr.assigned_to', '=', $input_details['assigned_to'])
        ->where('rr.delete_status',  '=', "0")
        ->orderBy('rr.created_at', 'desc')
        ->groupBy('rr.hepl_recruitment_ref_number')
       ->get();

        return $mdlrecruitmenttbl;
    }

    public function get_ticket_report_recruiter_custom_filter( $input_details ){
        $mdlrecruitmenttbl = DB::table('recruitment_requests as rr')
        ->leftJoin('users as u', 'rr.assigned_to', '=', 'u.empID')
        ->leftJoin('tat_details_tbls as tdt', 'rr.band', '=', 'tdt.id')
        ->select('rr.*', 'u.name as recruiter_name','tdt.no_of_days as tat_days','tdt.band_title as band_title')
        ->where('rr.assigned_to', '=', $input_details['assigned_to'])
        ->where('rr.position_title', 'LIKE', "%{$input_details['custom_filter']}%")
        ->where('rr.delete_status',  '=', "0")
        ->orderBy('rr.created_at', 'desc')
        ->groupBy('rr.hepl_recruitment_ref_number')
       ->get();

        return $mdlrecruitmenttbl;
    }

    public function ticket_candidate_details( $input_details ){

        if($input_details['created_by'] !='no_user'){
            $mdlrecruitmenttbl = DB::table('candidate_details as cd')
            ->leftjoin('recruitment_requests as rr', 'cd.rfh_no', '=', 'rr.rfh_no')
            ->leftjoin('users as u', 'cd.created_by', '=', 'u.empID')
            ->leftJoin('tat_details_tbls as tdt', 'rr.band', '=', 'tdt.band_title')

            ->select('cd.*','rr.position_title','rr.sub_position_title','u.name','tdt.no_of_days as tat_days')
            ->where('cd.hepl_recruitment_ref_number', '=', $input_details['hepl_recruitment_ref_number'])
            ->where('cd.created_by', '=', $input_details['created_by'])
            ->where('rr.delete_status',  '=', "0")
            ->where('cd.candidate_status',  '=', "1")
            ->orderBy('cd.created_at', 'desc')
            ->groupBy('cd.cdID')
            ->get();

        }
        else{
            $mdlrecruitmenttbl = DB::table('candidate_details as cd')
            ->leftjoin('recruitment_requests as rr', 'cd.rfh_no', '=', 'rr.rfh_no')
            ->leftjoin('users as u', 'cd.created_by', '=', 'u.empID')
            ->leftJoin('tat_details_tbls as tdt', 'rr.band', '=', 'tdt.band_title')

            ->select('cd.*','rr.position_title','rr.sub_position_title','u.name','tdt.no_of_days as tat_days')
            ->where('cd.hepl_recruitment_ref_number', '=', $input_details['hepl_recruitment_ref_number'])
            // ->where('cd.created_by', '=', $input_details['created_by'])
            ->where('rr.delete_status',  '=', "0")
            ->orderBy('cd.created_at', 'desc')
            ->groupBy('cd.cdID')
            ->get();

        }
        return $mdlrecruitmenttbl;
    }

    public function ticket_candidate_details_recuiter( $input_details ){

        $mdlrecruitmenttbl = DB::table('candidate_details as cd')
        ->leftjoin('recruitment_requests as rr', 'cd.rfh_no', '=', 'rr.rfh_no')
        ->leftjoin('users as u', 'cd.created_by', '=', 'u.empID')
        ->leftJoin('tat_details_tbls as tdt', 'rr.band', '=', 'tdt.band_title')

        ->select('cd.*','rr.position_title','rr.sub_position_title','u.name','tdt.no_of_days as tat_days')
        ->where('cd.hepl_recruitment_ref_number', '=', $input_details['hepl_recruitment_ref_number'])
        ->where('cd.created_by', '=', $input_details['created_by'])
        ->where('rr.delete_status',  '=', "0")
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

        $mdlrecreqtbl = $mdlrecreqtbl->select( 'cd.*','rr.position_title','rr.sub_position_title','u.name','tdt.no_of_days as tat_days' );

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

        if ($advanced_filter['created_by'] != '' && $advanced_filter['created_by'] !='no_user') {

            $mdlrecreqtbl = $mdlrecreqtbl->where('cd.created_by', '=', $advanced_filter['created_by']);

        }
        $mdlrecreqtbl = $mdlrecreqtbl->where('rr.delete_status',  '=', "0");

        $mdlrecreqtbl = $mdlrecreqtbl->where('cd.hepl_recruitment_ref_number', '=', $advanced_filter['hepl_recruitment_ref_number']);

        return $mdlrecreqtbl
        ->orderBy( 'cd.created_at', 'desc' )->groupBy('cd.cdID')->get();
    }


    public function get_ticket_report_recruiter_afilter( $advanced_filter ){


        $mdlrecreqtbl = DB::table('recruitment_requests as rr');

        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'tbl_rfh as tr', 'rr.rfh_no', '=', 'tr.res_id' );
        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'users as u', 'rr.assigned_to', '=', 'u.empID' );
        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'tat_details_tbls as tdt', 'rr.band', '=', 'tdt.id' );
        $mdlrecreqtbl = $mdlrecreqtbl->select( 'rr.*','tr.name as raised_by', 'tr.approved_by as approved_by', 'u.name as recruiter_name','tdt.no_of_days as tat_days','tdt.band_title as band_title' );

        if($advanced_filter['af_from_date'] !=''  && $advanced_filter['af_to_date'] !=''){

            if($advanced_filter['af_position_status'] =='Closed'){
                $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.close_date', '>=', $advanced_filter['af_from_date'] );
                $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.close_date', '<=', $advanced_filter['af_to_date']);

            }else{
                $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.open_date', '>=', $advanced_filter['af_from_date'] );
                $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.open_date', '<=', $advanced_filter['af_to_date']);

            }

        }

        if($advanced_filter['af_from_date'] !=''  && $advanced_filter['af_to_date'] ==''){
            if($advanced_filter['af_position_status'] =='Closed'){
                $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.close_date', '=', $advanced_filter['af_from_date'] );

            }else{
                $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.open_date', '=', $advanced_filter['af_from_date'] );

            }
        }
        if($advanced_filter['af_from_date'] ==''  && $advanced_filter['af_to_date'] !=''){
            if($advanced_filter['af_position_status'] =='Closed'){
                $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.close_date', '=', $advanced_filter['af_to_date'] );

            }else{
                $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.open_date', '=', $advanced_filter['af_to_date'] );

            }

        }


        if ($advanced_filter['af_position_title'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.position_title',  $advanced_filter['af_position_title']);
        }
        if ($advanced_filter['af_sub_position_title'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.sub_position_title',  $advanced_filter['af_sub_position_title']);
        }

        if ($advanced_filter['af_critical_position'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.critical_position',  $advanced_filter['af_critical_position']);
        }
        if ($advanced_filter['af_position_status'] != '') {

            if($advanced_filter['af_position_status'] =='Openall'){

                $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.request_status', '!=', 'Closed');
                $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.request_status', '!=', 'On Hold');

            }else{
                $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.request_status',  $advanced_filter['af_position_status']);

            }
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
        if ($advanced_filter['af_division'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.division',  $advanced_filter['af_division']);
        }
        if ($advanced_filter['af_raisedby'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'tr.name',  $advanced_filter['af_raisedby']);
        }
        if ($advanced_filter['af_approvedby'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'tr.approved_by',  $advanced_filter['af_approvedby']);
        }

        $mdlrecreqtbl = $mdlrecreqtbl->where('rr.delete_status',  '=', "0");

        $mdlrecreqtbl = $mdlrecreqtbl->where('rr.assigned_to', '=', $advanced_filter['assigned_to']);

        return $mdlrecreqtbl
        ->orderBy( 'rr.id', 'desc' )->groupBy('rr.hepl_recruitment_ref_number');

    }

    public function get_candidate_profile_af( $advanced_filter ){


        $mdlrecreqtbl = DB::table('candidate_details as cd');

        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'recruitment_requests as rr', 'cd.hepl_recruitment_ref_number', '=', 'rr.hepl_recruitment_ref_number' );

        $mdlrecreqtbl = $mdlrecreqtbl->select( 'cd.*','rr.position_title','rr.sub_position_title' );

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

        if ($advanced_filter['af_sub_position_title'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.sub_position_title',  $advanced_filter['af_sub_position_title']);
        }

        if ($advanced_filter['af_position_status'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'cd.status',  $advanced_filter['af_position_status']);
        }

        $mdlrecreqtbl = $mdlrecreqtbl->where('rr.delete_status',  '=', "0");
        $mdlrecreqtbl = $mdlrecreqtbl->where('cd.candidate_status',  '=', "1");

        // $mdlrecreqtbl = $mdlrecreqtbl->where( 'cd.status', '!=', $advanced_filter['status']);
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

    public function get_cv_count($input_details){
        $mdlrecruitmenttbl = DB::table('candidate_details as cd')
        ->select('cd.*')
        ->where('cd.hepl_recruitment_ref_number', '=', $input_details['hepl_recruitment_ref_number'])
        ->where('cd.created_by', '=', $input_details['created_by'])
        ->orderBy('cd.created_at', 'desc')
        ->count();

        return $mdlrecruitmenttbl;
    }

    public function modify_close_date( $input_details ){

        $update_mdlrecruitreqtbl = new recruitmentRequest();
        // $update_mdlrecruitreqtbl = $update_mdlrecruitreqtbl->where( 'id', '=', $input_details['id'] );
        $update_mdlrecruitreqtbl = $update_mdlrecruitreqtbl->where( 'hepl_recruitment_ref_number', '=', $input_details['hrr_cd'] );

        $update_mdlrecruitreqtbl->update( [
            'close_date' => $input_details['close_date']
        ] );

        $update_mdlofferreltbl = new Offer_released_details();
        $update_mdlofferreltbl = $update_mdlofferreltbl->where( 'hepl_recruitment_ref_number', '=', $input_details['hrr_cd'] );

        $update_mdlofferreltbl->update( [
            'closed_date' => $input_details['close_date']


        ] );

    }

    public function candidate_follow_up_history_pd( $input_details ){
        $mdlrecruitmenttbl = DB::table('recruitment_requests as rr')
        ->select('rr.*', 'u.name as recruiter_name')
        ->leftJoin( 'users as u', 'rr.assigned_to', '=', 'u.empID' )
        ->where('rr.hepl_recruitment_ref_number', '=', $input_details['hepl_recruitment_ref_number'])
        ->where('rr.assigned_to', '=', $input_details['assigned_to'])
        ->orderBy('rr.created_at', 'desc')
        ->get();

        return $mdlrecruitmenttbl;
    }

    public function process_candidate_delete( $input_details){
        $update_mdlcdtbl = new Candidate_details();
        $update_mdlcdtbl = $update_mdlcdtbl->where( 'cdID', '=', $input_details['cdID'] );

        $update_mdlcdtbl->update( [
            'candidate_status' => '0'
        ] );
    }

    public function get_candidate_details_ed( $input_details ){

        $mdlcandidate_detailstbl = DB::table('candidate_details as cd')
        ->select('cd.*','orf.created_at as offer_date')
        ->leftJoin('offer_release_followup_details as orf', 'cd.cdID', '=', 'orf.cdID')
        ->where('cd.cdID', '=', $input_details['cdID'])
        ->orderBy('cd.updated_at', 'desc')
        ->limit(1)
        ->get();

        return $mdlcandidate_detailstbl;
    }

    public function process_candidate_edit( $input_details ){

        $update_mdlcdtbl = new Candidate_details();
        $update_mdlcdtbl = $update_mdlcdtbl->where( 'cdID', '=', $input_details['cdID'] );

        $update_mdlcdtbl->update( [
            'candidate_name' => $input_details['candidate_name'],
            'gender' => $input_details['candidate_gender'],
            'candidate_email' => $input_details['candidate_email'],
            'candidate_source' => $input_details['candidate_source']
        ] );
    }

    public function dateofjoining_update( $credentials ){
        $update_mdlofferreltbl = new Offer_released_details();
        $update_mdlofferreltbl = $update_mdlofferreltbl->where( 'cdID', '=', $credentials['cdID'] );

        $update_mdlofferreltbl->update( [
            'date_of_joining' => $credentials['date_of_joining']
        ] );
    }

    public function get_current_status_rr( $input_details_cc ){
        $mdlrecruitmenttbl = DB::table('candidate_details as cd')
        ->select('cd.*')
        ->where('cd.hepl_recruitment_ref_number', '=', $input_details_cc['hepl_recruitment_ref_number'])
        ->where('cd.created_by', '=', $input_details_cc['assigned_to'])

        ->orderBy('cd.updated_at', 'desc')
        ->limit(1)
        ->get();

        return $mdlrecruitmenttbl;
    }

    public function get_candidate_profile_dc($input_details){
        $mdlrecruitmenttbl = DB::table('candidate_details as cd')
        ->join('recruitment_requests as rr', 'cd.rfh_no', '=', 'rr.rfh_no', 'left outer')

        ->select('cd.*','rr.position_title','rr.sub_position_title')
        ->where('cd.created_by', '=', $input_details['created_by'])
        ->where('cd.doc_status', '=', $input_details['doc_status'])
        ->where('rr.delete_status',  '=', "0")
        ->where('cd.candidate_status',  '=', "1")
      //  ->where('cd.leader_status',  '!=', "2")
        ->where('cd.payroll_status',  '!=', "3")
        // ->orWhere('cd.po_finance_status',  '!=', "2")
       // ->orWhere('cd.po_file_status',  '!=', "1")
        ->orderBy('cd.updated_at', 'desc')


        ->groupBy('cd.cdID')
        ->get();

        return $mdlrecruitmenttbl;
    }

    public function get_candidate_edu_details( $input_details ){
        $mdleducationtbl = DB::table('candidate_education_details')
        ->select('*')
        ->where('cdID', '=', $input_details['cdID'])
        ->orderBy('updated_at', 'asc')
        ->get();

        return $mdleducationtbl;
    }

    public function get_candidate_exp_details( $input_details ){
        $mdlexperiencetbl = DB::table('candidate_experience_details')
        ->select('*')
        ->where('cdID', '=', $input_details['cdID'])
        ->orderBy('updated_at', 'desc')
        ->get();

        return $mdlexperiencetbl;
    }

    public function get_candidate_benefits_details( $input_details ){
        $mdlbenefitstbl = DB::table('candidate_benefits_details')
        ->select('*')
        ->where('cdID', '=', $input_details['cdID'])
        ->orderBy('updated_at', 'asc')
        ->get();

        return $mdlbenefitstbl;
    }

    public function process_cdoc_status( $input_details ){

        $update_mdlcdtbl = new Candidate_details();
        $update_mdlcdtbl = $update_mdlcdtbl->where( 'cdID', '=', $input_details['cdID'] );

        $update_mdlcdtbl->update( [
            'c_doc_status' => $input_details['c_doc_status']
        ] );
    }

    public function file_proof_attach( $input_details_f ){

        $update_mdlcdtbl = new Candidate_details();
        $update_mdlcdtbl = $update_mdlcdtbl->where( 'cdID', '=', $input_details_f['cdID'] );

        $update_mdlcdtbl->update( [

            'proof_of_attach' => $input_details_f['proof_attach'],

        ] );
    }
    public function process_orpop_status( $input_details ){

        $update_mdlcdtbl = new Candidate_details();
        $update_mdlcdtbl = $update_mdlcdtbl->where( 'cdID', '=', $input_details['cdID'] );

        $update_mdlcdtbl->update( [
            'or_cc_mailid' => $input_details['or_cc_mailid'],
            'or_bc_mailid' => $input_details['or_bc_mailid'],
            'or_doj' => $input_details['or_doj'],
            'closed_salary_pa' => $input_details['closed_salary_pa'],
            'get_emp_mode' => $input_details['get_emp_mode'],
            'welcome_buddy' => $input_details['welcome_buddy'],
            'offer_letter_filename' => $input_details['offer_letter_filename'],
            'payroll_status' => $input_details['payroll_status'],
            'po_finance_status' => $input_details['po_finance_status'],
            'leader_status' => $input_details['leader_status'],
            'or_department' => $input_details['or_department'],
            'last_drawn_ctc' => $input_details['last_drawn_ctc'],
            'register_type' => $input_details['register_type'],
            'po_type' => $input_details['po_type'],
            'or_recruiter_name' => $input_details['or_recruiter_name'],
            'or_recruiter_email' => $input_details['or_recruiter_email'],
            'or_recruiter_mobile_no' => $input_details['or_recruiter_mobile_no'],
            'approver' => $input_details['approver'],
            'esi_type' => $input_details['esi_type'],
            'attendance_format'=> $input_details['attendance_format'],
            'weak_off'=> $input_details['weak_off'],
            'payroll_status_ctc'=> $input_details['payroll_status_ctc'],
            'vertical'=> $input_details['vertical'],
            'onboarder'=> $input_details['onboarder'],
            'reviewer'=> $input_details['reviewer'],
            'primary_reporter'=> $input_details['primary_reporter'],
            'additional_reporter'=> $input_details['additional_reporter']
           // 'proof_of_attach' => $input_details['proof_of_attach'],

        ] );
    }
    public function insert_ctc_calc( $ctc_calculation_data ){

        $reqtbl = new Ctc_calculation();
        $reqtbl->ctcID = 'CTC'.str_pad( ( $reqtbl->max( 'id' )+1 ), 4, '0', STR_PAD_LEFT );
        $reqtbl->cdID = $ctc_calculation_data['cdID'];
        $reqtbl->basic_pm = $ctc_calculation_data['basic_pm'];
        $reqtbl->basic_pa = $ctc_calculation_data['basic_pa'];
        $reqtbl->hra_pm = $ctc_calculation_data['hra_pm'];
        $reqtbl->hra_pa = $ctc_calculation_data['hra_pa'];
        $reqtbl->medi_al_pm = $ctc_calculation_data['medi_al_pm'];
        $reqtbl->medi_al_pa = $ctc_calculation_data['medi_al_pa'];
        $reqtbl->conv_pm = $ctc_calculation_data['conv_pm'];
        $reqtbl->conv_pa = $ctc_calculation_data['conv_pa'];
        $reqtbl->spl_al_pm = $ctc_calculation_data['spl_al_pm'];
        $reqtbl->spl_al_pa = $ctc_calculation_data['spl_al_pa'];
        $reqtbl->comp_a_pm = $ctc_calculation_data['comp_a_pm'];
        $reqtbl->comp_a_pa = $ctc_calculation_data['comp_a_pa'];
        $reqtbl->ec_pf_pm = $ctc_calculation_data['ec_pf_pm'];
        $reqtbl->ec_pf_pa = $ctc_calculation_data['ec_pf_pa'];
        $reqtbl->ec_esi_pm = $ctc_calculation_data['ec_esi_pm'];
        $reqtbl->ec_esi_pa = $ctc_calculation_data['ec_esi_pa'];
        $reqtbl->sub_totalb_pm = $ctc_calculation_data['sub_totalb_pm'];
        $reqtbl->sub_totalb_pa = $ctc_calculation_data['sub_totalb_pa'];
        $reqtbl->gratuity_pm = $ctc_calculation_data['gratuity_pm'];
        $reqtbl->gratuity_pa = $ctc_calculation_data['gratuity_pa'];
        $reqtbl->st_bonus_pm = $ctc_calculation_data['st_bonus_pm'];
        $reqtbl->st_bonus_pa = $ctc_calculation_data['st_bonus_pa'];
        $reqtbl->sub_totalc_pm = $ctc_calculation_data['sub_totalc_pm'];
        $reqtbl->sub_totalc_pa = $ctc_calculation_data['sub_totalc_pa'];
        $reqtbl->abc_pm = $ctc_calculation_data['abc_pm'];
        $reqtbl->abc_pa = $ctc_calculation_data['abc_pa'];
        $reqtbl->net_pay = $ctc_calculation_data['net_pay'];
        $reqtbl->created_by = $ctc_calculation_data['created_by' ];
        $reqtbl->modified_by = $ctc_calculation_data['modified_by' ];

        $reqtbl->save();

        if($reqtbl) {
            return true;
        } else {
            return false;
        }
    }

    public function update_ctc_calc( $ctc_calculation_data ){
        $update_mdlcctbl = new Ctc_calculation();
        $update_mdlcctbl = $update_mdlcctbl->where( 'cdID', '=', $ctc_calculation_data['cdID'] );

        $update_mdlcctbl->update( [
            'basic_pm' => $ctc_calculation_data['basic_pm'],
            'basic_pa' => $ctc_calculation_data['basic_pa'],
            'hra_pm' => $ctc_calculation_data['hra_pm'],
            'hra_pa' => $ctc_calculation_data['hra_pa'],
            'medi_al_pm' => $ctc_calculation_data['medi_al_pm'],
            'medi_al_pa' => $ctc_calculation_data['medi_al_pa'],
            'conv_pm' => $ctc_calculation_data['conv_pm'],
            'conv_pa' => $ctc_calculation_data['conv_pa'],
            'spl_al_pm' => $ctc_calculation_data['spl_al_pm'],
            'spl_al_pa' => $ctc_calculation_data['spl_al_pa'],
            'comp_a_pm' => $ctc_calculation_data['comp_a_pm'],
            'comp_a_pa' => $ctc_calculation_data['comp_a_pa'],
            'ec_pf_pm' => $ctc_calculation_data['ec_pf_pm'],
            'ec_pf_pa' => $ctc_calculation_data['ec_pf_pa'],
            'ec_esi_pm' => $ctc_calculation_data['ec_esi_pm'],
            'ec_esi_pa' => $ctc_calculation_data['ec_esi_pa'],
            'sub_totalb_pm' => $ctc_calculation_data['sub_totalb_pm'],
            'sub_totalb_pa' => $ctc_calculation_data['sub_totalb_pa'],
            'gratuity_pm' => $ctc_calculation_data['gratuity_pm'],
            'gratuity_pa' => $ctc_calculation_data['gratuity_pa'],
            'st_bonus_pm' => $ctc_calculation_data['st_bonus_pm'],
            'st_bonus_pa' => $ctc_calculation_data['st_bonus_pa'],
            'sub_totalc_pm' => $ctc_calculation_data['sub_totalc_pm'],
            'sub_totalc_pa' => $ctc_calculation_data['sub_totalc_pa'],
            'abc_pm' => $ctc_calculation_data['abc_pm'],
            'abc_pa' => $ctc_calculation_data['abc_pa'],
            'net_pay' => $ctc_calculation_data['net_pay'],
            'modified_by' => $ctc_calculation_data['modified_by'],


        ] );
    }
    public function check_ctc_calc( $check_input_details ){

        $mdlrecruitmenttbl = DB::table('ctc_calculations as cc')
        ->select('cc.*')
        ->where('cc.cdID', '=', $check_input_details['cdID'])
        ->orderBy('cc.created_at', 'desc')
        ->count();

        return $mdlrecruitmenttbl;
    }

    public function get_offer_accepted_for_rr_dt( $input_details ){
        $mdlrecruitmenttbl = DB::table('candidate_details as cd')
        ->join('recruitment_requests as rr', 'cd.rfh_no', '=', 'rr.rfh_no', 'left outer')
        ->leftJoin('tat_details_tbls as tdt', 'rr.band', '=', 'tdt.id')

        ->select('cd.*','rr.position_title','rr.sub_position_title','tdt.band_title as band_title')
        ->where('cd.created_by', '=', $input_details['created_by'])
        ->where('cd.offer_rel_status', '=', $input_details['offer_rel_status'])
        ->where('rr.delete_status',  '=', "0")
        ->where('cd.candidate_status',  '=', "1")
        ->where('cd.onboard_status',  '=', "0")
        ->orderBy('cd.created_at', 'desc')
        ->groupBy('cd.cdID')
        ->get();

        return $mdlrecruitmenttbl;
    }
    public function get_approved_offers( $input_details ){

        $mdlrecruitmenttbl = DB::table('candidate_details as cd')
        ->join('recruitment_requests as rr', 'cd.rfh_no', '=', 'rr.rfh_no', 'left outer')
        ->leftJoin('tat_details_tbls as tdt', 'rr.band', '=', 'tdt.id')

        ->select('cd.*','rr.position_title','rr.sub_position_title','tdt.band_title as band_title')
        ->where('rr.delete_status',  '=', "0")
        ->where('cd.candidate_status',  '=', "1")
        ->where('cd.created_by', '=', $input_details['created_by'])
        ->where('cd.leader_status', '=', $input_details['leader_status'])
        ->where('cd.payroll_status', '=', $input_details['payroll_status'])
        ->where('cd.offer_rel_status', '=', $input_details['offer_rel_status'])
        ->orwhere('cd.offer_rel_status', '=', $input_details['offer_rel_status_or'])
        ->orderBy('cd.created_at', 'desc')
        ->groupBy('cd.cdID')
        ->get();

        return $mdlrecruitmenttbl;
    }

    public function offer_release_followup_entry( $or_followup_input ){

        $c_orfdtbl = new Offer_release_followup_details();
        $c_orfdtbl->orfID = 'ORF'.str_pad( ( $c_orfdtbl->max( 'id' )+1 ), 4, '0', STR_PAD_LEFT );
        $c_orfdtbl->cdID = $or_followup_input['cdID'];
        $c_orfdtbl->rfh_no = $or_followup_input['rfh_no'];
        $c_orfdtbl->hepl_recruitment_ref_number = $or_followup_input['hepl_recruitment_ref_number'];
        $c_orfdtbl->description = $or_followup_input['description'];
        $c_orfdtbl->created_by = $or_followup_input['created_by'];

        $c_orfdtbl->save();

        if($c_orfdtbl) {
            return true;
        } else {
            return false;
        }

    }
    public function customusers_entry( $insert_details ){

        $cus_us = new Customusers();
        $cus_us->empID = $insert_details['empID'];
        $cus_us->username = $insert_details['username'];
        $cus_us->passcode = $insert_details['passcode'];
        $cus_us->email = $insert_details['email'];
        $cus_us->role_type = $insert_details['role_type'];
        $cus_us->pre_onboarding = $insert_details['pre_onboarding'];
        $cus_us->active = $insert_details['active'];
        $cus_us->Induction_mail = $insert_details['Induction_mail'];
        $cus_us->Buddy_mail = $insert_details['Buddy_mail'];


        $cus_us->save();

        if($cus_us) {
            return true;
        } else {
            return false;
        }

    }
    public function get_candidate_rfh( $rfh ){

        $mdlcandidate_detailstbl = DB::table('tbl_rfh')
        ->select('*')
        ->where('res_id', '=', $rfh['rfh_no'])
        ->get();

        return $mdlcandidate_detailstbl;
    }
public function update_new_doj( $up_details ){
    $update_mdlrecruitreqtbl = new Candidate_details();
    $update_mdlrecruitreqtbl = $update_mdlrecruitreqtbl->where( 'cdID', '=', $up_details['cdID'] );


        $update_mdlrecruitreqtbl->update( [
            'onboard_status' => $up_details['onboard_status'],
            'or_doj' => $up_details['or_doj'],
            'status' => "Candidate Onboarded"

        ] );

        return  $update_mdlrecruitreqtbl;

}
}
