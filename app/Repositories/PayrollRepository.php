<?php

namespace App\Repositories;

use App\Models\Candidate_details;
use App\Models\Podetails;
use DB;

class PayrollRepository implements IPayrollRepository
{

    public function get_candidate_profile_ps( $input_details ){
        $mdlrecruitmenttbl = DB::table('candidate_details as cd')
        ->join('recruitment_requests as rr', 'cd.rfh_no', '=', 'rr.rfh_no', 'left outer')
        ->leftJoin('tat_details_tbls as tdt', 'rr.band', '=', 'tdt.id')
        ->leftJoin('users as us', 'cd.approver', '=', 'us.empID')
        ->leftJoin('offer_release_followup_details as orf', 'cd.cdID', '=', 'orf.cdID')

        ->select('cd.*','rr.position_title','rr.sub_position_title','tdt.band_title as band_title','us.name as approver','cd.approver as approver_id','orf.created_at as offer_date')

        ->where('cd.offer_rel_status', '=', $input_details['offer_rel_status'])
        ->where('rr.delete_status',  '=', "0")
        ->where('cd.candidate_status',  '=', "1")
        ->where('cd.doc_status', '=', 1)
        ->where('cd.payroll_status', '!=', 0)
        ->where('cd.po_type', '!=', "non_po")


        ->where(function ($query) {
            $query->where('cd.payroll_status', '=', 1)
          //  ->orwhere('cd.payroll_status', '!=', 5)
            ->orwhere('cd.po_finance_status', '=', 1)
            ->orwhere('cd.po_finance_status', '=', 3)
            ->orwhere('cd.leader_status', '=', 1)
            ->orwhere('cd.leader_status', '=', 3)
            ->orwhere('cd.po_file_status', '=', 1)
            ->orwhere('cd.po_file_status', '=',0);

        })

        ->orderBy('cd.updated_at', 'desc')
        ->groupBy('cd.cdID')
        ->get();

        return $mdlrecruitmenttbl;
    }
    public function get_non_po_qc_list( $input_details ){
        $mdlrecruitmenttbl = DB::table('candidate_details as cd')
        ->join('recruitment_requests as rr', 'cd.rfh_no', '=', 'rr.rfh_no', 'left outer')
        ->leftJoin('tat_details_tbls as tdt', 'rr.band', '=', 'tdt.id')
        ->leftJoin('users as us', 'cd.approver', '=', 'us.empID')
        ->leftJoin('offer_release_followup_details as orf', 'cd.cdID', '=', 'orf.cdID')

        ->select('cd.*','rr.position_title','rr.sub_position_title','tdt.band_title as band_title','us.name as approver','cd.approver as approver_id','orf.created_at as offer_date')

        ->where('cd.offer_rel_status', '=', $input_details['offer_rel_status'])
        ->where('rr.delete_status',  '=', "0")
        ->where('cd.candidate_status',  '=', "1")
        ->where('cd.doc_status', '=', 1)
       // ->where('cd.payroll_status', '=', 5)
        ->where('cd.leader_status', '=', 0)
        ->where(function ($query) {
            $query->where('cd.payroll_status', '=', 5)
            ->orwhere('cd.payroll_status', '=', 4);
        })

        ->orderBy('cd.created_at', 'desc')
        ->groupBy('cd.cdID')
        ->get();

        return $mdlrecruitmenttbl;
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

    public function candidate_follow_up_history( $input_details ){
        $mdlrecruitmenttbl = DB::table('candidate_followup_details as cfd')

        ->select('cfd.*')
        ->where('cfd.cdID', '=', $input_details['cdID'])
        ->orderBy('cfd.created_at', 'asc')
        ->get();

        return $mdlrecruitmenttbl;
    }
    public function get_offer_oat_date( $input_details_oat ){
        $offer_release_date = DB::table('offer_release_followup_details as ofd')

        ->select('ofd.*')
        ->where('ofd.cdID', '=', $input_details_oat['cdID'])
       // ->where('ofd.description', '=', 'Offer Ratification')
        ->orderBy('ofd.created_at', 'asc')
        ->get();

        return $offer_release_date;
    }
    public function get_offer_oat_offrat_date( $credentials ){
        $get_offer_list_af_details = DB::table('offer_release_followup_details as ofd')
        ->select('ofd.*')
        ->where('ofd.cdID', '=', $credentials['cdID'])
        ->where('ofd.rfh_no', '=', $credentials['rfh_no'])
        //->where('ofd.description', '=', 'Offer Ratification')
        ->orderBy('ofd.created_at', 'asc')
        ->limit(1)
        ->get();

        return $get_offer_list_af_details;
    }
    public function get_ctc_calculation( $input_details ){

        $mdlrecruitmenttbl = DB::table('ctc_calculations as cc')
        ->select('cc.*')
        ->where('cc.cdID', '=', $input_details['cdID'])
        ->orderBy('cc.created_at', 'desc')
        ->get();

        return $mdlrecruitmenttbl;
    }

    public function update_candidate_details($input_details){
        $update_mdlcdtbl = new Candidate_details();
        $update_mdlcdtbl = $update_mdlcdtbl->where( 'cdID', '=', $input_details['cdID'] );

        $update_mdlcdtbl->update( [
            'payroll_verify_type' => $input_details['payrol_verify_type'],
            'closed_salary_pa' => $input_details['closed_salary_pa'],
            'offer_letter_filename' => $input_details['offer_letter_filename'],

        ] );
    }

    public function process_orpop_status_ld($input_details){
        $update_mdlcdtbl = new Candidate_details();
        $update_mdlcdtbl = $update_mdlcdtbl->where( 'cdID', '=', $input_details['cdID'] );

        $update_mdlcdtbl->update( [
            'leader_status' => $input_details['leader_status'],
            'payroll_status' => $input_details['payroll_status'],

        ] );
    }

    public function get_approved_offers( $input_details ){
        $mdlrecruitmenttbl = DB::table('candidate_details as cd')
        ->join('recruitment_requests as rr', 'cd.rfh_no', '=', 'rr.rfh_no', 'left outer')
        ->leftJoin('tat_details_tbls as tdt', 'rr.band', '=', 'tdt.id')
        ->leftJoin('users as us', 'cd.approver', '=', 'us.empID')

        ->select('cd.*','rr.position_title','rr.sub_position_title','tdt.band_title as band_title','us.name as approved_by')
        ->where('cd.payroll_status', '!=', 0)
         ->where('cd.payroll_status', '!=', 1)
        // ->where('cd.leader_status', '=', $input_details['leader_status'])
        ->where('rr.delete_status',  '=', "0")
        ->where('cd.candidate_status',  '=', "1")
        ->orderBy('cd.created_at', 'desc')
        ->groupBy('cd.cdID')
        ->get();

        return $mdlrecruitmenttbl;
    }
    public function get_approved_nonpo_offers( $input_details ){
        $mdlrecruitmenttbl = DB::table('candidate_details as cd')
        ->join('recruitment_requests as rr', 'cd.rfh_no', '=', 'rr.rfh_no', 'left outer')
        ->leftJoin('tat_details_tbls as tdt', 'rr.band', '=', 'tdt.id')
        ->leftJoin('users as us', 'cd.approver', '=', 'us.empID')

        ->select('cd.*','rr.position_title','rr.sub_position_title','tdt.band_title as band_title','us.name as approved_by')
        ->where('cd.payroll_status', '=', $input_details['payroll_status'])
         ->where('cd.po_type', '=',"non_po")
        // ->where('cd.leader_status', '=', $input_details['leader_status'])
        ->where('rr.delete_status',  '=', "0")
        ->where('cd.candidate_status',  '=', "1")
        ->where('cd.offer_rel_status',  '!=', "1")
        ->orderBy('cd.created_at', 'desc')
        ->groupBy('cd.cdID')
        ->get();

        return $mdlrecruitmenttbl;
    }

    public function update_po_finance_status($input_details){
        $update_mdlcdtbl = new Candidate_details();
        $update_mdlcdtbl = $update_mdlcdtbl->where( 'cdID', '=', $input_details['cdID'] );

        $update_mdlcdtbl->update($input_details);
    }

    public function get_po_details($input_details_cd){
        $mdlrecruitmenttbl = DB::table('podetails as pd')
        ->select('pd.*')
        ->where('pd.cdID', '=', $input_details_cd['cdID'])
        ->orderBy('pd.created_at', 'desc')
        ->get();

        return $mdlrecruitmenttbl;
    }

    public function get_po_default_values(){

        $mdlrecruitmenttbl = DB::table('po_master_details as pmd')
        ->select('pmd.*')
        ->where('pmd.pmID', '=', 'PM01')
        ->orderBy('pmd.created_at', 'desc')
        ->get();

        return $mdlrecruitmenttbl;
    }

    public function submit_po_process($data){

        $reqtbl = new Podetails();
        $reqtbl->poID = 'PD'.str_pad( ( $reqtbl->max( 'id' )+1 ), 9, '0', STR_PAD_LEFT );
        $reqtbl->cdID = $data['cdID'];
        $reqtbl->rfh_no = $data['rfh_no'];
        $reqtbl->po_detail = $data['po_detail'];
        $reqtbl->po_description = $data['po_description'];
        $reqtbl->po_amount = $data['po_amount'];
        $reqtbl->created_by = $data['created_by'];
        $reqtbl->save();

        if($reqtbl) {
            return true;
        } else {
            return false;
        }
    }

    public function check_po_details( $input_details_cd ){
        $mdlrecruitmenttbl = DB::table('podetails as p')
        ->select('p.*')
        ->where('p.cdID', '=', $input_details_cd['cdID'])
        ->where('p.rfh_no', '=', $input_details_cd['rfh_no'])
        ->orderBy('p.created_at', 'desc')
        ->count();

        return $mdlrecruitmenttbl;
    }

    public function update_po_process( $input_details ){

        $update_mdlcdtbl = new Podetails();
        $update_mdlcdtbl = $update_mdlcdtbl->where( 'cdID', '=', $input_details['cdID'] );
        $update_mdlcdtbl = $update_mdlcdtbl->where( 'rfh_no', '=', $input_details['rfh_no'] );

        $update_mdlcdtbl->update( [
            'po_detail' => $input_details['po_detail'],
            'po_description' => $input_details['po_description'],
            'po_amount' => $input_details['po_amount'],
            'remark' => $input_details['remark'],
           // 'po_remark' => $input_details['po_remark'],
            'created_by' => $input_details['created_by'],
        ] );
    }
    public function update_po_process_oat( $input_details ){
        $update_mdlcdtbl = new Podetails();
        $update_mdlcdtbl = $update_mdlcdtbl->where( 'cdID', '=', $input_details['cdID'] );
        $update_mdlcdtbl = $update_mdlcdtbl->where( 'rfh_no', '=', $input_details['rfh_no'] );

        $update_mdlcdtbl->update( [
            'po_detail' => $input_details['po_detail'],
            'po_description' => $input_details['po_description'],
            'po_amount' => $input_details['po_amount'],
            'created_by' => $input_details['created_by'],
        ] );
    }

    public function update_poletter_fn( $input_details ){

        $update_mdlcdtbl = new Candidate_details();
        $update_mdlcdtbl = $update_mdlcdtbl->where( 'cdID', '=', $input_details['cdID'] );
        $update_mdlcdtbl = $update_mdlcdtbl->where( 'rfh_no', '=', $input_details['rfh_no'] );

        $update_mdlcdtbl->update( [
            'po_letter_filename' => $input_details['po_letter_filename'],
            'payroll_status' => $input_details['payroll_status'],
        ] );
    }

    public function check_offer_oat( $credentials ){

        $get_offer_list_af_details = DB::table('offer_release_followup_details as ofd')

        ->select('ofd.*')

        ->where('ofd.cdID', '=', $credentials['cdID'])

        ->where('ofd.rfh_no', '=', $credentials['rfh_no'])

        ->where('ofd.description', '=', 'Offer Ratification')

        ->orderBy('ofd.created_at', 'asc')

       ->count();



        return $get_offer_list_af_details;

    }
    public function update_client_type( $input_details ){

        $update_mdlcdtbl = new Candidate_details();
        $update_mdlcdtbl = $update_mdlcdtbl->where( 'cdID', '=', $input_details['cdID'] );
        $update_mdlcdtbl = $update_mdlcdtbl->where( 'rfh_no', '=', $input_details['rfh_no'] );

        $update_mdlcdtbl->update( [
            'client_type' => $input_details['client_type']
        ] );
        return $update_mdlcdtbl;
    }
    public function check_mail_details( $cdID ){
        $mdlrecruitmenttbl = DB::table('candidate_po_mail')
        ->select('*')
        ->where('cdID', '=', $cdID)
        ->get();

        return $mdlrecruitmenttbl;
    }
    public function insert_mail_form($input_data){
        $insert_mdlcdtbl = DB::table('candidate_po_mail');
      //  $insert_mdlcdtbl = $update_mdlcdtbl->where( 'cdID', '=', $input_details['cdID'] );
      $insert_mdlcdtbl->insert($input_data);
        // $insert_mdlcdtbl->insert( [
        //     'cdID' => $input_data['cdID'],
        //     'files' => $input_data['files'],
        // ] );
        return $insert_mdlcdtbl;
    }
    public function update_mail_form($data){
        $update_mdlcdtbl = DB::table('candidate_po_mail');
       $update_mdlcdtbl = $update_mdlcdtbl->where( 'cdID', '=', $data['cdID'] );
       // $update_mdlcdtbl->update($data);
        $update_mdlcdtbl->update( [
            'files' => $data['files']
        ] );
        return $update_mdlcdtbl;
    }
    public function update_mail_form_sub($data){
        $update_mdlcdtbl = DB::table('candidate_po_mail');
       $update_mdlcdtbl = $update_mdlcdtbl->where( 'cdID', '=', $data['cdID'] );
       // $update_mdlcdtbl->update($data);
        $update_mdlcdtbl->update( [

            'client_type'=>$data['client_type'],
        'to_mail'=>$data['to_mail'],
        'cc_mail'=>$data['cc_mail'],
        'subject'=>$data['subject'],
        'message'=>$data['message'],
        ] );
        return $update_mdlcdtbl;
    }

}

?>
