<?php

namespace App\Repositories;

use App\Models\recruitmentRequest;
use App\Models\User;
use App\Models\Candidate_details;
use App\Models\Offer_released_details;
use App\Models\Podetails;
use App\Models\Departments;

use DB;
class CoordinatorRepository implements ICoordinatorRepository
{
    public function reqcruitment_requestEntry( $form_credentials ){
        $reqtbl = new recruitmentRequest();
        $reqtbl->recReqID = 'RR'.str_pad( ( $reqtbl->max( 'id' )+1 ), 9, '0', STR_PAD_LEFT );
        $reqtbl->rfh_no = $form_credentials['rfh_no'];
        $reqtbl->position_title = $form_credentials['position_title'];
        $reqtbl->sub_position_title = $form_credentials['sub_position_title'];
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

    public function get_reqcruitment_request_default($advanced_filter){

    //     $mdlrecruitmenttbl = DB::table('recruitment_requests as rr')
    //     ->leftJoin('users as u', 'rr.assigned_to', '=', 'u.empID')
    //     ->leftJoin('tat_details_tbls as tdt', 'rr.band', '=', 'tdt.id')
    //     ->select('rr.*', 'u.name as recruiter_name','tdt.no_of_days as tat_days','tdt.band_title as band_title')
    //     ->where('rr.assigned_status', '=', $where_cond)
    //     ->orderBy('rr.id', 'desc')
    //     ->groupBy('rfh_no')
    //    ->get();

    //     return $mdlrecruitmenttbl;

        $mdlrecreqtbl = DB::table('recruitment_requests as rr');

        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'tbl_rfh as tr', 'rr.rfh_no', '=', 'tr.res_id' );
        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'users as u', 'rr.assigned_to', '=', 'u.empID' );
        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'tat_details_tbls as tdt', 'rr.band', '=', 'tdt.id' );
        $mdlrecreqtbl = $mdlrecreqtbl->select( 'rr.*','tr.name as raised_by', 'tr.approved_by as approved_by','rr.id as rr_id', 'u.name as recruiter_name','tdt.no_of_days as tat_days','tdt.band_title as band_title','tr.ticket_number','tr.location_preferred','tr.jd_roles','tr.qualification','tr.essential_skill','tr.good_skill','tr.experience','tr.any_specific','tr.mobile','tr.email' );

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

        if ($advanced_filter['af_band'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.band',  $advanced_filter['af_band']);
        }
        if ($advanced_filter['af_location'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.location',  $advanced_filter['af_location']);
        }
        if ($advanced_filter['af_business'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.business',  $advanced_filter['af_business']);
        }

        if ($advanced_filter['af_function'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.function',  $advanced_filter['af_function']);
        }
        if ($advanced_filter['af_division'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.division',  $advanced_filter['af_division']);
        }
        if ($advanced_filter['af_billable'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.billing_status',  $advanced_filter['af_billable']);
        }
        if ($advanced_filter['af_raisedby'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'tr.name',  $advanced_filter['af_raisedby']);
        }
        if ($advanced_filter['af_approvedby'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'tr.approved_by',  $advanced_filter['af_approvedby']);
        }
        $mdlrecreqtbl = $mdlrecreqtbl->where('rr.delete_status',  '=', "0");
        return $mdlrecreqtbl
        // ->orderBy( 'rr.id', 'desc' )
        ->groupBy('rfh_no');

    }
    public function get_reqcruitment_request_default_report(){
        $mdlrecruitmenttbl = DB::table('recruitment_requests as rr')
        ->leftJoin('tbl_rfh as tr', 'rr.rfh_no', '=', 'tr.res_id')
        // ->leftJoin('users as us', 'rr.closed_by', '=', 'us.empID')
        ->leftJoin('users as u', 'rr.assigned_to', '=', 'u.empID')
        ->leftJoin('tat_details_tbls as tdt', 'rr.band', '=', 'tdt.id')
        // ->select('rr.*','us.name as closed_by_name','tr.name as raised_by', 'tr.approved_by as approved_by','u.name as recruiter_name','tdt.no_of_days as tat_days','tdt.band_title as band_title')
        ->select('rr.*','tr.name as raised_by', 'tr.approved_by as approved_by','u.name as recruiter_name','tdt.no_of_days as tat_days','tdt.band_title as band_title')
        ->where('rr.delete_status',  '=', "0")
        // ->orderBy('rr.id', 'desc')
        ->groupBy('rr.hepl_recruitment_ref_number');
    //    ->result_array();

        return $mdlrecruitmenttbl;
    }
    public function get_recruitment_edit_details($input_details){
        $mdlrecruitmenttbl = DB::table('recruitment_requests as rr')
        ->select('rr.*')
        ->where('rr.rfh_no', '=', $input_details['rfh_no'])
        ->where('rr.delete_status',  '=', "0")
        ->orderBy('rr.created_at', 'desc')
        ->limit(1)
        ->get();

        return $mdlrecruitmenttbl;
    }

    public function get_tblrfh_details($input_details){
        $mdlrecruitmenttbl = DB::table('tbl_rfh as tr')
        ->select('tr.*','tdt.band_title')
        ->leftJoin( 'tat_details_tbls as tdt', 'tr.band', '=', 'tdt.id' )
        ->where('tr.res_id', '=', $input_details['rfh_no'])
        ->orderBy('tr.id', 'desc')
        ->limit(1)
        ->get();

        return $mdlrecruitmenttbl;
    }

    public function get_reqcruitment_request_afilter( $advanced_filter ){

        // print_r($advanced_filter);
        $mdlrecreqtbl = DB::table('recruitment_requests as rr');

        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'tbl_rfh as tr', 'rr.rfh_no', '=', 'tr.res_id' );
        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'users as u', 'rr.assigned_to', '=', 'u.empID' );
        // $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'users as us', 'rr.closed_by', '=', 'us.empID' );
        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'tat_details_tbls as tdt', 'rr.band', '=', 'tdt.id' );
        $mdlrecreqtbl = $mdlrecreqtbl->select( 'rr.*','tr.name as raised_by', 'tr.approved_by as approved_by','u.name as recruiter_name','tdt.no_of_days as tat_days','tdt.band_title as band_title' );

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
        if ($advanced_filter['af_closed_by'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.closed_by',  $advanced_filter['af_closed_by']);
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
        if ($advanced_filter['af_billable'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.billing_status',  $advanced_filter['af_billable']);
        }
        if ($advanced_filter['af_teams'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where('u.team', '=', $advanced_filter['af_teams']);

        }

        if ($advanced_filter['af_raisedby'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'tr.name',  $advanced_filter['af_raisedby']);
        }
        if ($advanced_filter['af_approvedby'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'tr.approved_by',  $advanced_filter['af_approvedby']);
        }

        $mdlrecreqtbl = $mdlrecreqtbl->where('rr.delete_status',  '=', "0");
        return $mdlrecreqtbl
        // ->orderBy( 'rr.id', 'desc' )
        ->groupBy('rr.hepl_recruitment_ref_number');
    }

    public function get_reqcruitment_request( $rfh_no){
        $mdlrecruitmenttbl = DB::table('recruitment_requests as rr')
        ->leftJoin('users as u', 'rr.assigned_to', '=', 'u.empID')
        ->leftJoin('tat_details_tbls as tdt', 'rr.band', '=', 'tdt.id' )

        // ->leftJoin('subcategory_tbls as sct', 'pt.subcategory_id', '=', 'sct.subcategory_id')
        // ->leftJoin( 'subcategorytwo_tbls as sctt', 'pt.subcategorytwo_id', '=', 'sctt.subcategorytwo_id' )
        ->select('rr.*', 'u.name as recruiter_name','tdt.band_title as band_title')
        ->where('rr.rfh_no', '=', $rfh_no)
        ->where('rr.delete_status',  '=', "0")
        ->orderBy('rr.id', 'desc')
        // ->groupBy('rfh_no')
       ->get();

        return $mdlrecruitmenttbl;
    }

    public function get_recruiter_list(){
        $mdlrecruitmenttbl = DB::table('users as u')
        ->select('u.*')
        ->where('u.role_type', '=', 'recruiter')
        ->where('u.profile_status', '=', 'Active')

        // ->where('u.role_type', '!=', 'super_admin')
        ->orderBy('u.id', 'desc')
        ->get();

        return $mdlrecruitmenttbl;
    }

    public function get_recruiter_list_all(){
        $mdlrecruitmenttbl = DB::table('users as u')
        ->select('u.*')
        ->where('u.profile_status', '=', 'Active')
        ->orderBy('u.id', 'desc')
        ->get();

        return $mdlrecruitmenttbl;
    }

    public function process_recruitment_assign( $credentials ){

        $update_mdlrecruitreqtbl = new recruitmentRequest();
        $update_mdlrecruitreqtbl = $update_mdlrecruitreqtbl->where( 'recReqID', '=', $credentials['recReqID'] );

        $update_mdlrecruitreqtbl->update( [
            'assigned_to' => $credentials['assigned_to'],
            'sub_position_title' => $credentials['sub_position_title'],
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
        ->where('rr.delete_status',  '=', "0")
        ->orderBy('rr.created_at', 'desc')
        ->limit(1)
        ->get();

        return $mdlrecruitmenttbl;
    }

    public function get_candidate_profile_all( $input_details ){
        $mdlrecruitmenttbl = DB::table('candidate_details as cd')
        ->join('recruitment_requests as rr', 'cd.hepl_recruitment_ref_number', '=', 'rr.hepl_recruitment_ref_number', 'left outer')
        ->leftjoin('users as u', 'cd.created_by', '=', 'u.empID')

        ->select('cd.*','rr.position_title','rr.sub_position_title','rr.business','u.name as created_name')
        // ->where('cd.status', '!=', $input_details['status'])
        ->where('rr.delete_status',  '=', "0")
        ->where('cd.candidate_status',  '=', "1")
        ->orderBy('cd.created_at', 'desc')
        ->groupBy('cd.cdID')
        ->get();

        return $mdlrecruitmenttbl;
    }
    public function get_closed_salary($input_details){
        $mdltat_detailstbl = DB::table('offer_released_details as ord')
        ->select('ord.closed_salary')
        ->where('ord.cdID',  '=', $input_details['cdID'])
        ->get();

        return $mdltat_detailstbl;
    }

    public function get_candidate_profile_all_af($advanced_filter){

        $mdlrecreqtbl = DB::table('candidate_details as cd');

        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'recruitment_requests as rr', 'cd.hepl_recruitment_ref_number', '=', 'rr.hepl_recruitment_ref_number' );
        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'users as u', 'cd.created_by', '=', 'u.empID' );
        $mdlrecreqtbl = $mdlrecreqtbl->select( 'cd.*','rr.position_title','rr.business','rr.sub_position_title','u.name as created_name' );

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
        if ($advanced_filter['af_created_by'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'cd.created_by',  $advanced_filter['af_created_by']);
        }

        // $mdlrecreqtbl = $mdlrecreqtbl->where( 'cd.status', '!=', $advanced_filter['status']);
        $mdlrecreqtbl = $mdlrecreqtbl->where('rr.delete_status',  '=', "0");
        $mdlrecreqtbl = $mdlrecreqtbl->where('cd.candidate_status',  '=', "1");
        return $mdlrecreqtbl
        ->orderBy( 'cd.created_at', 'desc' )->groupBy('cd.cdID')->get();
    }

    public function process_ticket_edit( $credentials ){

        $update_mdlrecruitreqtbl = new recruitmentRequest();
        $update_mdlrecruitreqtbl = $update_mdlrecruitreqtbl->where( 'rfh_no', '=', $credentials['rfh_no'] );

        if($credentials['request_status'] =='Re Open'){

            $update_mdlrecruitreqtbl->update( [
                'open_date' => date('Y-m-d'),
                'assigned_date' => date('Y-m-d'),
                'request_status' => $credentials['request_status']
            ] );

        }
        else{

            $update_mdlrecruitreqtbl->update( [
                'request_status' => $credentials['request_status']
            ] );

        }
    }

    public function process_ticket_delete( $input_details ){

        // DB::table('tbl_rfh')->where('res_id', $input_details['res_id'])->delete();

        $update = \DB::table('tbl_rfh') ->where('res_id', $input_details['res_id'])
         ->update( [
             'delete_status' => 1,
             'delete_remark' => $input_details['delete_remark'],
            ]);

        // DB::table('recruitment_requests')->where('rfh_no', $input_details['res_id'])->delete();

        $update_mdlrecruitreqtbl = new recruitmentRequest();
        $update_mdlrecruitreqtbl = $update_mdlrecruitreqtbl->where( 'rfh_no', '=', $input_details['res_id'] );

        $update_mdlrecruitreqtbl->update( [
            'delete_status' => 1
        ] );

    }

    public function process_recruiter_delete( $input_details ){

        $update_mdlusertbl = new User();
        $update_mdlusertbl = $update_mdlusertbl->where( 'empID', '=', $input_details['empID'] );

        $update_mdlusertbl->update( [
            'profile_status' => 'Inactive'
        ] );

        $update_mdlrecruitreqtbl = new recruitmentRequest();
        $update_mdlrecruitreqtbl = $update_mdlrecruitreqtbl->where( 'assigned_to', '=', $input_details['empID'] );
        $update_mdlrecruitreqtbl = $update_mdlrecruitreqtbl->where( 'request_status', '!=', 'Closed' );

        $update_mdlrecruitreqtbl->update( [
            'assigned_status' => 'Pending',
            'assigned_to' => '',
            'assigned_date' => ''
        ] );

        // DB::table('users')->where('empID', $input_details['empID'])->delete();


    }
    public function get_ticket_candidate_details( $input_details ){
        $mdlrecruitmenttbl = DB::table('candidate_details as cd')
        ->join('recruitment_requests as rr', 'cd.rfh_no', '=', 'rr.rfh_no', 'left outer')
        ->leftjoin('users as u', 'cd.created_by', '=', 'u.empID')

        ->select('cd.*','rr.position_title','rr.sub_position_title','u.name')
        ->where('cd.hepl_recruitment_ref_number', '=', $input_details['hepl_recruitment_ref_number'])
        ->where('rr.delete_status',  '=', "0")
        ->where('cd.candidate_status',  '=', "1")
        ->orderBy('cd.created_at', 'desc')
        ->groupBy('cd.cdID')
        ->get();



        return $mdlrecruitmenttbl;
    }
    public function get_ticket_candidate_details_af($advanced_filter ){
        $mdlrecreqtbl = DB::table('candidate_details as cd');

        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'recruitment_requests as rr', 'cd.rfh_no', '=', 'rr.rfh_no' );
        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'users as u', 'cd.created_by', '=', 'u.empID' );

        $mdlrecreqtbl = $mdlrecreqtbl->select( 'cd.*','rr.position_title','rr.sub_position_title','u.name' );

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
        $mdlrecreqtbl = $mdlrecreqtbl->where('rr.delete_status',  '=', "0");
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
        ->orderBy('rr.position_title', 'asc')
        ->groupBy('rr.position_title')
        ->get();

        return $get_position_title_details;
    }

    public function get_sub_position_title_af(){
        $get_sub_position_title_details = DB::table('recruitment_requests as rr')
        ->select('rr.sub_position_title')
        ->where('rr.sub_position_title','!=','')
        ->orderBy('rr.sub_position_title', 'asc')
        ->groupBy('rr.sub_position_title')
        ->get();

        return $get_sub_position_title_details;
    }

    public function get_location_af(){

        $get_location_af_details = DB::table('recruitment_requests as rr')
        ->select('rr.location')
        ->orderBy('rr.location', 'asc')
        ->groupBy('rr.location')
        ->get();

        return $get_location_af_details;
    }

    public function get_business_af(){

        $get_business_af_details = DB::table('recruitment_requests as rr')
        ->select('rr.business')
        ->orderBy('rr.business', 'asc')
        ->groupBy('rr.business')
        ->get();

        return $get_business_af_details;
    }

    public function get_function_af(){

        $get_function_af_details = DB::table('recruitment_requests as rr')
        ->select('rr.function')
        ->orderBy('rr.function', 'asc')
        ->groupBy('rr.function')
        ->get();

        return $get_function_af_details;
    }
    public function get_division_af(){

        $get_division_af_details = DB::table('recruitment_requests as rr')
        ->select('rr.division')
        ->where('rr.division', '!=','')
        ->orderBy('rr.division', 'asc')
        ->groupBy('rr.division')
        ->get();

        return $get_division_af_details;
    }

    public function get_raisedby_af(){
        $get_raisedby_af_details = DB::table('tbl_rfh as tr')
        ->select('tr.name')
        ->where('tr.name', '!=','')
        ->orderBy('tr.name', 'asc')
        ->groupBy('tr.name')
        ->get();

        return $get_raisedby_af_details;
    }

    public function get_raisedby_list(){
        $get_raisedby_list_details = DB::table('raisedby_lists as rl')
        ->select('rl.raised_by')
        ->where('rl.raised_by', '!=','')
        ->orderBy('rl.raised_by', 'asc')
        ->groupBy('rl.raised_by')
        ->get();

        return $get_raisedby_list_details;
    }
    public function get_approvedby_af(){
        $get_approvedby_af = DB::table('tbl_rfh as tr')
        ->select('tr.approved_by')
        ->where('tr.approved_by', '!=','')
        ->orderBy('tr.approved_by', 'asc')
        ->groupBy('tr.approved_by')
        ->get();

        return $get_approvedby_af;
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

    public function get_recruiter_team_list_af( $team ){
        $get_recruiter_team_list_af_details = DB::table('users as u')
        ->select('u.empID','u.name')
        ->where('u.role_type', '=', 'recruiter')
        ->where('u.team', '=', $team )
        ->orderBy('u.created_at', 'asc')
        ->get();

        return $get_recruiter_team_list_af_details;
    }

    public function get_source_list_af(  ){
        $get_candidate_source_af = DB::table('candidate_details as cd')
        ->select('cd.candidate_source')
        ->where('cd.candidate_source', '!=','')
        ->orderBy('cd.candidate_source', 'asc')
        ->groupBy('cd.candidate_source')
        ->get();

        return $get_candidate_source_af;
    }

    public function get_interviewer_af(){
        $get_interviewer_af_details = DB::table('recruitment_requests as rr')
        ->select('rr.interviewer')
        ->where('rr.interviewer', '!=','')
        ->orderBy('rr.interviewer', 'asc')
        ->groupBy('rr.interviewer')
        ->get();

        return $get_interviewer_af_details;
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
            'position_reports' => $form_credentials['position_reports'],
            'reporter_id' => $form_credentials['reporter_id'],
            'approved_by' => $form_credentials['approved_by'],
            'approver_id' => $form_credentials['approver_id'],
            'approved_by' => $form_credentials['approved_by'],
            'department' =>  $form_credentials[ 'department' ],
            'vertical' =>  $form_credentials[ 'vertical' ],
            'emp_category' =>  $form_credentials[ 'emp_category' ],
            'attendance_format' =>  $form_credentials[ 'attendance_format' ],
            'week_off' =>  $form_credentials[ 'week_off' ],
            'ck_supervisior' =>  $form_credentials[ 'ck_supervisior' ],
            'ck_mail' =>  $form_credentials[ 'ck_mail' ],
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
			'interviewer'=>$form_credentials['position_reports'],

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
        $usertbl->team = $form_credentials['team' ];
        $usertbl->color_code = $form_credentials['color_code' ];

        $usertbl->save();

        if($usertbl) {
            return true;
        } else {
            return false;
        }
    }

    public function get_ticket_report_recruiter_afilter( $advanced_filter ){

        $mdlrecreqtbl = DB::table('recruitment_requests as rr');

        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'tbl_rfh as tr', 'rr.rfh_no', '=', 'tr.res_id' );
        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'users as u', 'rr.assigned_to', '=', 'u.empID' );
        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'tat_details_tbls as tdt', 'rr.band', '=', 'tdt.id' );
        $mdlrecreqtbl = $mdlrecreqtbl->select( 'rr.*','tr.name as raised_by', 'tr.approved_by as approved_by','tdt.band_title as band_title', 'u.name as recruiter_name','tdt.no_of_days as tat_days' );

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

        if ($advanced_filter['assigned_to'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where('rr.assigned_to', '=', $advanced_filter['assigned_to']);

        }
        if ($advanced_filter['af_teams'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where('u.team', '=', $advanced_filter['af_teams']);

        }
        if ($advanced_filter['af_raisedby'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'tr.name',  $advanced_filter['af_raisedby']);
        }
        if ($advanced_filter['af_approvedby'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'tr.approved_by',  $advanced_filter['af_approvedby']);
        }

        $mdlrecreqtbl = $mdlrecreqtbl->where('rr.delete_status',  '=', "0");
        return $mdlrecreqtbl
        // ->orderBy( 'rr.id', 'desc' )
        ->groupBy('rr.hepl_recruitment_ref_number','rr.assigned_to');
        // ->orderBy( 'rr.id', 'desc' );

    }

    public function get_ticket_report_recruiter(  ){
        $mdlrecreqtbl = DB::table('recruitment_requests as rr');
        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'tbl_rfh as tr', 'rr.rfh_no', '=', 'tr.res_id' );

        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'users as u', 'rr.assigned_to', '=', 'u.empID' );
        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'tat_details_tbls as tdt', 'rr.band', '=', 'tdt.id' );
        $mdlrecreqtbl = $mdlrecreqtbl->select( 'rr.*','tr.name as raised_by', 'tr.approved_by as approved_by','tdt.band_title as band_title', 'u.name as recruiter_name','tdt.no_of_days as tat_days' );

        $mdlrecreqtbl = $mdlrecreqtbl->where('rr.assigned_status', '=', "Assigned");
        $mdlrecreqtbl = $mdlrecreqtbl->where('rr.delete_status',  '=', "0");

        return $mdlrecreqtbl
        // ->orderBy( 'rr.id', 'desc' )
        ->groupBy('rr.hepl_recruitment_ref_number','rr.assigned_to');
        // ->orderBy( 'rr.id', 'desc' );
    }
    public function recruiter_ageing_details($input_details){

        // print_r($input_details);
        $get_recruiter_list_af_details = DB::table('candidate_details as cd')
        ->select('*')
        ->where('cd.rfh_no', '=', $input_details['rfh_no'])
        ->where('cd.hepl_recruitment_ref_number', '=', $input_details['hepl_recruitment_ref_number'])
        ->where('cd.created_by', '=', $input_details['created_by'])
        ->where('cd.updated_at', '>', $input_details['check_date_1'])
        ->where('cd.updated_at', '<=', $input_details['check_date_2'])
        ->orderBy('cd.id', 'asc')
        ->get();

        return $get_recruiter_list_af_details;
    }

    public function recruiter_ageing_details_dr($input_details){

        $get_recruiter_list_af_details = DB::table('candidate_details as cd')
        ->select('*')
        ->where('cd.rfh_no', '=', $input_details['rfh_no'])
        ->where('cd.hepl_recruitment_ref_number', '=', $input_details['hepl_recruitment_ref_number'])
        ->where('cd.updated_at', '>', $input_details['check_date_1'])
        ->where('cd.updated_at', '<=', $input_details['check_date_2'])
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
        ->orderBy('cd.updated_at', 'desc')
        ->limit(1)
        ->get();

        return $get_recruiter_list_af_details;
    }

    public function recruiter_last_modified_date_dr($credentials){
        $get_recruiter_list_af_details = DB::table('candidate_details as cd')
        ->select('*')
        ->where('cd.rfh_no', '=', $credentials['rfh_no'])
        ->where('cd.hepl_recruitment_ref_number', '=', $credentials['hepl_recruitment_ref_number'])
        ->orderBy('cd.updated_at', 'desc')
        ->limit(1)
        ->get();

        return $get_recruiter_list_af_details;
    }
    public function get_assigned_recruiters($hepl_recruitment_ref_number){

        $mdlrecruitmenttbl = DB::table('recruitment_requests as rr')
        ->leftjoin('users as u', 'rr.assigned_to', '=', 'u.empID')
        ->select('u.name')
        ->where('rr.hepl_recruitment_ref_number', '=', $hepl_recruitment_ref_number)
        ->where('rr.delete_status',  '=', "0")
        ->orderBy('rr.created_at', 'desc')
        ->groupBy('rr.assigned_to')
        ->get();



        return $mdlrecruitmenttbl;
    }

    public function get_interviewer_self( $rfh_no ){

        $get_interviewer_self = DB::table('tbl_rfh')
        ->select('name','position_reports')
        ->where('res_id', '=', $rfh_no)
        ->orderBy('id', 'desc')
        ->get();

        return $get_interviewer_self;
    }

    public function get_cv_count($hepl_recruitment_ref_number){
        $mdlrecruitmenttbl = DB::table('candidate_details as cd')
        ->select('cd.*')
        ->where('cd.hepl_recruitment_ref_number', '=', $hepl_recruitment_ref_number)
        ->where('cd.candidate_status', '=', 1 )
        ->orderBy('cd.created_at', 'desc')
        ->count();

        return $mdlrecruitmenttbl;
    }

    public function get_recruiter_details( $input_details){
        $mdlrecruitmenttbl = DB::table('users as u')
        ->select('u.*')
        ->where('u.empID', '=', $input_details['empID'])
        ->orderBy('u.created_at', 'desc')
        ->get();

        return $mdlrecruitmenttbl;
    }

    public function update_recruiter_details( $input_details ){

        $update_mdlusertbl = new User();
        $update_mdlusertbl = $update_mdlusertbl->where( 'empID', '=', $input_details['empID'] );

        $update_mdlusertbl->update( [
            'name' => $input_details['name'],
            'designation' => $input_details['designation'],
            'email' => $input_details['email'],
            'team' => $input_details['team']
        ] );

    }

    public function get_offer_released_bc( $credentials){

        $mdlrecruitmenttbl = DB::table('offer_released_details as ord')
        ->leftJoin('candidate_details as cd', 'ord.cdID', '=', 'cd.cdID')
        ->leftJoin('recruitment_requests as rr', 'ord.hepl_recruitment_ref_number', '=', 'rr.hepl_recruitment_ref_number')
        ->select('ord.*','rr.id as rr_id','rr.position_title as position_title','cd.candidate_name as candidate_name', 'cd.candidate_cv as candidate_cv',)
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

    public function get_candidate_onborded_history( $credentials ){
        $mdlrecruitmenttbl = DB::table('candidate_details as cd')
        ->leftJoin('recruitment_requests as rr', 'cd.rfh_no', '=', 'rr.rfh_no')

        ->select('cd.*','rr.position_title','rr.sub_position_title')
        ->where('cd.status', '=', $credentials['status'])
        ->where('rr.delete_status',  '=', "0")

        ->orderBy('cd.created_at', 'desc')
        ->groupBy('rr.rfh_no')
        ->get();

        return $mdlrecruitmenttbl;
    }

    public function process_unassign( $input_details ){

        $update_mdlrecruitreqtbl =new recruitmentRequest();
        $update_mdlrecruitreqtbl = $update_mdlrecruitreqtbl->where( 'hepl_recruitment_ref_number', '=', $input_details['hepl_recruitment_ref_number'] );
        $update_mdlrecruitreqtbl = $update_mdlrecruitreqtbl->where( 'recReqID', '=', $input_details['recReqID'] );

        $update_mdlrecruitreqtbl->update( [
            'assigned_status' => $input_details['assigned_status'],
            'assigned_to' => "",

        ] );
    }

    public function get_recruitment_requests( $input_details ){
        $get_recruitment_requests = DB::table('recruitment_requests')
        ->select('*')
        ->where('rfh_no', '=', $input_details['rfh_no'])
        ->where('delete_status',  '=', "0")

        ->orderBy('id', 'desc')
        ->limit(1)
        ->get();

        return $get_recruitment_requests;
    }

    public function get_table_last_row($table){

        $get_recruitment_requests = DB::table($table)
        ->select('*')
        ->where('delete_status',  '=', "0")

        ->orderBy('id', 'desc')
        ->limit(1)
        ->get();

        return $get_recruitment_requests;

	}

    public function insert_data($form_credentials){

        $reqtbl = new recruitmentRequest();
        $reqtbl->recReqID = $form_credentials['recReqID'];
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
        $reqtbl->hepl_recruitment_ref_number = $form_credentials['hepl_recruitment_ref_number' ];

        $reqtbl->created_by = $form_credentials['created_by' ];
        $reqtbl->modified_by = $form_credentials['modified_by' ];
        $reqtbl->save();

        $update_mdlrecruitreqtbl =new recruitmentRequest();
        $update_mdlrecruitreqtbl = $update_mdlrecruitreqtbl->where( 'rfh_no', '=', $form_credentials['rfh_no'] );

        $update_mdlrecruitreqtbl->update( [
            'no_of_position' => $form_credentials['no_of_position'],

        ] );

        if($reqtbl) {
            return true;
        } else {
            return false;
        }
    }

    public function unassigned_count_nop( $input_details ){
        $get_recruitment_requests = DB::table('recruitment_requests')
        ->select('*')
        ->where('rfh_no', '=', $input_details['rfh_no'])
        ->where('request_status', '=', $input_details['request_status'])
        ->where('assigned_status', '=', $input_details['assigned_status'])
        ->where('delete_status',  '=', "0")

        ->orderBy('id', 'desc')
        ->count();

        return $get_recruitment_requests;
    }

    public function delete_unassigned_ticket( $input_details ){

        DB::table('recruitment_requests')
        ->where('rfh_no', $input_details['rfh_no'])
        ->where('request_status', $input_details['request_status'])
        ->where('assigned_status', $input_details['assigned_status'])
        ->where('delete_status',  '=', "0")
        ->delete();

        $update_mdlrecruitreqtbl =new recruitmentRequest();
        $update_mdlrecruitreqtbl = $update_mdlrecruitreqtbl->where( 'rfh_no', '=', $input_details['rfh_no'] );

        $update_mdlrecruitreqtbl->update( [
            'no_of_position' => $input_details['no_of_position'],

        ] );

    }

    public function delete_unassigned_ticket_el( $input_details ){

        DB::table('recruitment_requests')
        ->where('rfh_no', $input_details['rfh_no'])
        ->where('request_status', $input_details['request_status'])
        ->where('assigned_status', $input_details['assigned_status'])
        ->where('delete_status',  '=', "0")
        ->orderBy('id', 'desc')
        ->limit($input_details['limit_count'])
        ->delete();

        $update_mdlrecruitreqtbl =new recruitmentRequest();
        $update_mdlrecruitreqtbl = $update_mdlrecruitreqtbl->where( 'rfh_no', '=', $input_details['rfh_no'] );

        $update_mdlrecruitreqtbl->update( [
            'no_of_position' => $input_details['no_of_position'],

        ] );
    }


    public function get_deleted_request($advanced_filter){

        //     $mdlrecruitmenttbl = DB::table('recruitment_requests as rr')
        //     ->leftJoin('users as u', 'rr.assigned_to', '=', 'u.empID')
        //     ->leftJoin('tat_details_tbls as tdt', 'rr.band', '=', 'tdt.id')
        //     ->select('rr.*', 'u.name as recruiter_name','tdt.no_of_days as tat_days','tdt.band_title as band_title')
        //     ->where('rr.assigned_status', '=', $where_cond)
        //     ->orderBy('rr.id', 'desc')
        //     ->groupBy('rfh_no')
        //    ->get();

        //     return $mdlrecruitmenttbl;

            $mdlrecreqtbl = DB::table('recruitment_requests as rr');

            $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'users as u', 'rr.assigned_to', '=', 'u.empID' );
            $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'tbl_rfh as tr', 'rr.rfh_no', '=', 'tr.res_id' );
            $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'tat_details_tbls as tdt', 'rr.band', '=', 'tdt.id' );
            $mdlrecreqtbl = $mdlrecreqtbl->select( 'rr.*','tr.name as raised_by', 'tr.approved_by as approved_by','tr.delete_remark as delete_remark','rr.id as rr_id', 'u.name as recruiter_name','tdt.no_of_days as tat_days','tdt.band_title as band_title' );

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

            if ($advanced_filter['af_band'] != '') {
                $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.band',  $advanced_filter['af_band']);
            }
            if ($advanced_filter['af_location'] != '') {
                $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.location',  $advanced_filter['af_location']);
            }
            if ($advanced_filter['af_business'] != '') {
                $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.business',  $advanced_filter['af_business']);
            }

            if ($advanced_filter['af_function'] != '') {
                $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.function',  $advanced_filter['af_function']);
            }
            if ($advanced_filter['af_division'] != '') {
                $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.division',  $advanced_filter['af_division']);
            }
            if ($advanced_filter['af_billable'] != '') {
                $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.billing_status',  $advanced_filter['af_billable']);
            }
            if ($advanced_filter['af_raisedby'] != '') {
                $mdlrecreqtbl = $mdlrecreqtbl->where( 'tr.name',  $advanced_filter['af_raisedby']);
            }
            if ($advanced_filter['af_approvedby'] != '') {
                $mdlrecreqtbl = $mdlrecreqtbl->where( 'tr.approved_by',  $advanced_filter['af_approvedby']);
            }

            $mdlrecreqtbl = $mdlrecreqtbl->where('rr.delete_status',  '=', "1");
            return $mdlrecreqtbl
            ->orderBy( 'rr.id', 'desc' )->groupBy('rfh_no');

    }

    public function get_del_ticket_candidate_details_af($advanced_filter ){
        $mdlrecreqtbl = DB::table('candidate_details as cd');

        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'recruitment_requests as rr', 'cd.rfh_no', '=', 'rr.rfh_no' );
        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'users as u', 'cd.created_by', '=', 'u.empID' );

        $mdlrecreqtbl = $mdlrecreqtbl->select( 'cd.*','rr.position_title','rr.sub_position_title','u.name' );

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


        $mdlrecreqtbl = $mdlrecreqtbl->where('cd.rfh_no', '=', $advanced_filter['rfh_no']);
        // $mdlrecreqtbl = $mdlrecreqtbl->where('rr.delete_status',  '=', "0");
        return $mdlrecreqtbl
        ->orderBy( 'cd.created_at', 'desc' )->groupBy('cd.cdID')->get();
    }

    public function get_del_ticket_candidate_details( $input_details ){
        $mdlrecruitmenttbl = DB::table('candidate_details as cd')
        ->join('recruitment_requests as rr', 'cd.rfh_no', '=', 'rr.rfh_no', 'left outer')
        ->leftjoin('users as u', 'cd.created_by', '=', 'u.empID')

        ->select('cd.*','rr.position_title','rr.sub_position_title','u.name')
        ->where('cd.rfh_no', '=', $input_details['rfh_no'])
        // ->where('rr.delete_status',  '=', "0")
        ->orderBy('cd.created_at', 'desc')
        ->groupBy('cd.cdID')
        ->get();

        return $mdlrecruitmenttbl;
    }


    public function update_sub_position_title( $credentials ){
        $update_mdlrecruitreqtbl = new recruitmentRequest();
        $update_mdlrecruitreqtbl = $update_mdlrecruitreqtbl->where( 'hepl_recruitment_ref_number', '=', $credentials['hepl_recruitment_ref_number'] );

        $update_mdlrecruitreqtbl->update( [
            'sub_position_title' => $credentials['sub_position_title'],
        ] );
    }

    public function process_hepldelete($input_details){

        DB::table('recruitment_requests')
        ->where('recReqID', $input_details['recReqID'])
        ->where('hepl_recruitment_ref_number', $input_details['hepl_recruitment_ref_number'])
        ->delete();

    }

    public function get_cv_count_rr($input_details){
        $mdlrecruitmenttbl = DB::table('candidate_details as cd')
        ->select('cd.*')
        ->where('cd.hepl_recruitment_ref_number', '=', $input_details['hepl_recruitment_ref_number'])
        ->where('cd.created_by', '=', $input_details['created_by'])
        ->orderBy('cd.created_at', 'desc')
        ->count();

        return $mdlrecruitmenttbl;
    }
    public function get_cv_count_dr($input_details){
        $mdlrecruitmenttbl = DB::table('candidate_details as cd')
        ->select('cd.*')
        ->where('cd.hepl_recruitment_ref_number', '=', $input_details['hepl_recruitment_ref_number'])
        ->orderBy('cd.created_at', 'desc')
        ->count();

        return $mdlrecruitmenttbl;
    }

    public function check_recruiter_already_assigned( $check_input_details ){
        $mdlrecruitmenttbl = DB::table('recruitment_requests as rr')
        ->select('rr.*')
        ->where('rr.hepl_recruitment_ref_number', '=', $check_input_details['hepl_recruitment_ref_number'])
        ->where('rr.assigned_to', '=', $check_input_details['assigned_to'])
        ->orderBy('rr.created_at', 'desc')
        ->count();

        return $mdlrecruitmenttbl;
    }

    public function get_position_closed_count( $credentials ){
        $mdlrecruitmenttbl = DB::table('recruitment_requests as rr')
        ->select('rr.*')
        ->where('rr.rfh_no', '=', $credentials['rfh_no'])
        ->where('rr.request_status', '=', $credentials['request_status'])
        ->groupBy('rr.hepl_recruitment_ref_number')
        ->get();

        return $mdlrecruitmenttbl;
    }

    public function get_closed_position_details( $input_details ){



        $mdlrecruitmenttbl = DB::table('recruitment_requests as rr')
        ->select('rr.hepl_recruitment_ref_number','rr.open_date','rr.position_title','rr.sub_position_title',
        'cd.candidate_name','cd.gender','cd.candidate_source','ord.date_of_joining',
        'rr.location','rr.business','tdt.band_title as band',
        'tr.name as request_raised_by','rr.billing_status','rr.close_date','ord.closed_salary','rr.request_status','rr.function','rr.division','rr.assigned_to','rr.closed_by')

        ->leftJoin('candidate_details as cd', function($query) {

            // $query->selectRaw('MAX(a2.id)')
            // ->from('candidate_details as a2')
            // ->join('recruitment_requests as rr2','rr2.hepl_recruitment_ref_number', '=', 'a2.hepl_recruitment_ref_number')
            // ->whereRaw('cd.hepl_recruitment_ref_number', '=', 'rr.hepl_recruitment_ref_number')
            // ->where('a2.status', '=', 'Candidate Onboarded')
            // ->groupBy('rr2.hepl_recruitment_ref_number');

            $query->on('cd.hepl_recruitment_ref_number','=','rr.hepl_recruitment_ref_number')

                ->whereRaw("cd.id IN (select MAX(a2.id) from candidate_details as a2 join recruitment_requests as rr2 on rr2.hepl_recruitment_ref_number = a2.hepl_recruitment_ref_number where a2.status = 'Candidate Onboarded' or a2.status = 'Offer Accepted' or a2.status = 'Offer Released' group by rr2.hepl_recruitment_ref_number)");
        })

        // ->leftjoin('candidate_details as cd', 'rr.hepl_recruitment_ref_number', '=', 'cd.hepl_recruitment_ref_number')
        ->leftjoin('offer_released_details as ord', 'cd.cdID', '=', 'ord.cdID')
        ->leftjoin('users as u', 'rr.assigned_to', '=', 'u.empID')
        ->leftjoin('tbl_rfh as tr', 'rr.rfh_no', '=', 'tr.res_id')
        ->leftjoin('tat_details_tbls as tdt', 'rr.band', '=', 'tdt.id')
        // ->leftJoin( 'users as us', 'rr.closed_by', '=', 'us.empID' )

        ->where('rr.request_status', '=', $input_details['request_status'])
        ->where('rr.delete_status',  '=', "0")
        ->groupBy('rr.hepl_recruitment_ref_number')
        ->orderBy('rr.close_date', 'desc')
        ->orderBy('cd.id', 'desc')
        ->get();

        return $mdlrecruitmenttbl;
    }

    public function get_closed_position_details_af( $advanced_filter ){
        $mdlrecreqtbl = DB::table('recruitment_requests as rr');
        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin('candidate_details as cd', function($query) {
            $query->on('cd.hepl_recruitment_ref_number','=','rr.hepl_recruitment_ref_number')
                ->whereRaw("cd.id IN (select MAX(a2.id) from candidate_details as a2 join recruitment_requests as rr2 on rr2.hepl_recruitment_ref_number = a2.hepl_recruitment_ref_number where a2.status = 'Candidate Onboarded' or a2.status = 'Offer Accepted' or a2.status = 'Offer Released' group by rr2.hepl_recruitment_ref_number)");
        });
        // $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'candidate_details as cd', 'rr.hepl_recruitment_ref_number', '=', 'cd.hepl_recruitment_ref_number' );
        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'offer_released_details as ord', 'cd.cdID', '=', 'ord.cdID' );
        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'users as u', 'rr.assigned_to', '=', 'u.empID' );
        // $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'users as us', 'rr.closed_by', '=', 'us.empID' );
        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'tbl_rfh as tr', 'rr.rfh_no', '=', 'tr.res_id' );
        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'tat_details_tbls as tdt', 'rr.band', '=', 'tdt.id' );

        $mdlrecreqtbl = $mdlrecreqtbl->select( 'rr.hepl_recruitment_ref_number','rr.open_date','rr.position_title','rr.sub_position_title',
        'cd.candidate_name','cd.gender','cd.candidate_source','ord.date_of_joining',
        'rr.location','rr.business','tdt.band_title as band',
        'tr.name as request_raised_by','rr.billing_status','rr.close_date','ord.closed_salary','rr.request_status','rr.function','rr.division','rr.assigned_to','rr.closed_by' );

        if($advanced_filter['afc_from_date'] !=''  && $advanced_filter['afc_to_date'] !=''){

            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.close_date', '>=', $advanced_filter['afc_from_date'] );
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.close_date', '<=', $advanced_filter['afc_to_date']);
        }

        if($advanced_filter['afc_from_date'] !=''  && $advanced_filter['afc_to_date'] ==''){

            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.close_date', '=', $advanced_filter['afc_from_date'] );
        }

        if($advanced_filter['afc_from_date'] ==''  && $advanced_filter['afc_to_date'] !=''){

            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.close_date', '=', $advanced_filter['afc_to_date'] );

        }

        if($advanced_filter['afc_doj_from_date'] !=''  && $advanced_filter['afc_doj_to_date'] !=''){

            $mdlrecreqtbl = $mdlrecreqtbl->where( 'ord.date_of_joining', '>=', $advanced_filter['afc_doj_from_date'] );
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'ord.date_of_joining', '<=', $advanced_filter['afc_doj_to_date']);
        }

        if($advanced_filter['afc_doj_from_date'] !=''  && $advanced_filter['afc_doj_to_date'] ==''){

            $mdlrecreqtbl = $mdlrecreqtbl->where( 'ord.date_of_joining', '=', $advanced_filter['afc_doj_from_date'] );
        }

        if($advanced_filter['afc_doj_from_date'] ==''  && $advanced_filter['afc_doj_to_date'] !=''){

            $mdlrecreqtbl = $mdlrecreqtbl->where( 'ord.date_of_joining', '=', $advanced_filter['afc_doj_to_date'] );

        }
        if ($advanced_filter['afc_position_title'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.position_title',  $advanced_filter['afc_position_title']);
        }
        if ($advanced_filter['afc_sub_position_title'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.sub_position_title',  $advanced_filter['afc_sub_position_title']);
        }

        if ($advanced_filter['afc_source'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'cd.candidate_source',  $advanced_filter['afc_source']);
        }

        if ($advanced_filter['afc_location'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.location',  $advanced_filter['afc_location']);
        }

        if ($advanced_filter['afc_business'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.business',  $advanced_filter['afc_business']);
        }

        if ($advanced_filter['afc_band'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.band',  $advanced_filter['afc_band']);
        }

        if ($advanced_filter['afc_created_by'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.closed_by',  $advanced_filter['afc_created_by']);
        }
        if ($advanced_filter['afc_teams'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'u.team',  $advanced_filter['afc_teams']);
        }
        if ($advanced_filter['afc_raisedby'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'tr.name',  $advanced_filter['afc_raisedby']);
        }

        if ($advanced_filter['afc_billable'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.billing_status',  $advanced_filter['afc_billable']);
        }
        if ($advanced_filter['afc_function'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.function',  $advanced_filter['afc_function']);
        }
        if ($advanced_filter['afc_division'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.division',  $advanced_filter['afc_division']);
        }

        $mdlrecreqtbl = $mdlrecreqtbl->where('rr.request_status', '=', 'Closed');
        $mdlrecreqtbl = $mdlrecreqtbl->where('rr.delete_status',  '=', "0");
        return $mdlrecreqtbl
        ->orderBy( 'rr.close_date', 'desc' )->groupBy('rr.hepl_recruitment_ref_number')->get();
    }

    public function get_current_status_rr( $input_details_cc ){
        $mdlrecruitmenttbl = DB::table('candidate_details as cd')
        ->select('cd.*')
        ->where('cd.hepl_recruitment_ref_number', '=', $input_details_cc['hepl_recruitment_ref_number'])

        ->orderBy('cd.updated_at', 'desc')
        ->limit(1)
        ->get();

        return $mdlrecruitmenttbl;
    }
    public function get_current_status_recruiter( $input_details_cc ){
        // echo "<pre>";
        // print_r($input_details_cc);
        $mdlrecruitmenttbl = DB::table('candidate_details as cd')
        ->select('cd.*')
        ->where('cd.hepl_recruitment_ref_number', '=', $input_details_cc['hepl_recruitment_ref_number'])
        ->where('cd.created_by', '=', $input_details_cc['created_by'])
        ->orderBy('cd.updated_at', 'desc')
        ->limit(1)
        ->get();

        return $mdlrecruitmenttbl;
    }
        public function get_assigned_date_closed_report( $input_details ){
            $mdlrecruitmenttbl = DB::table('recruitment_requests')
            ->select('assigned_date')
            ->where('hepl_recruitment_ref_number', '=', $input_details['hepl_recruitment_ref_number'])
            ->where('assigned_to', '=', $input_details['closed_by'])
            ->where('delete_status',  '=', "0")
            ->limit(1)
            ->get();

        return $mdlrecruitmenttbl;
        }
    public function get_open_position_details( $input_details ){

        $mdlrecruitmenttbl = DB::table('recruitment_requests as rr');
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->leftJoin( 'users as u', 'rr.assigned_to', '=', 'u.empID' );
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->leftJoin( 'tbl_rfh as tr', 'rr.rfh_no', '=', 'tr.res_id' );
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->select('rr.rfh_no','rr.hepl_recruitment_ref_number','rr.position_title','rr.sub_position_title','rr.open_date',
        'rr.location','rr.business','tr.name as request_raised_by','rr.interviewer','rr.assigned_to','rr.assigned_date',
        'rr.billing_status','rr.function','rr.division','rr.salary_range');

        $mdlrecruitmenttbl = $mdlrecruitmenttbl->leftjoin('users as u', 'rr.assigned_to', '=', 'u.empID');
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->leftjoin('tbl_rfh as tr', 'rr.rfh_no', '=', 'tr.res_id');
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('rr.request_status', '!=', $input_details['request_status_1']);
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('rr.request_status', '!=', $input_details['request_status_2']);
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->where( 'u.team', '=',  "HEPL");
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('rr.delete_status',  '=', "0");
       // $mdlrecruitmenttbl = $mdlrecruitmenttbl->groupBy('rr.hepl_recruitment_ref_number');
        //$mdlrecruitmenttbl = $mdlrecruitmenttbl->orderBy('rr.hepl_recruitment_ref_number', 'desc');
       // $mdlrecruitmenttbl = $mdlrecruitmenttbl->get();

       // return $mdlrecruitmenttbl;
       return $mdlrecruitmenttbl
       ->orderBy( 'rr.hepl_recruitment_ref_number', 'desc' )->groupBy('rr.hepl_recruitment_ref_number')->get();
    }


    public function get_open_position_details_af( $advanced_filter ){

        $mdlrecreqtbl = DB::table('recruitment_requests as rr');

        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'users as u', 'rr.assigned_to', '=', 'u.empID' );
        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'tbl_rfh as tr', 'rr.rfh_no', '=', 'tr.res_id' );

        $mdlrecreqtbl = $mdlrecreqtbl->select( 'rr.rfh_no','rr.hepl_recruitment_ref_number','rr.position_title','rr.sub_position_title','rr.open_date',
        'rr.location','rr.business','tr.name as request_raised_by','rr.interviewer','rr.assigned_to','rr.assigned_date',
        'rr.billing_status','rr.function','rr.division','rr.salary_range' );

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
        if ($advanced_filter['af_sub_position_title'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.sub_position_title',  $advanced_filter['af_sub_position_title']);
        }

        if ($advanced_filter['af_location'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.location',  $advanced_filter['af_location']);
        }

        if ($advanced_filter['af_business'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.business',  $advanced_filter['af_business']);
        }

        if ($advanced_filter['af_teams'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'u.team',  $advanced_filter['af_teams']);
        }
        else{
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'u.team',  'HEPL');
        }

        if ($advanced_filter['af_raisedby'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'tr.name',  $advanced_filter['af_raisedby']);
        }

        if ($advanced_filter['af_interviewer'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.interviewer',  $advanced_filter['af_interviewer']);
        }

        if ($advanced_filter['af_billable'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.billing_status',  $advanced_filter['af_billable']);
        }
        if ($advanced_filter['af_function'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.function',  $advanced_filter['af_function']);
        }
        if ($advanced_filter['af_division'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.division',  $advanced_filter['af_division']);
        }

        $mdlrecreqtbl = $mdlrecreqtbl->where('rr.request_status', '!=', $advanced_filter['request_status_1']);
        $mdlrecreqtbl = $mdlrecreqtbl->where('rr.request_status', '!=', $advanced_filter['request_status_2']);
        $mdlrecreqtbl = $mdlrecreqtbl->where('rr.delete_status',  '=', "0");
        return $mdlrecreqtbl
        ->orderBy( 'rr.hepl_recruitment_ref_number', 'desc' )->groupBy('rr.hepl_recruitment_ref_number')->get();
    }

    public function process_candidate_delete($input_details){

        $update_mdlcdtbl = new Candidate_details();
        $update_mdlcdtbl = $update_mdlcdtbl->where( 'cdID', '=', $input_details['cdID'] );

        $update_mdlcdtbl->update( [
            'candidate_status' => '0'
        ] );
    }

    public function get_candidate_details_ed( $input_details ){

        $mdlcandidate_detailstbl = DB::table('candidate_details as cd')
        ->select('cd.*')
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

    public function get_approval_for_hire( $rfh_no ){
        $get_approval_for_hire = DB::table('tbl_rfh')
        ->select('approval_hire','ticket_number','approval_hire_path')
        ->where('res_id', '=', $rfh_no)
        ->orderBy('id', 'desc')
        ->get();

        return $get_approval_for_hire;
    }

    public function update_closed_salary_bc($input_details){
        $update_mdl_ord_tbl = new Offer_released_details();
        $update_mdl_ord_tbl = $update_mdl_ord_tbl->where( 'rfh_no', '=', $input_details['rfh_no'] );
        $update_mdl_ord_tbl = $update_mdl_ord_tbl->where( 'cdID', '=', $input_details['cdID'] );

        $update_mdl_ord_tbl->update( [
            'closed_salary' => $input_details['closed_salary']

        ] );
    }

    public function get_closed_by_name( $hepl_recruitment_ref_number ){
        $mdlrecruitmenttbl = DB::table('recruitment_requests as rr')
        ->select('rr.*','us.name as closed_by_name')
        ->leftJoin('users as us', 'rr.closed_by', '=', 'us.empID')
        ->where('rr.hepl_recruitment_ref_number', '=', $hepl_recruitment_ref_number)
        ->where('rr.request_status', '=',"Closed")
        ->groupBy('rr.hepl_recruitment_ref_number')
        ->limit(1)
        ->get();

        return $mdlrecruitmenttbl;
    }

    public function get_buddylist(){
        $get_buddy_details = DB::table('users as u')
        ->select('u.*')
        ->where('u.role_type', '=', 'buddy')
        ->orderBy('u.created_at', 'asc')
        ->get();

        return $get_buddy_details;
    }
    public function get_department(){
        $get_depart_details = DB::table('departments as d')
        ->select('d.*')
        ->orderBy('d.created_at', 'asc')
        ->get();

        return $get_depart_details;
    }

    public function get_buddy_details( $input_details_bi ){
        $get_buddy_details = DB::table('users as u')
        ->select('u.*')
        ->where('u.role_type', '=', 'buddy')
        ->where('u.empID', '=', $input_details_bi['buddy_id'])
        ->orderBy('u.created_at', 'asc')
        ->get();

        return $get_buddy_details;
    }



    public function get_approved_offers( $input_details ){
        $mdlrecruitmenttbl = DB::table('candidate_details as cd')
        ->join('recruitment_requests as rr', 'cd.rfh_no', '=', 'rr.rfh_no', 'left outer')
        ->leftJoin('tat_details_tbls as tdt', 'rr.band', '=', 'tdt.id')

        ->select('cd.*','rr.position_title','rr.sub_position_title','tdt.band_title as band_title')
        ->where('rr.delete_status',  '=', "0")
        ->where('cd.candidate_status',  '=', "1")
        ->where('cd.leader_status', '=', $input_details['leader_status'])
        ->where('cd.payroll_status', '=', $input_details['payroll_status'])
        ->where('cd.offer_rel_status', '=', $input_details['offer_rel_status'])
        ->orwhere('cd.offer_rel_status', '=', $input_details['offer_rel_status_or'])
        ->orderBy('cd.created_at', 'desc')
        ->groupBy('cd.cdID')
        ->get();

        return $mdlrecruitmenttbl;
    }

    public function get_offer_accepted_for_bc_dt( $input_details ){
        $mdlrecruitmenttbl = DB::table('candidate_details as cd')
        ->join('recruitment_requests as rr', 'cd.rfh_no', '=', 'rr.rfh_no', 'left outer')
        ->leftJoin('tat_details_tbls as tdt', 'rr.band', '=', 'tdt.id')

        ->select('cd.*','rr.position_title','rr.sub_position_title','tdt.band_title as band_title')
        ->where('cd.offer_rel_status', '=', $input_details['offer_rel_status'])
        ->where('rr.delete_status',  '=', "0")
        ->where('cd.candidate_status',  '=', "1")
        ->orderBy('cd.created_at', 'desc')
        ->groupBy('cd.cdID')
        ->get();

        return $mdlrecruitmenttbl;
    }

    public function get_candidate_profile_dc_bc( $input_details ){
        $mdlrecruitmenttbl = DB::table('candidate_details as cd')
        ->join('recruitment_requests as rr', 'cd.rfh_no', '=', 'rr.rfh_no', 'left outer')

        ->select('cd.*','rr.position_title','rr.sub_position_title')
        ->where('cd.doc_status', '=', $input_details['doc_status'])
        ->where('rr.delete_status',  '=', "0")
        ->where('cd.candidate_status',  '=', "1")
     //   ->where('cd.leader_status',  '!=', "3")
     ->where('cd.payroll_status',  '!=', "3")
        ->orderBy('cd.created_at', 'desc')
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

    public function get_user_details($input_details_ad){
        $mdl_users_tbl = DB::table('users')
        ->select('*')
        ->where('empID', '=', $input_details_ad['empID'])
        ->orderBy('updated_at', 'asc')
        ->get();

        return $mdl_users_tbl;
    }
    public function get_user_recruiter(){
        $mdlrecruitmenttbl = DB::table('users as us')
        ->select('us.*')
        ->where('us.role_type',  '=', "Recruiter")
        ->where('us.profile_status',  '=', "Active")
        ->where('us.team',  '=', "HEPL")
        ->get();

        return $mdlrecruitmenttbl;
    }
    public function get_user_recruiter_count(){
        $mdlrecruitmenttbl = DB::table('users as us')
        ->select('us.*')
        ->where('us.role_type',  '=', "Recruiter")
        ->where('us.profile_status',  '=', "Active")
        ->where('us.team',  '=', "HEPL")
        ->count();
        return $mdlrecruitmenttbl;
    }
    public function get_recruiter_position($recruiter_id){
        // $mdlrecruitmenttbl = DB::table('recruitment_requests')
        // ->select('*')
        // ->where('assigned_to',  '=', $recruiter_id)
        // ->whereDate('created_at',  '=', date('Y-m-d'))
        // ->count();
        // return $mdlrecruitmenttbl;
        $mdlrecruitmenttbl = DB::table('candidate_details as cd')
        ->join('recruitment_requests as rr', 'cd.hepl_recruitment_ref_number', '=', 'rr.hepl_recruitment_ref_number', 'left outer')
        ->select('cd.*','rr.position_title','rr.sub_position_title')
        ->where('cd.created_by',  '=', $recruiter_id)
        ->where('rr.assigned_to',  '=', $recruiter_id)
        ->whereDate('cd.created_at',  '=', date('Y-m-d'))
        ->groupBy('rr.hepl_recruitment_ref_number')
        ->count();
        return $mdlrecruitmenttbl;
    }
    public function get_recruiter_interviews($recruiter_id){
        $mdlrecruitmenttbl = Candidate_details::where('created_by',$recruiter_id)
        ->where('status', 'Interview scheduled with Hiring Manager')
        ->where('candidate_status',  '=', 1)
        ->where(function($query) {
			$query->whereDate('updated_at',date('Y-m-d'));
					//	->orWhere('created_on',date('Y-m-d'));
        })->count();
        return $mdlrecruitmenttbl;
    }

    public function get_recruiter_offers($recruiter_id){
        $mdlrecruitmenttbl = DB::table('offer_released_details as or');
        $mdlrecruitmenttbl =  $mdlrecruitmenttbl->join('candidate_details as cd', 'cd.cdID', '=', 'or.cdID', 'left outer');
        $mdlrecruitmenttbl =  $mdlrecruitmenttbl->select('or*');
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('or.created_by',  '=', $recruiter_id);
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('or.profile_status',  '=', 'Offer Released');
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->whereDate('or.created_at',date('Y-m-d'));
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('cd.candidate_status',  '=', 1);
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->count();
        return $mdlrecruitmenttbl;

    }
    public function get_time_data( $data ){
        $mdlrecruitmenttbl = DB::table('candidate_details')
        ->select('*')
        ->where('created_by',  '=', $data['recruiter'])
        ->whereDate('created_at',  '=', $data['date'])
        ->whereTime('created_at',  '>=', $data['from_time'])
        ->whereTime('created_at',  '<=', $data['to_time'])
        ->where('candidate_status',  '=', '1')
        ->count();
        return $mdlrecruitmenttbl;
    }
    public function get_user_recruiter_id( $data ){
        $mdlrecruitmenttbl = DB::table('users as us')
        ->select('us.*')
        ->where('us.empID',  '=', $data['recruiter'])
        ->where('us.profile_status',  '=', "Active")
        ->get();
        return $mdlrecruitmenttbl;
    }
public function get_user_recruiter_filter(){
    $mdlrecruitmenttbl = DB::table('candidate_details')
    ->select('*')
    ->where('created_by',  '=', $data['recruiter'])
    ->whereDate('created_at',  '>=', $data['from_date'])
    ->whereDate('created_at',  '<=', $data['to_date'])
  //  ->whereTime('created_at',  '<=', $data['to_time'])
    ->count();
    return $mdlrecruitmenttbl;
}
public function get_recruiter_position_filter( $data ){
    $mdlrecruitmenttbl = DB::table('candidate_details as cd');
    $mdlrecruitmenttbl =  $mdlrecruitmenttbl->join('recruitment_requests as rr', 'cd.hepl_recruitment_ref_number', '=', 'rr.hepl_recruitment_ref_number', 'left outer');
    $mdlrecruitmenttbl =  $mdlrecruitmenttbl->select('cd.*','rr.position_title','rr.sub_position_title');
    $mdlrecruitmenttbl =  $mdlrecruitmenttbl->where('cd.created_by',  '=', $data['recruiter']);
    $mdlrecruitmenttbl =  $mdlrecruitmenttbl->where('rr.assigned_to',  '=', $data['recruiter']);
    if($data['from_date'] != null && $data['to_date'] != ""){
            $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('cd.created_at',  '>=', $data['from_date']);
            $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('cd.created_at',  '<=', $data['to_date']);
            }
            if($data['from_date'] != "" && $data['to_date'] == "" ){
                $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('cd.created_at',  '=', $data['from_date']);
               }
               if($data['from_date'] == "" && $data['to_date'] !== ""){
               $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('cd.created_at',  '=', $data['to_date']);
               }
   // $mdlrecruitmenttbl =  $mdlrecruitmenttbl->whereDate('cd.created_at',  '=', $data['date']);
    $mdlrecruitmenttbl =  $mdlrecruitmenttbl->groupBy('rr.hepl_recruitment_ref_number');
    $mdlrecruitmenttbl =  $mdlrecruitmenttbl->count();
    return $mdlrecruitmenttbl;
//     $mdlrecruitmenttbl = DB::table('recruitment_requests');
//     $mdlrecruitmenttbl = $mdlrecruitmenttbl->select('*');
//     $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('assigned_to',  '=', $data['recruiter']);
//     if($data['from_date'] != "" && $data['to_date'] != ""){
//     $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('created_at',  '>=', $data['from_date']);
//     $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('created_at',  '<=', $data['to_date']);
//     }
//     if($data['from_date'] != "" && $data['to_date'] == "" ){
//         $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('created_at',  '=', $data['from_date']);
//        }
//        if($data['from_date'] == "" && $data['to_date'] !== ""){
//        $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('created_at',  '=', $data['to_date']);
//        }
//     $mdlrecruitmenttbl =$mdlrecruitmenttbl->count();
//     return $mdlrecruitmenttbl;
}

public function get_recruiter_interviews_filter( $data ){
    $mdlrecruitmenttbl = DB::table('candidate_details');
    $mdlrecruitmenttbl =$mdlrecruitmenttbl->select('*');
    $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('created_by',  '=', $data['recruiter']);
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('status',  '=', 'Interview scheduled with Hiring Manager');
    if($data['from_date'] != null && $data['to_date'] != null){
        $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('updated_at',  '>=', $data['from_date']);
        $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('updated_at',  '<=', $data['to_date']);
       }
       if($data['from_date'] != null && $data['to_date'] == null ){
        $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('updated_at',  '=', $data['from_date']);
       }
       if($data['from_date'] == null && $data['to_date'] !== null){
       $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('updated_at',  '=', $data['to_date']);
       }
       if($data['today_date'] != null && $data['from_date'] == null && $data['to_date'] == null){
        $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('updated_at',  '=', date('Y-m-d'));
              }
              $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('candidate_status',  '=', 1);
        // $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('updated_at',  '=', date('Y-m-d'));

    $mdlrecruitmenttbl =$mdlrecruitmenttbl->count();
    return $mdlrecruitmenttbl;
}
public function get_recruiter_offers_filter( $data ){
    $mdlrecruitmenttbl = DB::table('offer_released_details as or');
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->join('candidate_details as cd', 'cd.cdID', '=', 'or.cdID');

    $mdlrecruitmenttbl =  $mdlrecruitmenttbl->select('or*');
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('or.created_by',  '=', $data['recruiter']);
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('or.profile_status',  '=', 'Offer Released');

    if($data['from_date'] != null && $data['to_date'] != null){
        $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('or.created_at',  '>=', $data['from_date']);
        $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('or.created_at',  '<=', $data['to_date']);
       }
       if($data['from_date'] != null && $data['to_date'] == null ){
        $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('or.created_at',  '=', $data['from_date']);
       }
       if($data['from_date'] == null && $data['to_date'] !== null){
       $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('or.created_at',  '=', $data['to_date']);
       }
       if($data['today_date'] != null && $data['from_date'] == null && $data['to_date'] == null){
        $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('or.created_at',  '=', date('Y-m-d'));
              }
              $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('cd.candidate_status',  '=', 1);
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->count();
    return $mdlrecruitmenttbl;
}
public function get_time_data_filter( $data ){
    $mdlrecruitmenttbl = DB::table('candidate_details');
    $mdlrecruitmenttbl =$mdlrecruitmenttbl->select('*');
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('created_by',  '=', $data['recruiter']);

    if($data['from_date'] != null && $data['to_date'] != null){
        $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('created_at',  '>=', $data['from_date']);
        $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('created_at',  '<=', $data['to_date']);
       }
       if($data['from_date'] != null && $data['to_date'] == null ){
        $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('created_at',  '=', $data['from_date']);
       }
       if($data['from_date'] == null && $data['to_date'] !== null){
       $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('created_at',  '=', $data['to_date']);
       }
       if($data['today_date'] != null && $data['from_date'] == null && $data['to_date'] == null){
        $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('created_at',  '=', date('Y-m-d'));
              }
   $mdlrecruitmenttbl = $mdlrecruitmenttbl->whereTime('created_at',  '>=', $data['from_time']);
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->whereTime('created_at',  '<=', $data['to_time']);
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('candidate_status',  '=', '1');

    $mdlrecruitmenttbl = $mdlrecruitmenttbl->count();
    return $mdlrecruitmenttbl;
}
public function get_position_working_rfh($data){
    $mdlrecruitmenttbl = DB::table('candidate_details as cd')
    ->join('recruitment_requests as rr', 'cd.hepl_recruitment_ref_number', '=', 'rr.hepl_recruitment_ref_number', 'left outer')
    ->select('cd.*','rr.position_title','rr.sub_position_title')
    ->where('cd.created_by',  '=', $data['recruiter'])
    ->where('rr.assigned_to',  '=', $data['recruiter'])
    ->whereDate('cd.created_at',  '=', $data['date'])
    ->where('cd.candidate_status',  '=', 1)
    ->groupBy('rr.hepl_recruitment_ref_number')
    ->get();
    return $mdlrecruitmenttbl;
}
public function get_position_cv_count( $data ){
    $mdlrecruitmenttbl = DB::table('candidate_details')
    ->select('*')
    ->where('hepl_recruitment_ref_number',  '=', $data['hepl_rfh_no'])
    ->where('created_by',  '=', $data['recruiter'])
    ->whereDate('created_at',  '=', $data['date'])
    ->where('candidate_status',  '=', 1)
    ->count();
    return $mdlrecruitmenttbl;
}
public function get_total_csv($recruiter_id){
    $mdlrecruitmenttbl = DB::table('candidate_details')
    ->select('*')
    ->where('created_by',  '=', $recruiter_id)
    ->whereDate('created_at',  '=', date('Y-m-d'))
    ->where('candidate_status',  '=', 1)
    ->count();
    return $mdlrecruitmenttbl;
}
public function get_offer_release_position($recruiter_id){
    $mdlrecruitmenttbl = DB::table('offer_released_details as cd');
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->join('recruitment_requests as rr', 'cd.hepl_recruitment_ref_number', '=', 'rr.hepl_recruitment_ref_number', 'left outer');
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->join('candidate_details as cs', 'cd.cdID', '=', 'cs.cdID', 'left outer');
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->select('cd.*','rr.position_title','rr.sub_position_title');
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('cd.created_by',  '=', $recruiter_id);
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('cd.profile_status', 'Offer Released');
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('cs.candidate_status', '1');
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('rr.assigned_to',  '=',$recruiter_id);
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->whereDate('cd.created_at',  '=',date('Y-m-d'));
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->get();
    return $mdlrecruitmenttbl;
}
public function get_position_working_filter($data){
    $mdlrecruitmenttbl = DB::table('candidate_details as cd');
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->join('recruitment_requests as rr', 'cd.hepl_recruitment_ref_number', '=', 'rr.hepl_recruitment_ref_number', 'left outer');
    $mdlrecruitmenttbl =$mdlrecruitmenttbl->select('cd.*','rr.position_title','rr.sub_position_title');
    $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('cd.created_by',  '=', $data['recruiter']);
    $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('rr.assigned_to',  '=', $data['recruiter']);
   if($data['from_date'] != null && $data['to_date'] != null){
    $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('cd.created_at',  '>=', $data['from_date']);
    $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('cd.created_at',  '<=', $data['to_date']);
   }
   if($data['from_date'] != null && $data['to_date'] == null ){
    $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('cd.created_at',  '=', $data['from_date']);
   }
   if($data['from_date'] == null && $data['to_date'] !== null){
   $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('cd.created_at',  '=', $data['to_date']);
   }
   if($data['today_date'] != null && $data['from_date'] == null && $data['to_date'] == null){
    $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('cd.created_at',  '=', date('Y-m-d'));
          }
    $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('cd.candidate_status',  '=', 1);
    $mdlrecruitmenttbl =$mdlrecruitmenttbl->groupBy('rr.hepl_recruitment_ref_number');
    $mdlrecruitmenttbl =$mdlrecruitmenttbl->get();
    return $mdlrecruitmenttbl;
}
public function get_position_cv_count_filter( $data ){
    $mdlrecruitmenttbl = DB::table('candidate_details');
    $mdlrecruitmenttbl =$mdlrecruitmenttbl->select('*');
    $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('hepl_recruitment_ref_number',  '=', $data['hepl_rfh_no']);
    $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('created_by',  '=', $data['recruiter']);
    if($data['from_date'] != null && $data['to_date'] != null){
    $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('created_at',  '>=', $data['from_date']);
    $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('created_at',  '<=', $data['to_date']);
    }
    if($data['from_date'] != null && $data['to_date'] == null ){
        $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('created_at',  '=', $data['from_date']);
       }
       if($data['from_date'] == null && $data['to_date'] !== null){
       $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('created_at',  '=', $data['to_date']);
       }
       if($data['today_date'] !=null && $data['from_date'] == null && $data['to_date'] == null){
        $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('created_at',  '=', $data['today_date']);
               }
               $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('candidate_status',  '=', 1);
    $mdlrecruitmenttbl =$mdlrecruitmenttbl->count();
    return $mdlrecruitmenttbl;
}
public function get_total_csv_filter($data){
    $mdlrecruitmenttbl = DB::table('candidate_details');
    $mdlrecruitmenttbl =$mdlrecruitmenttbl->select('*');
    $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('created_by',  '=', $data['recruiter']);
    if($data['from_date'] != null && $data['to_date'] != null){
    $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('created_at',  '>=', $data['from_date']);
    $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('created_at',  '<=', $data['to_date']);
    }
    if($data['from_date'] != null && $data['to_date'] == null){
        $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('created_at',  '=', $data['from_date']);
       }
       if($data['from_date'] == null && $data['to_date'] !== null){
       $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('created_at',  '=', $data['to_date']);
       }
       if($data['today_date'] != null && $data['from_date'] == null && $data['to_date'] == null){
        $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('created_at',  '=', $data['today_date']);
               }
               $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('candidate_status',  '=', 1);
    $mdlrecruitmenttbl =$mdlrecruitmenttbl->count();
    return $mdlrecruitmenttbl;
}
public function get_recruiter_offers_filter_id($data){
    $mdlrecruitmenttbl =  DB::table('offer_released_details as or');
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->join('candidate_details as cd', 'cd.cdID', '=', 'or.cdID');

    $mdlrecruitmenttbl = $mdlrecruitmenttbl->select('or*');
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('or.created_by',  '=', $data['recruiter']);
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('or.profile_status',  '=', 'Offer Released');
    if($data['from_date'] != null && $data['to_date'] != null){
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->whereDate('or.created_at',  '>=', $data['from_date']);
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->whereDate('or.created_at',  '<=', $data['to_date']);
    }
    if($data['from_date'] != null && $data['to_date'] == null ){
        $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('or.created_at',  '=', $data['from_date']);
       }
       if($data['from_date'] == null && $data['to_date'] !== null){
       $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('or.created_at',  '=', $data['to_date']);
       }
       if($data['today_date'] !=null && $data['from_date'] == null && $data['to_date'] == null){
        $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('or.created_at',  '=', $data['today_date']);
               }
     $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('cd.candidate_status',  '=', '1');

         $mdlrecruitmenttbl = $mdlrecruitmenttbl->count();
    //$mdlrecruitmenttbl = $mdlrecruitmenttbl->count();
    return $mdlrecruitmenttbl;

}
public function get_offer_release_position_filter($data){
    $mdlrecruitmenttbl = DB::table('offer_released_details as cd');
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->join('recruitment_requests as rr', 'cd.hepl_recruitment_ref_number', '=', 'rr.hepl_recruitment_ref_number', 'left outer');
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->join('candidate_details as cs', 'cd.cdID', '=', 'cs.cdID', 'left outer');
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->select('cd.*','rr.position_title','rr.sub_position_title');
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('cd.created_by',  '=', $data['recruiter']);
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('cd.profile_status',  '=', 'Offer Released');
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('cs.candidate_status',  '=', '1');
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('rr.assigned_to',  '=',$data['recruiter']);
    if($data['from_date'] != null && $data['to_date'] != null){
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->whereDate('cd.created_at',  '>=', $data['from_date']);
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->whereDate('cd.created_at',  '<=', $data['to_date']);
    }
    if($data['from_date'] != null && $data['to_date'] == null){
        $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('cd.created_at',  '=', $data['from_date']);
       }
       if($data['from_date'] == null && $data['to_date'] !== null){
       $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('cd.created_at',  '=', $data['to_date']);
       }
       if($data['today_date'] != null && $data['from_date'] == null && $data['to_date'] == null){
        $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('cd.created_at',  '=', $data['today_date']);
               }
   $mdlrecruitmenttbl =$mdlrecruitmenttbl->get();
    return $mdlrecruitmenttbl;
}
public function get_team_recruiter( $team ){
    $mdlrecruitmenttbl = DB::table('users as us')
    ->select('us.*')
    ->where('us.team',  '=', $team)
    ->where('us.role_type',  '=', "Recruiter")
    ->where('us.profile_status',  '=', "Active")
    ->get();

    return $mdlrecruitmenttbl;
}
public function get_recruitment_dt( $data ){
    // $mdlrecruitmenttbl = DB::table('candidate_details');
    // $mdlrecruitmenttbl = $mdlrecruitmenttbl->select('*');
    // if($data['from_date'] !=  "" && $data['to_date'] != ""){
    //     $mdlrecruitmenttbl = $mdlrecruitmenttbl->whereDate('updated_at',  '>=', $data['from_date']);
    //     $mdlrecruitmenttbl = $mdlrecruitmenttbl->whereDate('updated_at',  '<=', $data['to_date']);
    //     }
    //     if($data['from_date'] != "" && $data['to_date'] == "" ){
    //         $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('updated_at',  '=', $data['from_date']);
    //        }
    //        if($data['from_date'] == "" && $data['to_date'] !== ""){
    //        $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('updated_at',  '=', $data['to_date']);
    //        }
    //        if($data['today_date'] !="" && $data['from_date'] == "" && $data['to_date'] == ""){
    // $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('updated_at',  '=', $data['today_date']);
    //        }
    // $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('status',  '=', "Offer Accepted");
    // $mdlrecruitmenttbl =$mdlrecruitmenttbl->count();
    // return $mdlrecruitmenttbl;
    $mdlrecruitmenttbl = DB::table('recruitment_requests as rr');
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->select('rr.*');
$mdlrecruitmenttbl = $mdlrecruitmenttbl->leftjoin('users as u', 'rr.assigned_to', '=', 'u.empID');
$mdlrecruitmenttbl = $mdlrecruitmenttbl->leftjoin('tbl_rfh as tr', 'rr.rfh_no', '=', 'tr.res_id');
    if($data['from_date'] != null && $data['to_date'] != null){
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('rr.close_date',  '>=', $data['from_date']);
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('rr.close_date',  '<=', $data['to_date']);
        }
        if($data['from_date'] != null && $data['to_date'] == null ){
            $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('rr.close_date',  '=', $data['from_date']);
           }
           if($data['from_date'] == null && $data['to_date'] !== null){
           $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('rr.close_date',  '=', $data['to_date']);
           }
           if($data['today_date'] !=null && $data['from_date'] == null && $data['to_date'] == null){
            $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('rr.close_date',  '=', $data['today_date']);
           }
          // $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('u.team',  '=', 'HEPL');

                $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('rr.request_status',  '=', "Closed");
                $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('rr.delete_status',  '=', "0");
                $mdlrecruitmenttbl = $mdlrecruitmenttbl->whereColumn('rr.assigned_to', 'rr.closed_by');
                $mdlrecruitmenttbl =$mdlrecruitmenttbl->groupBy('rr.closed_by');
                    $mdlrecruitmenttbl =$mdlrecruitmenttbl->count();
                    return $mdlrecruitmenttbl;
}

public function get_avg_closed( $data ){
    $mdlrecruitmenttbl = DB::table('recruitment_requests');
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->select('*');
    if($data['from_date'] != null && $data['to_date'] != null){
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->whereDate('updated_at',  '>=', $data['from_date']);
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->whereDate('updated_at',  '<=', $data['to_date']);
        }
        if($data['from_date'] != null && $data['to_date'] == null ){
            $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('updated_at',  '=', $data['from_date']);
           }
           if($data['from_date'] == null && $data['to_date'] !== null){
           $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('updated_at',  '=', $data['to_date']);
           }
           if($data['today_date'] !=null && $data['from_date'] == null && $data['to_date'] == null){
    $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('updated_at',  '=', $data['today_date']);
           }
    $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('request_status',  '=', "Closed");
    $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('delete_status',  '=', "0");
    $mdlrecruitmenttbl =$mdlrecruitmenttbl->count();
    return $mdlrecruitmenttbl;
}
public function get_billable_status( $bill ){
    $mdlrecruitmenttbl = DB::table('recruitment_requests as rr');
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->select('rr.*');
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->leftjoin('users as u', 'rr.assigned_to', '=', 'u.empID');
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->leftjoin('tbl_rfh as tr', 'rr.rfh_no', '=', 'tr.res_id');
    if($bill['from_date'] != null && $bill['to_date'] != null){
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->whereDate('rr.created_at',  '>=', $bill['from_date']);
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->whereDate('rr.created_at',  '<=', $bill['to_date']);
        }
        if($bill['from_date'] != null && $bill['to_date'] == null ){
            $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('rr.created_at',  '=', $bill['from_date']);
           }
           if($bill['from_date'] == null && $bill['to_date'] !== null){
           $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('rr.created_at',  '=', $bill['to_date']);
           }
           if($bill['today_date'] !=null && $bill['from_date'] == null && $bill['to_date'] == null){
    $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('rr.created_at',  '=', $bill['today_date']);
           }
   if($bill['billing_status'] == "Billable"){
           $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('rr.billing_status',  '=', "Billable");
   }
   if($bill['billing_status'] == "Non Billable"){
    $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('rr.billing_status',  '=', "Non Billable");
   }
   $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('rr.request_status',  '!=', "Closed");
$mdlrecruitmenttbl =$mdlrecruitmenttbl->where('rr.request_status',  '!=', "On Hold");
$mdlrecruitmenttbl = $mdlrecruitmenttbl->where('u.team',  '=', 'HEPL');

$mdlrecruitmenttbl = $mdlrecruitmenttbl->where('rr.delete_status',  '=', "0");
$mdlrecruitmenttbl =$mdlrecruitmenttbl->groupBy('rr.hepl_recruitment_ref_number');
  // $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('rr.request_status',  '=', "Open");
   //$mdlrecruitmenttbl =$mdlrecruitmenttbl->where('request_status',  '=', "Re Open");
    $mdlrecruitmenttbl =$mdlrecruitmenttbl->count();
    return $mdlrecruitmenttbl;
}
public function get_max_count_recruiter($data){

        $mdlrecruitmenttbl = DB::table('candidate_details');
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->select('*');
        if($data['from_date'] != null && $data['to_date'] != null){
            $mdlrecruitmenttbl = $mdlrecruitmenttbl->whereDate('updated_at',  '>=', $data['from_date']);
            $mdlrecruitmenttbl = $mdlrecruitmenttbl->whereDate('updated_at',  '<=', $data['to_date']);
            }
            if($data['from_date'] != null && $data['to_date'] == null){
                $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('updated_at',  '=', $data['from_date']);
               }
               if($data['from_date'] == null && $data['to_date'] !== null){
               $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('updated_at',  '=', $data['to_date']);
               }
               if($data['today_date'] !=null && $data['from_date'] == null && $data['to_date'] == null){
        $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('updated_at',  '=', $data['today_date']);
               }
        $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('status',  '=', "Offer Accepted");
       $mdlrecruitmenttbl =$mdlrecruitmenttbl->groupBy('created_by');
       $mdlrecruitmenttbl =$mdlrecruitmenttbl->orderBy('created_by','desc');
        $mdlrecruitmenttbl =$mdlrecruitmenttbl->count();
        //$mdlrecruitmenttbl =$mdlrecruitmenttbl->get();
        return $mdlrecruitmenttbl;

}
public function get_min_count_recruiter($data){

    $mdlrecruitmenttbl = DB::table('candidate_details');
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->select('*');
    if($data['from_date'] != null && $data['to_date'] != null){
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->whereDate('updated_at',  '>=', $data['from_date']);
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->whereDate('updated_at',  '<=', $data['to_date']);
        }
        if($data['from_date'] != null && $data['to_date'] == null ){
            $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('updated_at',  '=', $data['from_date']);
           }
           if($data['from_date'] == null && $data['to_date'] !== null){
           $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('updated_at',  '=', $data['to_date']);
           }
           if($data['today_date'] !=null && $data['from_date'] == null && $data['to_date'] == null){
    $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('updated_at',  '=', $data['today_date']);
           }
    $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('status',  '=', "Offer Accepted");
   $mdlrecruitmenttbl =$mdlrecruitmenttbl->groupBy('created_by');
   $mdlrecruitmenttbl =$mdlrecruitmenttbl->orderBy('created_by','asc');
    $mdlrecruitmenttbl =$mdlrecruitmenttbl->count();

    //$mdlrecruitmenttbl =$mdlrecruitmenttbl->get();
    return $mdlrecruitmenttbl;

}


    public function get_closure_details( $bill ){
        $mdlrecruitmenttbl = DB::table('recruitment_requests as rr');
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->leftJoin( 'tbl_rfh as tr', 'rr.rfh_no', '=', 'tr.res_id' );
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->leftJoin( 'users as u', 'rr.assigned_to', '=', 'u.empID' );
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->leftJoin( 'tat_details_tbls as tdt', 'rr.band', '=', 'tdt.id' );
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->select('*');
        if($bill['from_date'] != null && $bill['to_date'] != null){
            $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('rr.close_date',  '>=', $bill['from_date']);
            $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('rr.close_date',  '<=', $bill['to_date']);
            }
            if($bill['from_date'] != null && $bill['to_date'] == null ){
                $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('rr.close_date',  '=', $bill['from_date']);
               }
               if($bill['from_date'] == null && $bill['to_date'] !== null){
               $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('rr.close_date',  '=', $bill['to_date']);
               }
               if($bill['today_date'] !=null && $bill['from_date'] == null && $bill['to_date'] == null){
        $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('rr.close_date',  '=', $bill['today_date']);
               }
       if($bill['billing_status'] == "Billable"){
               $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('rr.billing_status',  '=', "Billable");
       }
       if($bill['billing_status'] == "Non Billable"){
        $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('rr.billing_status',  '=', "Non Billable");
       }
       $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('rr.assigned_to',  '=', $bill['recruit_id']);
       $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('rr.request_status',  '=', "Closed");
       $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('rr.delete_status',  '=', "0");
       $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('rr.closed_by','=',$bill['recruit_id']);

        $mdlrecruitmenttbl =$mdlrecruitmenttbl->count();
        return $mdlrecruitmenttbl;
}
public function get_closure_details_close( $bill ){
    $mdlrecruitmenttbl = DB::table('recruitment_requests as rr');
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->leftJoin( 'tbl_rfh as tr', 'rr.rfh_no', '=', 'tr.res_id' );
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->leftJoin( 'users as u', 'rr.assigned_to', '=', 'u.empID' );
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->leftJoin( 'tat_details_tbls as tdt', 'rr.band', '=', 'tdt.id' );
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->select('*');
    if($bill['from_date'] != null && $bill['to_date'] != null){
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('rr.close_date',  '>=', $bill['from_date']);
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('rr.close_date',  '<=', $bill['to_date']);
        }
        if($bill['from_date'] != null && $bill['to_date'] == null ){
            $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('rr.close_date',  '=', $bill['from_date']);
           }
           if($bill['from_date'] == null && $bill['to_date'] !== null){
           $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('rr.close_date',  '=', $bill['to_date']);
           }
           if($bill['today_date'] !=null && $bill['from_date'] == null && $bill['to_date'] == null){
    $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('rr.close_date',  '=', $bill['today_date']);
           }

   $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('rr.assigned_to',  '=', $bill['recruit_id']);
   $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('rr.request_status',  '=', "Closed");
   $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('rr.delete_status',  '=', "0");
   $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('rr.closed_by','=',$bill['recruit_id']);
    $mdlrecruitmenttbl =$mdlrecruitmenttbl->count();
    return $mdlrecruitmenttbl;
}

public function get_billable_status_close( $bill ){
    $mdlrecruitmenttbl = DB::table('recruitment_requests as rr');
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->leftJoin( 'tbl_rfh as tr', 'rr.rfh_no', '=', 'tr.res_id' );
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->leftJoin( 'users as u', 'rr.assigned_to', '=', 'u.empID' );
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->leftJoin( 'tat_details_tbls as tdt', 'rr.band', '=', 'tdt.id' );
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->select('closed_by');
    if($bill['from_date'] != null && $bill['to_date'] != null){
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('rr.close_date',  '>=', $bill['from_date']);
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('rr.close_date',  '<=', $bill['to_date']);
        }
        if($bill['from_date'] != null && $bill['to_date'] == null ){
            $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('rr.close_date',  '=', $bill['from_date']);
           }
           if($bill['from_date'] == null && $bill['to_date'] !== null){
           $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('rr.close_date',  '=', $bill['to_date']);
           }
           if($bill['today_date'] !=null && $bill['from_date'] == null && $bill['to_date'] == null){
    $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('rr.close_date',  '=', $bill['today_date']);
           }
   if($bill['billing_status'] == "Billable"){
           $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('rr.billing_status',  '=', "Billable");
   }
   if($bill['billing_status'] == "Non Billable"){
    $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('rr.billing_status',  '=', "Non Billable");
   }
   $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('rr.request_status',  '=', "Closed");
   $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('rr.delete_status',  '=', "0");
   $mdlrecruitmenttbl = $mdlrecruitmenttbl->whereColumn('rr.assigned_to', 'rr.closed_by');

 //  $mdlrecruitmenttbl =$mdlrecruitmenttbl->groupBy('rr.closed_by');
    $mdlrecruitmenttbl =$mdlrecruitmenttbl->count();
    return $mdlrecruitmenttbl;
}
public function get_billable_status_total( $bill ){
    $mdlrecruitmenttbl = DB::table('recruitment_requests as rr');
    //$mdlrecruitmenttbl = $mdlrecruitmenttbl->leftJoin( 'tbl_rfh as tr', 'rr.rfh_no', '=', 'tr.res_id' );
    //$mdlrecruitmenttbl = $mdlrecruitmenttbl->leftJoin( 'users as u', 'rr.closed_by', '=', 'u.empID' );
    //$mdlrecruitmenttbl = $mdlrecruitmenttbl->leftJoin( 'tat_details_tbls as tdt', 'rr.band', '=', 'tdt.id' );
    //$mdlrecruitmenttbl = $mdlrecruitmenttbl->Join( 'recruitment_requests as cr', 'rr.closed_by', '=', 'cr.assigned_to' );

    $mdlrecruitmenttbl = $mdlrecruitmenttbl->select('*');
    if($bill['from_date'] != null && $bill['to_date'] != null){
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('rr.close_date',  '>=', $bill['from_date']);
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('rr.close_date',  '<=', $bill['to_date']);
        }
        if($bill['from_date'] != null && $bill['to_date'] == null ){
            $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('rr.close_date',  '=', $bill['from_date']);
           }
           if($bill['from_date'] == null && $bill['to_date'] !== null){
           $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('rr.close_date',  '=', $bill['to_date']);
           }
           if($bill['today_date'] !=null && $bill['from_date'] == null && $bill['to_date'] == null){
            $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('rr.close_date',  '=', $bill['today_date']);
           }


   $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('rr.request_status',  '=', "Closed");
   $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('rr.delete_status',  '=', "0");
   $mdlrecruitmenttbl = $mdlrecruitmenttbl->whereColumn('rr.assigned_to', 'rr.closed_by');
   //$mdlrecruitmenttbl =$mdlrecruitmenttbl->groupBy('rr.closed_by');
    $mdlrecruitmenttbl =$mdlrecruitmenttbl->count();
    return $mdlrecruitmenttbl;
}
public function get_total_csv_avg( $input_dt ){
    $mdlrecruitmenttbl = DB::table('candidate_details');
    $mdlrecruitmenttbl = $mdlrecruitmenttbl->select('*');
    if($input_dt['from_date'] != null && $input_dt['to_date'] != null){
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->whereDate('created_at',  '>=', $input_dt['from_date']);
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->whereDate('created_at',  '<=', $input_dt['to_date']);
        }
    if($input_dt['from_date'] != null && $input_dt['to_date'] == null ){
        $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('created_at',  '=', $input_dt['from_date']);
       }
       if($input_dt['from_date'] == null && $input_dt['to_date'] !== null){
       $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('created_at',  '=', $input_dt['to_date']);
       }
        if($input_dt['from_date'] == null &&  $input_dt['to_date'] == null){
            $mdlrecruitmenttbl =  $mdlrecruitmenttbl->whereDate('created_at',  '=', date('Y-m-d'));
        }
        $mdlrecruitmenttbl =  $mdlrecruitmenttbl->where('candidate_status',  '=','1');
    $mdlrecruitmenttbl =  $mdlrecruitmenttbl->count();
    return $mdlrecruitmenttbl;
}
    // public function get_recruiter_count_csv( $input_dt ){
    //     $mdlrecruitmenttbl = DB::table('users as u');
    //     $mdlrecruitmenttbl = $mdlrecruitmenttbl->join('candidate_details as cd', 'cd.created_by', '=', 'u.empID', 'left outer');
    //     if($input_dt['from_date'] != " " && $input_dt['to_date'] == " " ){
    //         $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('cd.created_at',  '=', $input_dt['from_date']);
    //        }
    //        if($input_dt['from_date'] == " " && $input_dt['to_date'] !== " "){
    //        $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('cd.created_at',  '=', $input_dt['to_date']);
    //        }
    //         if($input_dt['from_date'] == " " &&  $input_dt['to_date'] == " "){
    //             $mdlrecruitmenttbl =  $mdlrecruitmenttbl->whereDate('cd.created_at',  '=', $input_dt['today_date']);
    //         }
    //         $mdlrecruitmenttbl = $mdlrecruitmenttbl->whereColumn('cd.created_by', 'u.empID');

    //     $mdlrecruitmenttbl =  $mdlrecruitmenttbl->count();
    //     return $mdlrecruitmenttbl;

    // }
    public function get_user_recruiter_byorder($order){
        $mdlrecruitmenttbl = DB::table('users as us')
        ->select('us.*')
        ->where('us.role_type',  '=', "Recruiter")
        ->where('us.profile_status',  '=', "Active")
        ->where('us.team',  '=', "HEPL")
        ->orderBy('us.name' , $order)
        ->get();

        return $mdlrecruitmenttbl;
    }
    public function ageging_billable_nonbillable_total($data){
        $mdlrecruitmenttbl = DB::table('recruitment_requests as rr');
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->select('rr.*');
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->leftjoin('users as u', 'rr.assigned_to', '=', 'u.empID');
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->leftjoin('tbl_rfh as tr', 'rr.rfh_no', '=', 'tr.res_id');
       $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('rr.request_status',  '!=', "Closed");
       $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('rr.request_status',  '!=', "On Hold");
       if($data['billable_status'] != ""){
        $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('rr.billing_status',  '=', $data['billable_status']);
       }
       $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('u.team',  '=', $data['input_team']);

       $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('rr.delete_status',  '=', "0");
       $mdlrecruitmenttbl =$mdlrecruitmenttbl->orderBy('rr.updated_at','desc');
       $mdlrecruitmenttbl =$mdlrecruitmenttbl->groupBy('rr.hepl_recruitment_ref_number');
        $mdlrecruitmenttbl =$mdlrecruitmenttbl->get();
        return $mdlrecruitmenttbl;
    }
    public function get_avg_open( $data ){
        $mdlrecruitmenttbl = DB::table('recruitment_requests as rr');
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->select('rr.*');
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->leftjoin('users as u', 'rr.assigned_to', '=', 'u.empID');
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->leftjoin('tbl_rfh as tr', 'rr.rfh_no', '=', 'tr.res_id');
       $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('rr.request_status',  '!=', "Closed");
       $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('rr.request_status',  '!=', "On Hold");
        if($data['from_date'] != null && $data['to_date'] != null){
            $mdlrecruitmenttbl = $mdlrecruitmenttbl->whereDate('rr.open_date',  '>=', $data['from_date']);
            $mdlrecruitmenttbl = $mdlrecruitmenttbl->whereDate('rr.open_date',  '<=', $data['to_date']);
            }
            if($data['from_date'] != null && $data['to_date'] == null){
                $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('rr.open_date',  '=', $data['from_date']);
               }
               if($data['from_date'] == null && $data['to_date'] !== null){
               $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('rr.open_date',  '=', $data['to_date']);
               }
               if($data['today_date'] != null && $data['from_date'] == null && $data['to_date'] == null){
        $mdlrecruitmenttbl =$mdlrecruitmenttbl->whereDate('rr.open_date',  '=', $data['today_date']);
               }
      //  $mdlrecruitmenttbl =$mdlrecruitmenttbl->where('request_status',  '=', "Open");
      $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('u.team',  '=', 'HEPL');

      $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('rr.delete_status',  '=', "0");
      //$mdlrecruitmenttbl =$mdlrecruitmenttbl->orderBy('rr.updated_at','desc');
      $mdlrecruitmenttbl =$mdlrecruitmenttbl->groupBy('rr.hepl_recruitment_ref_number');
        $mdlrecruitmenttbl =$mdlrecruitmenttbl->count();
        return $mdlrecruitmenttbl;
    }
}
