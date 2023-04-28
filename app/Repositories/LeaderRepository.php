<?php

namespace App\Repositories;

use App\Models\Candidate_details;
use DB;

class LeaderRepository implements ILeaderRepository
{

    public function get_candidate_profile_ld( $input_details ){
        $mdlrecruitmenttbl = DB::table('candidate_details as cd')
        ->join('recruitment_requests as rr', 'cd.rfh_no', '=', 'rr.rfh_no', 'left outer')
        ->leftJoin('tat_details_tbls as tdt', 'rr.band', '=', 'tdt.id')

        ->select('cd.*','rr.position_title','rr.sub_position_title','tdt.band_title as band_title')
        ->where('cd.approver', '=', $input_details['approver'])
        ->where('cd.leader_status', '=', $input_details['leader_status'])
        ->where('rr.delete_status',  '=', "0")
        ->where('cd.candidate_status',  '=', "1")
        ->orderBy('cd.updated_at', 'desc')
        ->groupBy('cd.cdID')
        ->get();

        return $mdlrecruitmenttbl;
    }

    // public function get_approved_offers( $input_details ){
    //     $mdlrecruitmenttbl = DB::table('candidate_details as cd')
    //     ->join('recruitment_requests as rr', 'cd.rfh_no', '=', 'rr.rfh_no', 'left outer')
    //     ->leftJoin('tat_details_tbls as tdt', 'rr.band', '=', 'tdt.id')

    //     ->select('cd.*','rr.position_title','rr.sub_position_title','tdt.band_title as band_title')
    //     ->where('cd.created_by', '=', $input_details['created_by'])

    //     ->where('cd.leader_status', '=', $input_details['leader_status'])
    //     ->where('rr.delete_status',  '=', "0")
    //     ->where('cd.candidate_status',  '=', "1")
    //     ->orderBy('cd.created_at', 'desc')
    //     ->groupBy('cd.cdID')
    //     ->get();

    //     return $mdlrecruitmenttbl;
    // }
    public function update_approval_process($input_details){
        $update_mdlcdtbl = new Candidate_details();
        $update_mdlcdtbl = $update_mdlcdtbl->where( 'cdID', '=', $input_details['cdID'] );

        $update_mdlcdtbl->update($input_details);
    }

}

?>
