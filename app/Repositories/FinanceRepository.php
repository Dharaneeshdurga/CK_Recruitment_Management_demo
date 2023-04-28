<?php

namespace App\Repositories;

use App\Models\Candidate_details;
use App\Models\Podetails;
use DB;

class FinanceRepository implements IFinanceRepository
{
    public function get_pending_po_request( $input_details ){
        $mdlrecruitmenttbl = DB::table('candidate_details as cd')
        ->join('recruitment_requests as rr', 'cd.rfh_no', '=', 'rr.rfh_no', 'left outer')
        ->leftJoin('tat_details_tbls as tdt', 'rr.band', '=', 'tdt.id')

        ->select('cd.*','rr.position_title','rr.sub_position_title','tdt.band_title as band_title')
        ->where('cd.po_finance_status', '=', $input_details['po_finance_status'])
        ->where('rr.delete_status',  '=', "0")
        ->where('cd.candidate_status',  '=', "1")
        ->orderBy('cd.updated_at', 'desc')
        ->groupBy('cd.cdID')
        ->get();

        return $mdlrecruitmenttbl;
    }

    public function update_finance_status_cd($input_details){
        $update_mdlcdtbl = new Candidate_details();
        $update_mdlcdtbl = $update_mdlcdtbl->where( 'cdID', '=', $input_details['cdID'] );

        $update_mdlcdtbl->update( $input_details);
    }

    public function get_approved_offers( $input_details ){
        $mdlrecruitmenttbl = DB::table('candidate_details as cd')
        ->join('recruitment_requests as rr', 'cd.rfh_no', '=', 'rr.rfh_no', 'left outer')
        ->leftJoin('tat_details_tbls as tdt', 'rr.band', '=', 'tdt.id')
        ->leftJoin('users as us', 'cd.approver', '=', 'us.empID')

        ->select('cd.*','rr.position_title','rr.sub_position_title','tdt.band_title as band_title','us.name as approved_by')
        // ->where('cd.payroll_status', '=', $input_details['payroll_status'])
        ->where('cd.po_finance_status', '=', $input_details['po_finance_status'])
        // ->where('cd.leader_status', '=', $input_details['leader_status'])
        ->where('rr.delete_status',  '=', "0")
        ->where('cd.candidate_status',  '=', "1")
        ->orderBy('cd.created_at', 'desc')
        ->groupBy('cd.cdID')
        ->get();

        return $mdlrecruitmenttbl;
    }



    public function get_pending_client_request( $input_details ){
        $mdlrecruitmenttbl = DB::table('candidate_details as cd')
        ->join('recruitment_requests as rr', 'cd.rfh_no', '=', 'rr.rfh_no', 'left outer')
        ->leftJoin('tat_details_tbls as tdt', 'rr.band', '=', 'tdt.id')

        ->select('cd.*','rr.position_title','rr.sub_position_title','tdt.band_title as band_title')
        ->where('cd.client_type', '=', $input_details['client_type'])
        ->where('cd.po_file_status',  '=', "0")
        ->where('rr.delete_status',  '=', "0")
        ->where('cd.candidate_status',  '=', "1")
        ->orderBy('cd.updated_at', 'desc')
        ->groupBy('cd.cdID')
        ->get();

        return $mdlrecruitmenttbl;
    }
    public function get_approved_client_request( $input_details ){
        $mdlrecruitmenttbl = DB::table('candidate_details as cd')
        ->join('recruitment_requests as rr', 'cd.rfh_no', '=', 'rr.rfh_no', 'left outer')
        ->leftJoin('tat_details_tbls as tdt', 'rr.band', '=', 'tdt.id')

        ->select('cd.*','rr.position_title','rr.sub_position_title','tdt.band_title as band_title')
        ->where('cd.client_type', '=', $input_details['client_type'])
        ->where('cd.po_file_status',  '=', "1")
        ->where('rr.delete_status',  '=', "0")
        ->where('cd.candidate_status',  '=', "1")
        ->orderBy('cd.created_at', 'desc')
        ->groupBy('cd.cdID')
        ->get();

        return $mdlrecruitmenttbl;
    }

}

?>
