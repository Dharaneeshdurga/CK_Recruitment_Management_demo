<?php

namespace App\Http\Controllers;
use App\Models\Candidate_details;
use App\Models\Candidate_followup_details;
use DB;
use Illuminate\Http\Request;

class BudgieController extends Controller
 {
//     public function __construct(ICoordinatorRepository $corepo,ICandidateRepository $canrepo,IRecruiterRepository $recrepo)
//     {
//         $this->corepo = $corepo;
//         $this->canrepo = $canrepo;
//         $this->recrepo = $recrepo;

//     }

   /*public function change_doc_upload_status(Request $request){

    $cdID = $request->input('cdID');

     $update_roletbl = DB::table('candidate_details')->where( 'cdID', '=', $cdID);
                        $update_roletbl->update(['c_doc_upload_status' => '1']);
    if($update_roletbl){
        return response()->json(['response'=>'success']);
    }else{
     return response()->json(['response'=>'error']);
    }

   }
*/

   public function offer_response_candidate(Request $request){

     $cdID=$request->input('cdID');
//echo $cdID;

    $user_row = DB::table('candidate_details');
    $user_row = $user_row->where( 'cdID','=', $cdID );
     $user_row = $user_row->get();
     $candidate_email = $user_row[0]->candidate_email;
    $recruiter_id=$user_row[0]->created_by;


                $get_user_result = DB::table('users')
                ->select('*')
                ->where('empID', '=', $recruiter_id)
                ->orderBy('updated_at', 'asc')
                ->get();

     $recruit_email=$get_user_result[0]->email;


    $recruitment_requests = DB::table('recruitment_requests');
    $recruitment_requests = $recruitment_requests->where( 'hepl_recruitment_ref_number', '=', $user_row[0]->hepl_recruitment_ref_number );

    $recruitment_requests->update( [
        'closed_by' => $recruiter_id,
        'close_date' => date('Y-m-d'),
        'request_status' => "Closed"
    ] );

    $candidate_details = DB::table('candidate_details');
    $candidate_details = $candidate_details->where( 'cdID', '=', $cdID );

    $candidate_details->update( [
        'candidate_email' => $candidate_email,
        'status' => "Offer Accepted",
        'offer_rel_status' => "2"

    ] );


    $cfdID = 'CFD'.( ( DB::table( 'candidate_followup_details' )->max( 'id' ) )+1 );

    $candidate_followup_details = array(
        'cfdID'=>$cfdID,
        'cdID'=>$request->input('cdID'),
        'rfh_no'=>$user_row[0]->rfh_no,
        'follow_up_status'=>"Offer Accepted",
        'created_on'=>date('Y-m-d'),
        'created_by'=>$user_row[0]->created_by,
    );
    $other_result = DB::table( 'candidate_followup_details' )->insert( $candidate_followup_details );

    //Candidate_followup_details::create($candidate_followup_details);
            if($other_result && $candidate_details && $recruitment_requests){
                $response = 'Updated';
                return response()->json( ['response' => $response] );
            }
            else{
                $response = 'error';
                return response()->json( ['response' => $response] );
            }
}

 public function offer_reject_update(Request $request){
    $cdID=$request->input('cdID');

    $user_row = DB::table('candidate_details');
    $user_row = $user_row->where( 'cdID','=', $cdID );
     $user_row = $user_row->get();

    $offer_released_details =DB::table('offer_released_details');
    $offer_released_details = $offer_released_details->where( 'rfh_no', '=', $user_row[0]->rfh_no );
    $offer_released_details = $offer_released_details->where( 'cdID', '=', $cdID );

    $offer_released_details->update( [
        'profile_status' => "Offer Rejected",
        'remark' => $request->input('remark')

    ] );
    $candidate_details = DB::table('candidate_details');
    $candidate_details = $candidate_details->where( 'cdID', '=', $cdID );

    $candidate_details->update( [
       // 'candidate_email' => $candidate_email,
        'status' => "Offer Rejected",
        'offer_rel_status' => "3"

    ] );


    if($offer_released_details && $candidate_details){
        $response ="success";
        return response()->json( ['response' => $response] );

    }
    else{
        $response ="error";
        return response()->json( ['response' => $response] );
    }

    }
    public function upload_status(Request $request){

    $cdID = $request->input('cdID');

    $update = DB::table('candidate_details')->where( 'cdID', '=', $cdID);
    $update->update(['c_doc_upload_status' => 1]);
    // $check = DB::table( 'candidate_details' )
    //                     ->where('cdID', '=', $cdID)
    //                     ->where('c_doc_upload_status', '=', '0')
    //                     ->first();
    // if ($check) {
    //     $update = DB::table('candidate_details')->where( 'cdID', '=', $cdID);
    //     $update->update(['c_doc_upload_status' => 1]);
    // }
// echo $update;
    if($update){
        return response()->json(['response'=>'success']);
    }else{
     return response()->json(['response'=>'error']);
    }

   }

}
