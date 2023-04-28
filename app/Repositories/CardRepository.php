<?php

namespace App\Repositories;

use App\Models\recruitmentRequest;
use App\Models\User;

use DB;
class CardRepository implements ICardRepository
{  
    public function get_cv_count_details($advanced_filter){

      

        $mdlrecreqtbl = DB::table('candidate_details as cd');
        
        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'users as u', 'u.empID', '=', 'cd.created_by' );
        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'users as ur',  'ur.empID', '=', 'cd.created_by');
        // $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'recruitment_requestss as rr',  'rr.rfh_no', '=', 'cd.rfh_no');

        $mdlrecreqtbl = $mdlrecreqtbl->select( DB::raw('COUNT(cd.created_by) as cv_count'),'cd.created_by as recruiter_id', 'u.name as recruiter' );
        $mdlrecreqtbl = $mdlrecreqtbl->where( 'cd.created_on', '>=', $advanced_filter['get_from_date'] );
        $mdlrecreqtbl = $mdlrecreqtbl->where( 'cd.created_on', '<=', $advanced_filter['get_to_date']);
        if($advanced_filter['af_recruiter']!=''){
            $mdlrecreqtbl = $mdlrecreqtbl->where( 'cd.created_by', '=', $advanced_filter['af_recruiter']);

        }
        if ($advanced_filter['af_teams'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where('ur.team', '=', $advanced_filter['af_teams']);
        }
        if ($advanced_filter['af_division'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where('rr.division', '=', $advanced_filter['af_division']);
        }
        return $mdlrecreqtbl
        ->orderBy( 'u.name', 'asc' )->groupBy('cd.created_by')->get();
    }

    public function get_cpcv_count_details($advanced_filter){
        $mdlrecreqtbl = DB::table('recruitment_requests as rr');

        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'users as u', 'rr.assigned_to', '=', 'u.empID' );
        $mdlrecreqtbl = $mdlrecreqtbl->leftJoin( 'users as us', 'rr.closed_by', '=', 'us.empID' );
        $mdlrecreqtbl = $mdlrecreqtbl->select( 'rr.hepl_recruitment_ref_number','us.name as closed_by_name','u.name as recruiter_name' );

        $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.close_date', '>=', $advanced_filter['af_from_date'] );
        $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.close_date', '<=', $advanced_filter['af_to_date']);
        
        if ($advanced_filter['af_division'] != '') {
            $mdlrecreqtbl = $mdlrecreqtbl->where('rr.division', '=', $advanced_filter['af_division']);
        }

        $mdlrecreqtbl = $mdlrecreqtbl->where( 'rr.request_status', '=', 'Closed');
        $mdlrecreqtbl = $mdlrecreqtbl->where('rr.delete_status',  '=', "0");
        return $mdlrecreqtbl
        ->orderBy( 'us.name', 'asc' )
        ->groupBy('rr.hepl_recruitment_ref_number')->get();
    }

    public function get_date_of_rfh($input_details){

        $mdlrecruitmenttbl = DB::table('recruitment_requests as rr');
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->select('rr.open_date');
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('rr.hepl_recruitment_ref_number', '=', $input_details['hepl_recruitment_ref_number']);

        if (array_key_exists('assigned_to', $input_details)) {
            $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('rr.assigned_to', '=', $input_details['assigned_to']);
        }
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->orderBy('rr.created_at', 'asc');
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->limit(1);
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->get();

        return $mdlrecruitmenttbl;
    }

    public function get_dops_cd( $input_details ){

        $mdl_cd_tbl = DB::table('candidate_details as cd');
        $mdl_cd_tbl = $mdl_cd_tbl->select('cd.cdID');
        $mdl_cd_tbl = $mdl_cd_tbl->where('cd.hepl_recruitment_ref_number', '=', $input_details['hepl_recruitment_ref_number']);
        if (array_key_exists('assigned_to', $input_details)) {
            $mdl_cd_tbl = $mdl_cd_tbl->where('cd.created_by', '=', $input_details['assigned_to']);
        }
        $mdl_cd_tbl = $mdl_cd_tbl->orderBy('cd.created_at', 'asc');
        $mdl_cd_tbl = $mdl_cd_tbl->get();

        return $mdl_cd_tbl;
    }

    public function get_date_of_profile_screened( $input_details ){

        $mdl_cfd_tbl = DB::table('candidate_followup_details as cfd');
        $mdl_cfd_tbl = $mdl_cfd_tbl->select('cfd.created_on');
        $mdl_cfd_tbl = $mdl_cfd_tbl->whereIn('cfd.cdID', $input_details['cdID']);
        $mdl_cfd_tbl = $mdl_cfd_tbl->where('cfd.follow_up_status', '=', 'Profile submitted to Hiring Manager');
        if (array_key_exists('assigned_to', $input_details)) {
            $mdl_cfd_tbl = $mdl_cfd_tbl->where('cfd.created_by', '=', $input_details['assigned_to']);
        }
        $mdl_cfd_tbl = $mdl_cfd_tbl->orderBy('cfd.created_at', 'asc');
        $mdl_cfd_tbl = $mdl_cfd_tbl->limit(1);
        $mdl_cfd_tbl = $mdl_cfd_tbl->get();

        return $mdl_cfd_tbl;
    }

    public function get_date_of_last_interviewdate( $input_details ){
        $mdl_cfd_tbl = DB::table('candidate_followup_details as cfd');
        $mdl_cfd_tbl = $mdl_cfd_tbl->select('cfd.created_on');
        $mdl_cfd_tbl = $mdl_cfd_tbl->whereIn('cfd.cdID', $input_details['cdID']);
        $mdl_cfd_tbl = $mdl_cfd_tbl->where('cfd.follow_up_status', '=', 'Profile shortlisted by Hiring Manager');
        if (array_key_exists('assigned_to', $input_details)) {
            $mdl_cfd_tbl = $mdl_cfd_tbl->where('cfd.created_by', '=', $input_details['assigned_to']);
        }
        $mdl_cfd_tbl = $mdl_cfd_tbl->orderBy('cfd.created_at', 'asc');
        $mdl_cfd_tbl = $mdl_cfd_tbl->limit(1);
        $mdl_cfd_tbl = $mdl_cfd_tbl->get();

        return $mdl_cfd_tbl;
    }

    public function get_date_of_offer_releaseddate( $input_details ){

        $mdl_cfd_tbl = DB::table('candidate_followup_details as cfd');
        $mdl_cfd_tbl = $mdl_cfd_tbl->select('cfd.created_on');
        $mdl_cfd_tbl = $mdl_cfd_tbl->whereIn('cfd.cdID', $input_details['cdID']);
        $mdl_cfd_tbl = $mdl_cfd_tbl->where('cfd.follow_up_status', '=', 'Offer Released');
        if (array_key_exists('assigned_to', $input_details)) {
            $mdl_cfd_tbl = $mdl_cfd_tbl->where('cfd.created_by', '=', $input_details['assigned_to']);
        }
        $mdl_cfd_tbl = $mdl_cfd_tbl->orderBy('cfd.created_at', 'asc');
        $mdl_cfd_tbl = $mdl_cfd_tbl->limit(1);
        $mdl_cfd_tbl = $mdl_cfd_tbl->get();

        return $mdl_cfd_tbl;
    }

    public function get_date_of_offer_accepteddate( $input_details ){
        $mdl_cfd_tbl = DB::table('candidate_followup_details as cfd');
        $mdl_cfd_tbl = $mdl_cfd_tbl->select('cfd.created_on');
        $mdl_cfd_tbl = $mdl_cfd_tbl->whereIn('cfd.cdID', $input_details['cdID']);
        $mdl_cfd_tbl = $mdl_cfd_tbl->where('cfd.follow_up_status', '=', 'Offer Accepted');
        if (array_key_exists('assigned_to', $input_details)) {
            $mdl_cfd_tbl = $mdl_cfd_tbl->where('cfd.created_by', '=', $input_details['assigned_to']);
        }
        $mdl_cfd_tbl = $mdl_cfd_tbl->orderBy('cfd.created_at', 'asc');
        $mdl_cfd_tbl = $mdl_cfd_tbl->limit(1);
        $mdl_cfd_tbl = $mdl_cfd_tbl->get();

        return $mdl_cfd_tbl;
    }

    public function get_date_of_candidate_onboarded( $input_details ){
        $mdl_cfd_tbl = DB::table('candidate_followup_details as cfd');
        $mdl_cfd_tbl = $mdl_cfd_tbl->select('cfd.created_on');
        $mdl_cfd_tbl = $mdl_cfd_tbl->whereIn('cfd.cdID', $input_details['cdID']);
        $mdl_cfd_tbl = $mdl_cfd_tbl->where('cfd.follow_up_status', '=', 'Candidate Onboarded');
        if (array_key_exists('assigned_to', $input_details)) {
            $mdl_cfd_tbl = $mdl_cfd_tbl->where('cfd.created_by', '=', $input_details['assigned_to']);
        }
        $mdl_cfd_tbl = $mdl_cfd_tbl->orderBy('cfd.created_at', 'asc');
        $mdl_cfd_tbl = $mdl_cfd_tbl->limit(1);
        $mdl_cfd_tbl = $mdl_cfd_tbl->get();

        return $mdl_cfd_tbl;
    }

    public function check_candidate_not_show( $input_details ){
    
        $mdl_cfd_tbl = DB::table('candidate_followup_details as cfd');
        $mdl_cfd_tbl = $mdl_cfd_tbl->select('cfd.*');
        $mdl_cfd_tbl = $mdl_cfd_tbl->whereIn('cfd.cdID', $input_details['cdID']);
        $mdl_cfd_tbl = $mdl_cfd_tbl->where('cfd.follow_up_status', '=', 'Candidate No Show');
        $mdl_cfd_tbl = $mdl_cfd_tbl->where('cfd.follow_up_status', '=', 'Offer Rejected');
        $mdl_cfd_tbl = $mdl_cfd_tbl->where('cfd.follow_up_status', '=', 'Candidate Abscond');
        if (array_key_exists('assigned_to', $input_details)) {
            $mdl_cfd_tbl = $mdl_cfd_tbl->where('cfd.created_by', '=', $input_details['assigned_to']);
        }
        $mdl_cfd_tbl = $mdl_cfd_tbl->orderBy('cfd.created_at', 'desc');
        $mdl_cfd_tbl = $mdl_cfd_tbl->limit(1);
        $mdl_cfd_tbl = $mdl_cfd_tbl->get();

        return $mdl_cfd_tbl;
    }

    public function get_date_of_profile_screened_next( $input_details_nad ){
       
        $mdl_cfd_tbl = DB::table('candidate_followup_details as cfd');
        $mdl_cfd_tbl = $mdl_cfd_tbl->select('cfd.created_on');
        $mdl_cfd_tbl = $mdl_cfd_tbl->whereIn('cfd.cdID', $input_details_nad['cdID']);
        $mdl_cfd_tbl = $mdl_cfd_tbl->where('cfd.created_on', '>=', $input_details_nad['date_cond']);
        $mdl_cfd_tbl = $mdl_cfd_tbl->where('cfd.follow_up_status', '!=', 'Candidate No Show');
        $mdl_cfd_tbl = $mdl_cfd_tbl->where('cfd.follow_up_status', '!=', 'Offer Rejected');
        $mdl_cfd_tbl = $mdl_cfd_tbl->where('cfd.follow_up_status', '!=', 'Candidate Abscond');
        if (array_key_exists('assigned_to', $input_details_nad)) {
            $mdl_cfd_tbl = $mdl_cfd_tbl->where('cfd.created_by', '=', $input_details_nad['assigned_to']);
        }
        $mdl_cfd_tbl = $mdl_cfd_tbl->orderBy('cfd.created_at', 'asc');
        $mdl_cfd_tbl = $mdl_cfd_tbl->limit(1);
        $mdl_cfd_tbl = $mdl_cfd_tbl->get();

        return $mdl_cfd_tbl;
    }

    public function get_date_of_last_interviewdate_next( $input_details_nad ){
        $mdl_cfd_tbl = DB::table('candidate_followup_details as cfd');
        $mdl_cfd_tbl = $mdl_cfd_tbl->select('cfd.created_on');
        $mdl_cfd_tbl = $mdl_cfd_tbl->whereIn('cfd.cdID', $input_details_nad['cdID']);
        $mdl_cfd_tbl = $mdl_cfd_tbl->where('cfd.created_on', '>=', $input_details_nad['date_cond']);
        $mdl_cfd_tbl = $mdl_cfd_tbl->where('cfd.follow_up_status', '=', 'Profile shortlisted by Hiring Manager');
        if (array_key_exists('assigned_to', $input_details_nad)) {
            $mdl_cfd_tbl = $mdl_cfd_tbl->where('cfd.created_by', '=', $input_details_nad['assigned_to']);
        }
        $mdl_cfd_tbl = $mdl_cfd_tbl->orderBy('cfd.created_at', 'asc');
        $mdl_cfd_tbl = $mdl_cfd_tbl->limit(1);
        $mdl_cfd_tbl = $mdl_cfd_tbl->get();

        return $mdl_cfd_tbl;
    }

    public function get_date_of_offer_releaseddate_next( $input_details_nad ){
        
        $mdl_cfd_tbl = DB::table('candidate_followup_details as cfd');
        $mdl_cfd_tbl = $mdl_cfd_tbl->select('cfd.created_on');
        $mdl_cfd_tbl = $mdl_cfd_tbl->whereIn('cfd.cdID', $input_details_nad['cdID']);
        $mdl_cfd_tbl = $mdl_cfd_tbl->where('cfd.created_on', '>=', $input_details_nad['date_cond']);
        $mdl_cfd_tbl = $mdl_cfd_tbl->where('cfd.follow_up_status', '=', 'Offer Released');
        if (array_key_exists('assigned_to', $input_details_nad)) {
            $mdl_cfd_tbl = $mdl_cfd_tbl->where('cfd.created_by', '=', $input_details_nad['assigned_to']);
        }
        $mdl_cfd_tbl = $mdl_cfd_tbl->orderBy('cfd.created_at', 'asc');
        $mdl_cfd_tbl = $mdl_cfd_tbl->limit(1);
        $mdl_cfd_tbl = $mdl_cfd_tbl->get();

        return $mdl_cfd_tbl;
    }
    public function get_date_of_offer_accepteddate_next( $input_details_nad ){
        $mdl_cfd_tbl = DB::table('candidate_followup_details as cfd');
        $mdl_cfd_tbl = $mdl_cfd_tbl->select('cfd.created_on');
        $mdl_cfd_tbl = $mdl_cfd_tbl->whereIn('cfd.cdID', $input_details_nad['cdID']);
        $mdl_cfd_tbl = $mdl_cfd_tbl->where('cfd.created_on', '>=', $input_details_nad['date_cond']);
        $mdl_cfd_tbl = $mdl_cfd_tbl->where('cfd.follow_up_status', '=', 'Offer Accepted');
        if (array_key_exists('assigned_to', $input_details_nad)) {
            $mdl_cfd_tbl = $mdl_cfd_tbl->where('cfd.created_by', '=', $input_details_nad['assigned_to']);
        }
        $mdl_cfd_tbl = $mdl_cfd_tbl->orderBy('cfd.created_at', 'asc');
        $mdl_cfd_tbl = $mdl_cfd_tbl->limit(1);
        $mdl_cfd_tbl = $mdl_cfd_tbl->get();

        return $mdl_cfd_tbl;
    }

    public function get_date_of_candidate_onboarded_next( $input_details_nad ){
        $mdl_cfd_tbl = DB::table('candidate_followup_details as cfd');
        $mdl_cfd_tbl = $mdl_cfd_tbl->select('cfd.created_on');
        $mdl_cfd_tbl = $mdl_cfd_tbl->whereIn('cfd.cdID', $input_details_nad['cdID']);
        $mdl_cfd_tbl = $mdl_cfd_tbl->where('cfd.created_on', '>=', $input_details_nad['date_cond']);
        $mdl_cfd_tbl = $mdl_cfd_tbl->where('cfd.follow_up_status', '=', 'Candidate Onboarded');
        if (array_key_exists('assigned_to', $input_details_nad)) {
            $mdl_cfd_tbl = $mdl_cfd_tbl->where('cfd.created_by', '=', $input_details_nad['assigned_to']);
        }
        $mdl_cfd_tbl = $mdl_cfd_tbl->orderBy('cfd.created_at', 'asc');
        $mdl_cfd_tbl = $mdl_cfd_tbl->limit(1);
        $mdl_cfd_tbl = $mdl_cfd_tbl->get();

        return $mdl_cfd_tbl;
    }

    public function get_candidate_no_show_details( $input_details_nad ){
        
        $mdl_cfd_tbl = DB::table('candidate_followup_details as cfd');
        $mdl_cfd_tbl = $mdl_cfd_tbl->select('cfd.follow_up_status','cfd.created_on','cd.candidate_name');
        $mdl_cfd_tbl = $mdl_cfd_tbl->leftJoin( 'candidate_details as cd', 'cfd.cdID', '=', 'cd.cdID' );
        $mdl_cfd_tbl = $mdl_cfd_tbl->whereIn('cfd.cdID', $input_details_nad['cdID']);
        $mdl_cfd_tbl = $mdl_cfd_tbl->where('cfd.follow_up_status', '=', 'Candidate No Show');
        $mdl_cfd_tbl = $mdl_cfd_tbl->Where('cfd.follow_up_status', '=', 'Offer Rejected');
        $mdl_cfd_tbl = $mdl_cfd_tbl->Where('cfd.follow_up_status', '=', 'Candidate Abscond');
        if (array_key_exists('assigned_to', $input_details_nad)) {
            $mdl_cfd_tbl = $mdl_cfd_tbl->where('cfd.created_by', '=', $input_details_nad['assigned_to']);
        }
        $mdl_cfd_tbl = $mdl_cfd_tbl->orderBy('cfd.created_at', 'desc');
        $mdl_cfd_tbl = $mdl_cfd_tbl->limit(1);
        $mdl_cfd_tbl = $mdl_cfd_tbl->get();

        return $mdl_cfd_tbl;
    }

    public function get_original_rfh_date( $input_details_orfh ){
        $mdlrecruitmenttbl = DB::table('tbl_rfh')
        ->select('created_date')
        ->where('res_id', '=',  $input_details_orfh['rfh_no'])
        ->get();

        return $mdlrecruitmenttbl;
    }
    public function get_position_title( $input_details ){

        $mdlrecruitmenttbl = DB::table('recruitment_requests');
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->select('position_title');
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('rfh_no', '=',  $input_details['rfh_no']);
        $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('hepl_recruitment_ref_number', '=',  $input_details['hepl_recruitment_ref_number']);
        
        if (array_key_exists('assigned_to', $input_details)) {
            $mdlrecruitmenttbl = $mdlrecruitmenttbl->where('assigned_to', '=', $input_details['assigned_to']);
        }

        $mdlrecruitmenttbl = $mdlrecruitmenttbl->get();

        return $mdlrecruitmenttbl;
    }
}

?>