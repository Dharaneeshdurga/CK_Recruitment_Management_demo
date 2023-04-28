<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\IRecruiterRepository; 
use App\Repositories\IPayrollRepository; 
use App\Repositories\ICoordinatorRepository; 
use DataTables;
use DB;
use App\Models\Candidate_details;
use App\Models\Candidate_followup_details;
use DateTime;
use Mail;
use PDF;
use File;
class RecruiterController extends Controller
{
    public function __construct(IPayrollRepository $payrepo,IRecruiterRepository $recrepo,ICoordinatorRepository $corepo)
    {
        $this->recrepo = $recrepo;
        $this->corepo = $corepo;
        $this->payrepo = $payrepo;

        $this->middleware('recruiter');
    }

    public function view_task_detail(Request $request){

        if ($request->ajax()) {
            
            // get all data
            $get_assigned_to = auth()->user();

            $assigned_to = $get_assigned_to->empID;
            $current_date = date('Y-m-d');

            $input_details = array(
                'assigned_to'=>$assigned_to,
                'current_date'=>$current_date,
            );

            $get_reqcruitment_request_result = $this->recrepo->get_assigned_reqcruitment_request( $input_details );

            
            // $recruiter_list .= '<button class="btn btn-sm btn-primary" id="btnAssign">Assign</button>';
            return Datatables::of($get_reqcruitment_request_result)
                    ->addIndexColumn()
                    ->addColumn('ageing', function($row) {
                        
                        $from = strtotime($row->open_date);

                        $today = time();
                        $difference = $today - $from;
                        $difference_days = floor($difference / 86400);  // (60 * 60 * 24)

                        // $originalDate = $row->open_date;
                        // $currentDate = date("Y-m-d");

                        // $datetime1 = new DateTime($originalDate);

                        // $datetime2 = new DateTime($currentDate);

                        // $difference = $datetime1->diff($datetime2);

                        $ageing = $difference_days;
                        // $ageing = $difference->y.' years, ' .$difference->m.' months, '.$difference->d.' days';

                        return $ageing;
                    })
                    ->addColumn('open_date', function($row) {
                        
                        $originalDate = $row->open_date;
                        $newDate = date("d-m-Y", strtotime($originalDate));

                        return $newDate;
                    })
                    ->addColumn('assigned_date', function($row) {
                        
                        $originalDate = $row->updated_at;
                        $newDate = date("d-m-Y", strtotime($originalDate));

                        return $newDate;
                    })
                    ->addColumn('request_status', function($row) {
                        
                        if($row->request_status =='Open'){
                            $btn1 = '<span class="badge bg-warning" title="Open"><i class="fa fa-book-open"></i></span>';
                        }
                        else if($row->request_status =='Closed'){
                            $btn1 = '<span class="badge bg-success" title="Closed"><i class="fa fa-book"></i></span>';
                        }
                        else if($row->request_status =='On Hold'){
                            $btn1 = '<span class="badge bg-onhold" title="On Hold"><i class="bi bi-pause-fill"></i></span>';
                        }
                        else if($row->request_status =='Re Open'){
                            $btn1 = '<span class="badge bg-dark" title="Re Open"><i class="bi bi-exclude"></i></span>';
                        }

                        return $btn1;
                    })
                   
                    ->addColumn('action', function($row) {

                        if($row->request_status == 'Closed'){
                            $status_btn = '<button type="button" class="btn btn-primary btn-sm" disabled onclick="process_actionday(this,'."'".$row->hepl_recruitment_ref_number."'".','."'".$row->rfh_no."'".');"  title="Fresh Profiles submitted"><i class="bi bi-file-earmark-medical-fill"></i></button>';

                        }else{
                            $status_btn = '<button type="button" class="btn btn-primary btn-sm" data-position_title="'.$row->position_title.'" onclick="process_actionday(this,'."'".$row->hepl_recruitment_ref_number."'".','."'".$row->rfh_no."'".' );"  title="Fresh Profiles submitted"><i class="bi bi-file-earmark-medical-fill"></i></button>';

                        }
                        
                        $btn = '<button class="btn btn-sm btn-success" id="btnHistory" style="margin-top:2px;" data-position_title="'.$row->position_title.'" onclick="view_history(this,'."'".$row->hepl_recruitment_ref_number."'".','."'".$row->rfh_no."'".');" title="Edit"><i class="bi bi-pen-fill"></i></button>';
                        
                        return $status_btn." ".$btn;
                    })

                    ->addColumn('tat_process', function($row) {
                        $from = strtotime($row->open_date);

                        $today = time();
                        $difference = $today - $from;
                        $difference_days = floor($difference / 86400);  // (60 * 60 * 24)


                        // $originalDate = $row->open_date;
                        // $currentDate = date("Y-m-d");

                        // $datetime1 = new DateTime($originalDate);

                        // $datetime2 = new DateTime($currentDate);

                        // $difference = $datetime1->diff($datetime2);

                        $ageing = $difference_days;
                        $ageing_end = 15;

                        // if($row->critical_position =='Yes'){
                        //     $ageing_end = 15;

                            
                        // }else if($row->critical_position =='Nill'){
                        //     $ageing_end = 15;

                        // }
                        // else{
                        //     $ageing_end = $row->tat_days;

                        // }

                        if($ageing >= $ageing_end){
                            return "show_tat_highlight";
                        }
                        else{
                            return "hide_tat_highlight";
                        }

                        
                    })
                    ->addColumn('interviewer', function($row){
                        $get_interviewer = $row->interviewer;
                        
                        if($get_interviewer =='Self' || $get_interviewer =='SELF' || $get_interviewer =='self'){
                            $get_interviewer_self = $this->recrepo->get_interviewer_self( $row->rfh_no );

                            $get_interviewer = $get_interviewer_self[0]->name;
                        }
                        return $get_interviewer;

                    })
                    ->rawColumns(['tat_process','ageing','assigned_date','open_date','action','request_status','action_for_the_day_status','interviewer'])
                    ->make(true);
        }
        
        return view('view_task_detail');

    }

    public function upload_cvprocess(Request $request){
       
        $this->validate($request, [
            'candidate_name' => 'required',
            'candidate_cv' => 'required',
            'candidate_cv.*' => 'required|mimes:doc,pdf,docx,zip'
        ]);

        $get_assigned_to = auth()->user();
        $created_by = $get_assigned_to->empID;

        if($request->hasfile('candidate_cv')){
            $candidate_name = $request->candidate_name;
            $candidate_source = $request->candidate_source;
            $gender = $request->gender;
    
            $arr_lp = 0;
            foreach($request->file('candidate_cv') as $file){
                $cd_id = 'CD'.( ( DB::table( 'candidate_details' )->max( 'id' ) )+1 );
                $cv_name = $cd_id.time().'.'.$file->extension();
                $file->move(public_path().'/cv_upload/', $cv_name);  

                $input_details = array(
                    'cdID'=>$cd_id,
                    'status'=>$request->action_for_the_day,
                    'hepl_recruitment_ref_number'=>$request->hepl_recruitment_ref_number,
                    'rfh_no'=>$request->cv_up_rfh_no,
                    'candidate_name'=>$candidate_name[$arr_lp],
                    'candidate_source'=>$candidate_source[$arr_lp],
                    'gender'=>$gender[$arr_lp],
                    'created_on'=>date('Y-m-d'),
                    'created_by'=>$created_by,
                    'candidate_cv'=>$cv_name,
                    'red_flag_status'=>"0",
                    'candidate_status'=>"1"
                );
                
                Candidate_details::create($input_details);

                $cfdID = 'CFD'.( ( DB::table( 'candidate_followup_details' )->max( 'id' ) )+1 );

                $candidate_followup_details = array(
                    'cfdID'=>$cfdID,
                    'cdID'=>$cd_id,
                    'rfh_no'=>$request->cv_up_rfh_no,
                    'follow_up_status'=>$request->action_for_the_day,
                    'created_on'=>date('Y-m-d'),
                    'created_by'=>$created_by
                );
                Candidate_followup_details::create($candidate_followup_details);

                $arr_lp++;
            }

            $input_details = array(
                'cdID'=>$cd_id,

                'action_for_the_day_status'=>$request->action_for_the_day,
                'hepl_recruitment_ref_number'=>$request->hepl_recruitment_ref_number,
                'assigned_to'=>$created_by,
    
            );
    
            $process_default_status_result = $this->recrepo->process_default_status( $input_details );

            return response()->json(['success'=>"success"]);

        }
            return response()->json(['error'=>"error"]);

    

    }

    public function show_uploaded_cv(Request $req){
        $get_user_details = auth()->user();
        $role_type = $get_user_details->role_type;
        $created_by = $get_user_details->empID;
        
        $hepl_recruitment_ref_number=$req->input( 'hepl_recruitment_ref_number' );

        if($role_type =='recruiter'){
            $input_details = array(
                'hepl_recruitment_ref_number'=>$hepl_recruitment_ref_number,
                'created_by'=>$created_by,
    
            );

            $show_uploaded_cv_result = $this->recrepo->show_uploaded_cv_recruiter( $input_details );
        }
        else{
            $show_uploaded_cv_result = $this->recrepo->show_uploaded_cv( $hepl_recruitment_ref_number );
        }
        

        return Datatables::of($show_uploaded_cv_result)
                    ->addIndexColumn()
                    ->addColumn('status', function($row) {
                        
                        $btn = $row->status;
                        return $btn;
                    })
                    ->addColumn('follow_up', function($row) {
                        
                        $btn = '<span class="badge bg-secondary" title="Followup History" onclick="candidate_follow_up('."'".$row->created_by."'".','."'".$row->hepl_recruitment_ref_number."'".','."'".$row->cdID."'".','."'".$row->candidate_name."'".');"><i class="bi bi-stack"></i></span>';
                        return $btn;
                    })

                    ->addColumn('candidate_cv', function($row) {
                        
                        $btn = '<a href="../cv_upload/'.$row->candidate_cv.'" class="badge bg-info" title="Preview CV" target="_blank"><i class="bi bi-eye"></i></a>';
                        return $btn;
                    })
                    ->addColumn('updated_on', function($row) {
                        
                        $originalDate = $row->created_on;
                        $created_on = date("d-m-Y", strtotime($originalDate));

                        return $created_on;
                    })
                    ->addColumn('ageing', function($row) {
                        
                        $from = strtotime($row->created_on);

                        $today = time();
                        $difference = $today - $from;
                        $difference_days = floor($difference / 86400);  // (60 * 60 * 24)


                        // $originalDate = $row->created_on;
                        // $currentDate = date("Y-m-d");

                        // $datetime1 = new DateTime($originalDate);

                        // $datetime2 = new DateTime($currentDate);

                        // $difference = $datetime1->diff($datetime2);

                        $ageing = $difference_days;
                        // $ageing = $difference->y.' years, ' .$difference->m.' months, '.$difference->d.' days';

                        return $ageing;

                    })
                    ->addColumn('action', function($row) {
                        
                        // if($row->status =='Candidate Onboarded'){
                        //     $status_btn = '<select name="cv_action_for_the_day" id="cv_action_for_the_day_'.$row->cdID.'" class="form-control" disabled onchange="cv_process_actionday(this,'."'".$row->cdID."'".','."'".$row->candidate_name."'".','."'".$row->hepl_recruitment_ref_number."'".');">';
                        // }
                        // else{
                        //     $status_btn = '<select name="cv_action_for_the_day" id="cv_action_for_the_day_'.$row->cdID.'" class="form-control" onchange="cv_process_actionday(this,'."'".$row->cdID."'".','."'".$row->candidate_name."'".','."'".$row->hepl_recruitment_ref_number."'".');">';
                        // }

                        $status_btn = '<select name="cv_action_for_the_day" id="cv_action_for_the_day_'.$row->cdID.'" class="form-control" onchange="cv_process_actionday(this,'."'".$row->cdID."'".','."'".$row->candidate_name."'".','."'".$row->hepl_recruitment_ref_number."'".');">';

                        $status_btn .= '<option value="">Select</option>';
                        // $status_btn .= '<option value="Fresh Profile Submitted">Fresh Profile Submitted</option>';
                        // $status_btn .= '<option value="Profiles Shortlist On-going">Profiles Shortlist On-going</option>';
                        // $status_btn .= '<option value="Profiles shared for Screening with Interviewing Manager">Profiles shared for Screening with Interviewing Manager</option>';
                        // $status_btn .= '<option value="Interview Ongoing">Interview Ongoing</option>';
                        // $status_btn .= '<option value="CTC Negotiation on">CTC Negotiation on</option>';
                        // $status_btn .= '<option value="Offer Released">Offer Released</option>';
                        // $status_btn .= '<option value="Offer Accepted">Offer Accepted</option>';
                        // $status_btn .= '<option value="Offer Rejected">Offer Rejected</option>';
                        // $status_btn .= '<option value="Candidate Onboarded">Candidate Onboarded</option>';
                        // $status_btn .= '<option value="Profile Rejected">Profile Rejected</option>';
                        // $status_btn .= '<option value="Candidate No Show">Candidate No Show</option>';
                        $status_btn .= '<option value="Profile submitted to Hiring Manager">Profile submitted to Hiring Manager</option>';
                        $status_btn .= '<option value="Profile Rejected">Profile Rejected</option>';
                        $status_btn .= '<option value="Profile on Hold">Profile on Hold</option>';
                        $status_btn .= '<option value="Sourcing Profiles again after profile rejection">Sourcing Profiles again after profile rejection</option>';
                        $status_btn .= '<option value="Interview scheduled with Hiring Manager">Interview scheduled with Hiring Manager</option>';
                        $status_btn .= '<option value="Profile shortlisted by Hiring Manager">Profile shortlisted by Hiring Manager</option>';
                        $status_btn .= '<option value="CTC Negotiation">CTC Negotiation</option>';
                        $status_btn .= '<option value="Document Collection">Document Collection</option>';
                       // $status_btn .= '<option value="Offer Released">Offer Released</option>';
                       // $status_btn .= '<option value="Offer Accepted">Offer Accepted</option>';
                        $status_btn .= '<option value="Offer Rejected">Offer Rejected</option>';
                        $status_btn .= '<option value="Candidate Onboarded"> Candidate Onboarded</option>';
                        $status_btn .= '<option value="Candidate No Show">Candidate No Show</option>';
                        $status_btn .= '<option value="Candidate Abscond">Candidate Abscond</option>';
                        
                        $status_btn .= '</select>';
                        
                        $status_btn .='<span id="cd_span_'.$row->cdID.'" style="display:none;">Closed Date</span><input type="date" name="offer_accep_date" id="offer_accep_date_'.$row->cdID.'" value="'.date('Y-m-d').'" class="form-control" style="display:none;">';
                        
                        $status_btn .='<span id="oa_email_span_'.$row->cdID.'" style="display:none;">Candidate Email</span><input type="text" name="oa_candidate_email" id="oa_candidate_email_'.$row->cdID.'" value="" class="form-control" style="display:none;">';
                        
                        $status_btn .='<span id="oa_candi_type_span_'.$row->cdID.'" style="display:none;">Candidate Type</span><select class="form-control" style="display:none;" id="oa_candidate_type_'.$row->cdID.'" name="oa_candidate_type"><option value="">Select</option><option value="Fresher">Fresher</option><option value="Experienced">Experienced</option></select>';

                        $btn ='  <button class="btn btn-sm btn-primary btnDefaultSubmit" style="margin-top:5px;display:none;" id="btnDefaultSubmit_'.$row->cdID.'" onclick="process_default_status('."'".$row->cdID."'".','."'".$row->hepl_recruitment_ref_number."'".');" >Submit</button>';


                        return $status_btn." ".$btn;
                    })
                    ->rawColumns(['candidate_cv','status','action','follow_up','updated_on'])
                    ->make(true);
    }

    public function process_default_status(Request $request){
        
        $get_assigned_to = auth()->user();
        $assigned_to = $get_assigned_to->empID;
        
        // $cc1="karthikdavid@hemas.in";
        // $cc2="rfh@hemas.in";
        $cc1="durgadevi.r@hemas.in";
        $cc2="rfh@hemas.in";

        $history_rfh_no = $request->input('history_rfh_no');
        if($request->input('action_for_the_day') =='Offer Accepted' ||$request->input('action_for_the_day') =='Candidate Onboarded'){
            
            // update position status as closed 
            if($request->input('offer_accep_date') ==''){
                $offer_accep_date = date('Y-m-d');
            }else{
                $offer_accep_date = $request->input('offer_accep_date');

            }

            if($request->input('action_for_the_day') =='Offer Accepted'){
                
                $candidate_email = $request->input('candidate_email');

                $cdID=$request->input('cdID');
                $user_row = $this->recrepo->get_candidate_row( $cdID );

              //  $to_email=$candidate_email;
              $to_email=$user_row[0]->candidate_email;
//print_r($user_row);
                $get_title = "ProHire - Confirmation";
                $get_body1 = "Dear ".$user_row[0]->candidate_name.",";
                $get_body2 ='Congrats & Welcome to our family!';
                $get_body3 ='Thank you for your confirmation and offer acceptance.';
                $get_body4 ='Our On-boarding team will contact you for further joining formalities.';
                $get_body5 ='All the very best';
                $details = [
                    'title' => $get_title,
                    'body1' => $get_body1,
                    'body2' => $get_body2,
                    'body3' => $get_body3,
                    'body4' => $get_body4,
                    'body5' => $get_body5
                ];
              //  \Mail::to($to_email)->send(new \App\Mail\CandidateMail($details));
           //  echo $to_email;
           //  echo $cc1;
           //  echo $cc2;
           //->cc([$cc1,$cc2])
              \Mail::to($to_email)
                    ->cc(['karthikdavid@hemas.in','rfh@hemas.in'])
                    ->send(new \App\Mail\CandidateMail($details));

            }else{
                $candidate_email = '';
            }
            $credentials = array(
                'action_for_the_day_status'=>$request->input('action_for_the_day'),
                'hepl_recruitment_ref_number'=>$request->input('hepl_recruitment_ref_number'),
                'closed_date'=>$offer_accep_date,
                'request_status'=>"Closed",
                'closed_by'=>$get_assigned_to->empID
            );
            $update_position_status_result = $this->recrepo->update_position_status_closed( $credentials );

            $input_details = array(
                'action_for_the_day_status'=>$request->input('action_for_the_day'),
                'hepl_recruitment_ref_number'=>$request->input('hepl_recruitment_ref_number'),
                'cdID'=>$request->input('cdID'),
                'assigned_to'=>$assigned_to,
                'candidate_email'=>$candidate_email,
    
            );
    
            $process_default_status_result = $this->recrepo->process_default_status( $input_details );

        }
        
        else{

            if($request->input('action_for_the_day') =='Document Collection'){
                
                $candidate_email = $request->input('candidate_email');
                $candidate_type = $request->input('candidate_type');
                $doc_status = '1';
            }else{
                $candidate_email='';
                $doc_status = '';
                $candidate_type ='';
            }

            $input_details = array(
                'candidate_email'=>$candidate_email,
                'action_for_the_day_status'=>$request->input('action_for_the_day'),
                'hepl_recruitment_ref_number'=>$request->input('hepl_recruitment_ref_number'),
                'cdID'=>$request->input('cdID'),
                'assigned_to'=>$assigned_to,
                'candidate_type'=>$candidate_type,
                'doc_status'=>$doc_status,
    
            );
    
            $process_default_status_result = $this->recrepo->process_default_status( $input_details );

            if($doc_status == 1){

                $cdID=$request->input('cdID');
                $user_row = $this->recrepo->get_candidate_row( $cdID );

                $to_email=$candidate_email;

                $doc_upload_link =  url('/')."/candidate_dc/".base64_encode($cdID);
                $get_body4 = $doc_upload_link;
                $details = [
                    'candidate_name' => $user_row[0]->candidate_name,
                    'doc_upload_link' => $doc_upload_link,
                    
                ];
                //->cc([$cc1,$cc2])
                \Mail::to($to_email)     
                ->cc(['karthikdavid@hemas.in','rfh@hemas.in'])
                ->send(new \App\Mail\NotifyCandidateDocUploadMail($details));
            }
        }

        if($request->input('action_for_the_day') =='Candidate Abscond' || $request->input('action_for_the_day') =='Candidate No Show' || $request->input('action_for_the_day') =='Offer Rejected'){
            $credentials = array(
                'cdID'=>$request->input('cdID'),
                'rfh_no'=>$history_rfh_no,
                'hepl_recruitment_ref_number'=>$request->input('hepl_recruitment_ref_number'),
                'request_status'=>"Re Open",
                'open_date'=>date('Y-m-d'),
                'assigned_date'=>date('Y-m-d')
            );
            $update_request_status_result = $this->recrepo->update_request_status( $credentials );
        }

        if($request->input('action_for_the_day') =='Profile Rejected'){
            $credentials = array(
                'cdID'=>$request->input('cdID'),
                'rfh_no'=>$history_rfh_no,
                'profile_status'=>$request->input('action_for_the_day')
            );
            $update_position_status_orldj_result = $this->recrepo->update_position_status_orldj( $credentials );
        }

       
        // update candidate follow up
        
        $cfdID = 'CFD'.( ( DB::table( 'candidate_followup_details' )->max( 'id' ) )+1 );

        $candidate_followup_details = array(
            'cfdID'=>$cfdID,
            'cdID'=>$request->input('cdID'),
            'rfh_no'=>$history_rfh_no,
            'follow_up_status'=>$request->input('action_for_the_day'),
            'created_on'=>date('Y-m-d'),
            'created_by'=>$assigned_to
        );
        Candidate_followup_details::create($candidate_followup_details);

        $response = 'Updated';
        return response()->json( ['response' => $response] );
    }

    public function process_offer_release_details(Request $request){
        
        $get_assigned_to = auth()->user();
        $created_by = $get_assigned_to->empID;

        // check details already exits
        $input_details = array(
            'cdID'=>$request->input('or_cdID'),
            'hepl_recruitment_ref_number'=>$request->input('or_hepl_recruitment_ref_number'),
        );

        $check_details_exits = $this->recrepo->check_offer_release_details( $input_details );
        
        if($check_details_exits == 0){
            // insert process
            $input_details = array(
                'cdID'=>$request->input('or_cdID'),
                'rfh_no'=>$request->input('or_rfh_no'),
                'profile_status'=>$request->input('or_action_for_the_day'),
                'hepl_recruitment_ref_number'=>$request->input('or_hepl_recruitment_ref_number'),
                // 'closed_date'=>$request->input('or_closed_date'),
                'closed_salary'=>$request->input('or_closed_salary'),
                'salary_review'=>$request->input('or_salary_review'),
                'joining_type'=>$request->input('or_joining_type'),
                'date_of_joining'=>$request->input('or_doj'),
                'remark'=>$request->input('or_remark'),
                'created_by'=>$created_by,
                'created_on'=>date('Y-m-d')

            );

            $process_offer_release_details_result = $this->recrepo->process_offer_release_details( $input_details );
            
            // update status in candidate details table
            $input_details_cd = array(
                'cdID'=>$request->input('or_cdID'),
                'status'=>"Offer Released"

            );

            $cd_status_update = $this->recrepo->cd_status_update( $input_details_cd );

            // update candidate follow up
            $cfdID = 'CFD'.( ( DB::table( 'candidate_followup_details' )->max( 'id' ) )+1 );

            $candidate_followup_details = array(
                'cfdID'=>$cfdID,
                'cdID'=>$request->input('or_cdID'),
                'rfh_no'=>$request->input('rfh_no'),
                'follow_up_status'=>"Offer Released",
                'created_on'=>date('Y-m-d'),
                'created_by'=>$created_by
            );
            Candidate_followup_details::create($candidate_followup_details);

            $response = 'Updated';
        }else{
            $response = 'already_exits';
        }
        
        return response()->json( ['response' => $response] );
    }

    public function get_offer_released_tb(Request $req){

        $assigned_to = auth()->user();
        $assigned_to = $assigned_to->empID;
        
        $input_details = array(
            'assigned_to'=>$assigned_to,
            // 'joining_type'=>"Later Date",
            'request_status'=>"Open",
            'profile_status_1'=>"Profile Rejected",
            'profile_status_2'=>"Candidate Onboarded"
        );

        $get_offer_released_tb_result = $this->recrepo->get_offer_released_tb( $input_details );

        return Datatables::of($get_offer_released_tb_result)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                
                if($row->joining_type =='Later Date'){
                    $btn = '<span class="badge bg-primary" data-position_title="'.$row->position_title.'" title="Edit Later Date Join Action" onclick="edit_offer_released(this,'."'".$row->cdID."'".','."'".$row->candidate_name."'".','."'".$row->hepl_recruitment_ref_number."'".','."'".$row->rfh_no."'".');"><i class="bi bi-pen-fill"></i></span>';

                }else{
                    $btn = '';

                }
                
                $btn_select = '<span class="badge bg-success" title="Candidate Onboard" onclick="or_ldj_onboard('."'".$row->cdID."'".','."'".$row->rfh_no."'".','."'".$row->hepl_recruitment_ref_number."'".');"><i class="bi bi-person-badge-fill"></i></span>';
                
                
                $btn_1 = '<span class="badge bg-info" id="btnHistory" style="margin-top:2px;" title="Edit Action" data-position_title="'.$row->position_title.'" onclick="view_history(this,'."'".$row->hepl_recruitment_ref_number."'".','."'".$row->rfh_no."'".');"><i class="bi bi-pencil-square"></i></span>';
                

                // $btn_1 = '<button class="btn btn-sm btn-success" id="btnHistory" style="margin-top:2px;" data-position_title="'.$row->position_title.'" onclick="view_history(this,'."'".$row->hepl_recruitment_ref_number."'".','."'".$row->rfh_no."'".');" title="Edit Action"><i class="bi bi-pencil-square"></i></button>';

                return $btn."  ".$btn_select." ".$btn_1;


            })

            ->addColumn('followup_history', function($row) {
                        
                $btn = '<span class="badge bg-secondary" title="Followup History" onclick="candidate_follow_up('."'".$row->created_by."'".','."'".$row->hepl_recruitment_ref_number."'".','."'".$row->cdID."'".','."'".$row->candidate_name."'".');"><i class="bi bi-stack"></i></span>';
                return $btn;
            })
            ->addColumn('candidate_cv', function($row) {
                        
                $btn = '<a href="../cv_upload/'.$row->candidate_cv.'" class="badge bg-info" target="_blank"><i class="bi bi-eye"></i></a>';
                return $btn;
            })

            ->addColumn('closed_date', function($row) {
                        
                $originalDate = $row->closed_date;
                $closed_date = date("d-m-Y", strtotime($originalDate));

                $close_edit = '<button type="button" class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-edit"></i></button>';
                    $close_edit .= '<div class="dropdown-menu px-2 py-2" style="background: #d6f6fc;">';
                            $close_edit .= '<div class="form-group">';
                                $close_edit .= '<label for="close_date_edit">Close Date</label>';
                                $close_edit .= '<input type="date" class="form-control" id="close_date_edit_'.$row->rr_id.'" name="close_date_edit_'.$row->rr_id.'">';
                                $close_edit .= '<input type="hidden" class="form-control" id="hrr_cd_'.$row->rr_id.'" name="hrr_cd_'.$row->rr_id.'" value="'.$row->hepl_recruitment_ref_number .'">';
                            $close_edit .= '</div>';
                        $close_edit .= '<button type="button" class="btn btn-primary" id="cd_updatebtn_'.$row->rr_id.'" onclick=process_closedate('."'".$row->rr_id."'".');>Update</button>';
                    $close_edit .= '</div>';
                    

                return $closed_date." ".$close_edit;
            })
        
            ->addColumn('date_of_joining', function($row) {
                        
                $originalDate = $row->date_of_joining;
                $date_of_joining = date("d-m-Y", strtotime($originalDate));

                return $date_of_joining;
            })
            ->rawColumns(['followup_history','candidate_cv','action','closed_date','date_of_joining'])
            ->make(true);
    }

    public function offer_released_edit_process(Request $req){

        $get_assigned_to = auth()->user();
        $created_by = $get_assigned_to->empID;


        if($req->input('initiate_backfil') ==''){
            $initiate_backfil ='No';
        }
        else{
            $initiate_backfil ='Yes';
        }
        $input_details = array(
            'cdID'=>$req->input('or_ldj_cdID'),
            'rfh_no'=>$req->input('or_ldj_rfh_no'),
            'or_ldj_hepl_recruitment_ref_number'=>$req->input('or_ldj_hepl_recruitment_ref_number'),
            'orladj_resignation_received'=>$req->input('orladj_resignation_received'),
            'orladj_touchbase'=>$req->input('orladj_touchbase'),
            'initiate_backfil'=>$initiate_backfil,
            'created_by'=>$created_by,
            'created_on'=>date('Y-m-d')
        );

        $or_edit_result = $this->recrepo->offer_released_edit_process( $input_details );

        if($req->input('orladj_touchbase') == 'Red flag'){
            $rfs_input_details = array(
                'cdID'=>$req->input('or_ldj_cdID'),
                'red_flag_status'=>"1"
            );
    
            $rfs_result = $this->recrepo->update_red_flag( $rfs_input_details );
        }

        // if($initiate_backfil == 'Yes'){

        //     $ib_input_details = array(
        //         'rfh_no'=>$req->input('or_ldj_rfh_no'),
        //         'request_status'=>"Re Open"
        //     );

        //     $initiate_backfil_res = $this->recrepo->initiate_backfil_reopen( $ib_input_details );
        // }
        $response = 'Updated';
        return response()->json( ['response' => $response] );
        
    }

    public function or_ldj_history(Request $req){
        
        $assigned_to = auth()->user();
        $created_by = $assigned_to->empID;
        
        $input_details = array(
            'created_by'=>$created_by,
            'cdID'=>$req->input('cdID'),
        );

        $or_ldj_history_result = $this->recrepo->or_ldj_history( $input_details );

        return $or_ldj_history_result;

    }

    // old positions 
    public function get_assigned_recruitment_request_oldlist(Request $req){

            
        // get all data
        $get_assigned_to = auth()->user();
        $assigned_to = $get_assigned_to->empID;

        $current_date = date('Y-m-d');

        $input_details = array(
            'assigned_to'=>$assigned_to,
            'current_date'=>$current_date
        );

        $get_reqcruitment_request_result = $this->recrepo->get_assigned_reqcruitment_request_old_positions( $input_details );

            
        return Datatables::of($get_reqcruitment_request_result)
            ->addIndexColumn()
            ->addColumn('ageing', function($row) {
                $from = strtotime($row->open_date);

                $today = time();
                $difference = $today - $from;
                $difference_days = floor($difference / 86400);  // (60 * 60 * 24)

                // $originalDate = $row->open_date;
                // $currentDate = date("Y-m-d");

                // $datetime1 = new DateTime($originalDate);

                // $datetime2 = new DateTime($currentDate);

                // $difference = $datetime1->diff($datetime2);

                $ageing = $difference_days;
                // $ageing = $difference->y.' years, ' .$difference->m.' months, '.$difference->d.' days';

                return $ageing;
            })
            ->addColumn('close_date', function($row) {

                if($row->request_status =='Closed'){

                    $originalDate_cd = $row->close_date;
                    $closed_date = date("d-m-Y", strtotime($originalDate_cd));

                    $close_edit = '<button type="button" class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-edit"></i></button>';
                    $close_edit .= '<div class="dropdown-menu px-2 py-2" style="background: #d6f6fc;">';
                            $close_edit .= '<div class="form-group">';
                                $close_edit .= '<label for="close_date_edit">Close Date</label>';
                                $close_edit .= '<input type="date" class="form-control" id="close_date_edit_'.$row->id.'" name="close_date_edit">';
                                $close_edit .= '<input type="hidden" class="form-control" id="hrr_cd_'.$row->id.'" name="hrr_cd_'.$row->id.'" value="'.$row->hepl_recruitment_ref_number .'">';

                            $close_edit .= '</div>';
                        $close_edit .= '<button type="button" class="btn btn-primary" id="cd_updatebtn_'.$row->id.'" onclick=process_closedate('."'".$row->id."'".');>Update</button>';
                    $close_edit .= '</div>';
                    
                    return $closed_date." ".$close_edit;

                }else{
                    $closed_date = '-';

                    return $closed_date;

                }

                

            })

            ->addColumn('open_date', function($row) {
                        
                $originalDate = $row->open_date;
                $newDate = date("d-m-Y", strtotime($originalDate));

                return $newDate;
            })
            ->addColumn('assigned_date', function($row) {
                        
                $originalDate = $row->assigned_date;
                $newDate = date("d-m-Y", strtotime($originalDate));

                return $newDate;
            })
            ->addColumn('request_status', function($row) {
                  
                
                if($row->request_status =='Open'){
                    $btn1 = '<span class="badge bg-warning" title="Open"><i class="fa fa-book-open"></i></span>';
                }
                else if($row->request_status =='Closed'){
                    $btn1 = '<span class="badge bg-success" title="Closed"><i class="fa fa-book"></i></span>';
                }
                else if($row->request_status =='On Hold'){
                    $btn1 = '<span class="badge bg-onhold" title="On Hold"><i class="bi bi-pause-fill"></i></span>';
                }
                else if($row->request_status =='Re Open'){
                    $btn1 = '<span class="badge bg-dark" title="Re Open"><i class="bi bi-exclude"></i></span>';
                }

                return $btn1;
            })
            ->addColumn('history', function($row) {
                        
                $btn = '<button class="btn btn-sm btn-success" style="margin-top:5px;" id="btnHistory" onclick="view_history('."'".$row->hepl_recruitment_ref_number."'".');">View</button>';
                        
                return $btn;
            })
            ->addColumn('action', function($row) {
                
                if($row->request_status == 'Closed'){
                    $status_btn = '<button type="button" class="btn btn-primary btn-sm" disabled onclick="process_actionday(this,'."'".$row->hepl_recruitment_ref_number."'".','."'".$row->rfh_no."'".');"  title="Fresh Profiles submitted"><i class="bi bi-file-earmark-medical-fill"></i></button>';

                }
                else{
                    $status_btn = '<button type="button" class="btn btn-primary btn-sm" data-position_title="'.$row->position_title.'" onclick="process_actionday(this,'."'".$row->hepl_recruitment_ref_number."'".','."'".$row->rfh_no."'".');"  title="Fresh Profiles submitted"><i class="bi bi-file-earmark-medical-fill"></i></button>';

                }
                        
                $btn = '<button class="btn btn-sm btn-success" id="btnHistory" style="margin-top:2px;" data-position_title="'.$row->position_title.'" onclick="view_history(this,'."'".$row->hepl_recruitment_ref_number."'".','."'".$row->rfh_no."'".');" title="Edit"><i class="bi bi-pen-fill"></i></button>';
                                      
                return $status_btn." ".$btn;
            })
            ->addColumn('tat_process', function($row) {
                $from = strtotime($row->open_date);

                $today = time();
                $difference = $today - $from;
                $difference_days = floor($difference / 86400);  // (60 * 60 * 24)
                

                $ageing = $difference_days;
                $ageing_end = 15;

                // if($row->critical_position =='Yes'){
                //     $ageing_end = 15;

                    
                // }else if($row->critical_position =='Nill'){
                //     $ageing_end = 15;

                // }
                // else{
                //     $ageing_end = $row->tat_days;

                // }

                if($ageing >= $ageing_end){
                    return "show_tat_highlight";
                }
                else{
                    return "hide_tat_highlight";
                }

                
            })
            ->addColumn('interviewer', function($row){
                $get_interviewer = $row->interviewer;
                
                if($get_interviewer =='Self' || $get_interviewer =='SELF' || $get_interviewer =='self'){
                    $get_interviewer_self = $this->recrepo->get_interviewer_self( $row->rfh_no );

                    $get_interviewer = $get_interviewer_self[0]->name;
                }
                return $get_interviewer;

            })
            
            ->addColumn('cv_count', function($row) {

                $get_assigned_to = auth()->user();
                $assigned_to = $get_assigned_to->empID;

                $credentials = array(
                    'created_by'=>$assigned_to,
                    'hepl_recruitment_ref_number'=>$row->hepl_recruitment_ref_number
                );

                $cv_count = $this->recrepo->get_cv_count( $credentials );

                return $cv_count;
            })
            ->addColumn('current_status', function($row) {
                
                $input_details_cc = array(
                    'hepl_recruitment_ref_number'=>$row->hepl_recruitment_ref_number,
                    'assigned_to'=>$row->assigned_to,
                );

                $get_current_status = $this->recrepo->get_current_status_rr( $input_details_cc );

                $current_status ='';
                if(count($get_current_status) !=0){
                    foreach ($get_current_status as $key => $gcs_value) {
                        if($gcs_value->status !=''){
                            $current_status .= $gcs_value->status;
                        }
                    }
                }

                return $current_status;
            })
            ->rawColumns(['current_status','cv_count','tat_process','ageing','assigned_date','close_date','open_date','action','request_status','history','action_for_the_day_status','interviewer'])
            ->make(true);
    }

    public function or_ldj_onboard_status(Request $req){

         // get profile count based on rfh_no
         $credentials = array(
            'rfh_no'=>$req->input('rfh_no'),
            'hepl_recruitment_ref_number'=>$req->input('hepl_recruitment_ref_number'),
            'request_status'=>"Closed"
        );
        $no_of_closed_result = $this->recrepo->get_no_profile_position_recreq( $credentials );

        // get no of Candidate Onboarded 
        // $no_of_candidate_onboarded_result = $this->recrepo->get_no_candidate_onboarded( $credentials );
        if($no_of_closed_result >= 1){
            
            $response = 'position_filled';

            return response()->json( ['response' => $response] );

        }
        else{

            $input_details = array(
                'action_for_the_day_status'=>"Candidate Onboarded",
                'cdID'=>$req->input('cdID')
            );
    
            $process_default_status_result = $this->recrepo->process_default_status( $input_details );

            // update position status in orldj 
            $credentials = array(
                'cdID'=>$req->input('cdID'),
                'rfh_no'=>$req->input('rfh_no'),
                'profile_status'=>"Candidate Onboarded"
            );
            $update_position_status_orldj_result = $this->recrepo->update_position_status_orldj( $credentials );


            if($no_of_closed_result >= 1){
                // update position status as closed 
                $credentials = array(
                    'rfh_no'=>$req->input('rfh_no'),
                    'hepl_recruitment_ref_number'=>$req->input('hepl_recruitment_ref_number'),
                    'request_status'=>"Closed"
                );
                $update_position_status_result = $this->recrepo->update_position_status_closed( $credentials );
            }

             // get all data
            $get_assigned_to = auth()->user();
            $created_by = $get_assigned_to->empID;

            $cfdID = 'CFD'.( ( DB::table( 'candidate_followup_details' )->max( 'id' ) )+1 );

            $candidate_followup_details = array(
                'cfdID'=>$cfdID,
                'cdID'=>$req->input('cdID'),
                'rfh_no'=>$req->input('rfh_no'),
                'follow_up_status'=>"Candidate Onboarded",
                'created_on'=>date('Y-m-d'),
                'created_by'=>$created_by
            );
            Candidate_followup_details::create($candidate_followup_details);

            $response = 'Updated';
            return response()->json( ['response' => $response] );
        }
        
    }

    public function candidate_follow_up_history(Request $req){
        
        $input_details = array(
            'cdID'=>$req->input('cdID'),
        );

        $cfu_history_result = $this->recrepo->candidate_follow_up_history( $input_details );

         // get position details
         $input_details_pd = array(
            'hepl_recruitment_ref_number'=>$req->input('hepl_recruitment_ref_number'),
            'assigned_to'=>$req->input('created_by'),
        );

        $cfu_history_pd_result = $this->recrepo->candidate_follow_up_history_pd( $input_details_pd );

        return response()->json( [
            'chr' => $cfu_history_result,
            'ch_pdr' => $cfu_history_pd_result
            ] );
    }

    public function get_offer_released_report(Request $req){
        $input_details = array(
            'cdID'=>$req->input('cdID'),
        );

        $ofr_history_result = $this->recrepo->get_offer_released_report( $input_details );
        $ofrld_history_result = $this->recrepo->get_offer_released_ld_report( $input_details );

        return response()->json( [
            'ord' => $ofr_history_result,
            'orld' => $ofrld_history_result
            ] );


    }

    public function get_candidate_onborded_history(Request $req){
        // get all data
        $get_assigned_to = auth()->user();
        $created_by = $get_assigned_to->empID;


        $input_details = array(
            'status'=>"Candidate Onboarded",
            'created_by'=>$created_by,
        );

        $get_candidate_onborded_history_result = $this->recrepo->get_candidate_onborded_history( $input_details );

            
        return Datatables::of($get_candidate_onborded_history_result)
            ->addIndexColumn()
            ->addColumn('candidate_cv', function($row) {
                        
                $btn = '<a href="../cv_upload/'.$row->candidate_cv.'" class="badge bg-info" target="_blank"><i class="bi bi-eye"></i></a>';
                return $btn;
            })
            
            ->addColumn('history', function($row) {
                
                $btn = '<span class="badge bg-secondary" title="Followup History" onclick="candidate_follow_up('."'".$row->created_by."'".','."'".$row->hepl_recruitment_ref_number."'".','."'".$row->cdID."'".','."'".$row->candidate_name."'".');"><i class="bi bi-stack"></i></span>';
                        return $btn;

                
            })
            ->addColumn('status', function($row) {
                
                
                $btn = '<button class="btn btn-sm btn-success" id="btnHistory">'.$row->status.'</button>';
                                      
                return $btn;
            })
            ->rawColumns(['candidate_cv','history','status'])
            ->make(true);
    }

    public function list_candidate_profile(Request $request){
        
        if ($request->ajax()) {
           
            // get all data
            $session_user_details = auth()->user();
            $created_by = $session_user_details->empID;
            $get_role_type = $session_user_details->role_type;

            $af_from_date = (!empty($_POST["af_from_date"])) ? ($_POST["af_from_date"]) : ('');
            $af_to_date = (!empty($_POST["af_to_date"])) ? ($_POST["af_to_date"]) : ('');
            $af_position_title = (!empty($_POST["af_position_title"])) ? ($_POST["af_position_title"]) : ('');
            $af_sub_position_title = (!empty($_POST["af_sub_position_title"])) ? ($_POST["af_sub_position_title"]) : ('');
            $af_position_status = (!empty($_POST["af_position_status"])) ? ($_POST["af_position_status"]) : ('');
            
            if( $af_from_date || $af_to_date  || $af_position_title || $af_sub_position_title || $af_position_status) 
            {
                // get all data
                $advanced_filter = array(
                    'af_from_date'=>$af_from_date,
                    'af_to_date'=>$af_to_date,
                    'af_position_title'=>$af_position_title,
                    'af_sub_position_title'=>$af_sub_position_title,
                    'af_position_status'=>$af_position_status,
                    'created_by'=>$created_by,
                    'status'=>"Candidate Onboarded",

                );
                $get_candidate_profile_result = $this->recrepo->get_candidate_profile_af( $advanced_filter );

            }
            else{
                
                $input_details = array(
                    'created_by'=>$created_by,
                    'status'=>"Candidate Onboarded",
                );
    
                $get_candidate_profile_result = $this->recrepo->get_candidate_profile( $input_details );
    
            }
            
            return Datatables::of($get_candidate_profile_result)
                ->addIndexColumn()
                ->addColumn('candidate_cv', function($row) {
                            
                    $btn = '<a href="../cv_upload/'.$row->candidate_cv.'" class="badge bg-info" target="_blank"><i class="bi bi-eye"></i></a>';
                    return $btn;
                })
                
                ->addColumn('history', function($row) {
                    
                    $btn = '<span class="badge bg-secondary" title="Followup History" onclick="candidate_follow_up('."'".$row->created_by."'".','."'".$row->hepl_recruitment_ref_number."'".','."'".$row->cdID."'".','."'".$row->candidate_name."'".');"><i class="bi bi-stack"></i></span>';
                            return $btn;

                    
                })
                ->addColumn('status', function($row) {
                    
                    
                    $btn = '<button class="btn btn-sm btn-dark">'.$row->status.'</button>';
                                        
                    return $btn;
                })
                ->addColumn('status_cont', function($row) {
                    
                    return $row->status;
                })
                ->addColumn('created_on', function($row) {
                        
                    $originalDate = $row->created_on;
                    $created_on = date("d-m-Y", strtotime($originalDate));

                    return $created_on;
                })
                ->addColumn('action',function($row){
                    $edit_btn = '<button type="button" class="btn btn-info btn-sm" onclick="edit_candidate_pop('."'".$row->cdID."'".')"><i class="fa fa-edit"></i></button>';
                    // $delete_btn = '<button type="button" class="btn btn-danger btn-sm" onclick="delete_candidate_pop('."'".$row->cdID."'".')"><i class="fa fa-trash"></i></button>';
                    
                    return $action_btn = $edit_btn;
                })
                ->rawColumns(['candidate_cv','history','status','status_cont','action'])
                ->make(true);

        }

        return view('list_candidate_profile');
        

    }

    public function get_recruitment_inactive(Request $req){
        // get all data
        $get_assigned_to = auth()->user();
        $assigned_to = $get_assigned_to->empID;


        $input_details = array(
            'assigned_to'=>$assigned_to,
            'request_status'=>"On Hold"
        );

        $get_reqcruitment_request_result = $this->recrepo->get_assigned_reqcruitment_inactive( $input_details );

            
        return Datatables::of($get_reqcruitment_request_result)
            ->addIndexColumn()
            ->addColumn('ageing', function($row) {
                $from = strtotime($row->open_date);

                $today = time();
                $difference = $today - $from;
                $difference_days = floor($difference / 86400);  // (60 * 60 * 24)

                // $originalDate = $row->open_date;
                // $currentDate = date("Y-m-d");

                // $datetime1 = new DateTime($originalDate);

                // $datetime2 = new DateTime($currentDate);

                // $difference = $datetime1->diff($datetime2);

                $ageing = $difference_days;
                // $ageing = $difference->y.' years, ' .$difference->m.' months, '.$difference->d.' days';

                return $ageing;
            })
            ->addColumn('open_date', function($row) {
                        
                $originalDate = $row->open_date;
                $newDate = date("d-m-Y", strtotime($originalDate));

                return $newDate;
            })
            ->addColumn('assigned_date', function($row) {
                        
                $originalDate = $row->updated_at;
                $newDate = date("d-m-Y", strtotime($originalDate));

                return $newDate;
            })
            ->addColumn('request_status', function($row) {
                  
                
                if($row->request_status =='Open'){
                    $btn1 = '<span class="badge bg-warning" title="Open"><i class="fa fa-book-open"></i></span>';
                }
                else if($row->request_status =='Closed'){
                    $btn1 = '<span class="badge bg-success" title="Closed"><i class="fa fa-book"></i></span>';
                }
                else if($row->request_status =='On Hold'){
                    $btn1 = '<span class="badge bg-onhold" title="On Hold"><i class="bi bi-pause-fill"></i></span>';
                }
                else if($row->request_status =='Re Open'){
                    $btn1 = '<span class="badge bg-dark" title="Re Open"><i class="bi bi-exclude"></i></span>';
                }

                return $btn1;
            })
            ->addColumn('history', function($row) {
                        
                $btn = '<button class="btn btn-sm btn-success" style="margin-top:5px;" id="btnHistory" onclick="view_history('."'".$row->hepl_recruitment_ref_number."'".');">View</button>';
                        
                return $btn;
            })
            ->addColumn('interviewer', function($row){
                $get_interviewer = $row->interviewer;
                
                if($get_interviewer =='Self' || $get_interviewer =='SELF' || $get_interviewer =='self'){
                    $get_interviewer_self = $this->recrepo->get_interviewer_self( $row->rfh_no );

                    $get_interviewer = $get_interviewer_self[0]->name;
                }
                return $get_interviewer;

            })
            ->rawColumns(['ageing','assigned_date','open_date','request_status','history','action_for_the_day_status','interviewer'])
            ->make(true);
    }

    public function ticket_report_recruiter(Request $request){
        if ($request->ajax()) {
            
            $af_from_date = (!empty($_POST["af_from_date"])) ? ($_POST["af_from_date"]) : ('');
            $af_to_date = (!empty($_POST["af_to_date"])) ? ($_POST["af_to_date"]) : ('');
            $af_position_title = (!empty($_POST["af_position_title"])) ? ($_POST["af_position_title"]) : ('');
            $af_sub_position_title = (!empty($_POST["af_sub_position_title"])) ? ($_POST["af_sub_position_title"]) : ('');
            $af_critical_position = (!empty($_POST["af_critical_position"])) ? ($_POST["af_critical_position"]) : ('');
            $af_position_status = (!empty($_POST["af_position_status"])) ? ($_POST["af_position_status"]) : ('');
            $af_assigned_status = (!empty($_POST["af_assigned_status"])) ? ($_POST["af_assigned_status"]) : ('');
            $af_salary_range = (!empty($_POST["af_salary_range"])) ? ($_POST["af_salary_range"]) : ('');
            $af_band = (!empty($_POST["af_band"])) ? ($_POST["af_band"]) : ('');
            $af_location = (!empty($_POST["af_location"])) ? ($_POST["af_location"]) : ('');
            $af_business = (!empty($_POST["af_business"])) ? ($_POST["af_business"]) : ('');
            $af_billing_status = (!empty($_POST["af_billing_status"])) ? ($_POST["af_billing_status"]) : ('');
            $af_function = (!empty($_POST["af_function"])) ? ($_POST["af_function"]) : ('');
            $af_division = (!empty($_POST["af_division"])) ? ($_POST["af_division"]) : ('');
            $af_raisedby = (!empty($_POST["af_raisedby"])) ? ($_POST["af_raisedby"]) : ('');
            $af_approvedby = (!empty($_POST["af_approvedby"])) ? ($_POST["af_approvedby"]) : ('');

            
            $session_user_details = auth()->user();
            $assigned_to = $session_user_details->empID;

            if( $af_from_date || $af_to_date  || $af_position_title || $af_sub_position_title || 
                $af_critical_position || $af_position_status || $af_assigned_status || 
                $af_salary_range || $af_band || $af_location || $af_business || 
                $af_billing_status || $af_billing_status || $af_division || $af_raisedby || $af_approvedby ) 
            {
                // get all data
                $advanced_filter = array(
                    'af_from_date'=>$af_from_date,
                    'af_to_date'=>$af_to_date,
                    'af_position_title'=>$af_position_title,
                    'af_sub_position_title'=>$af_sub_position_title,
                    'af_critical_position'=>$af_critical_position,
                    'af_position_status'=>$af_position_status,
                    'af_assigned_status'=>$af_assigned_status,
                    'af_salary_range'=>$af_salary_range,
                    'af_band'=>$af_band,
                    'af_location'=>$af_location,
                    'af_business'=>$af_business,
                    'af_billing_status'=>$af_billing_status,
                    'af_function'=>$af_function,
                    'af_division'=>$af_division,
                    'assigned_to'=>$assigned_to,
                    'af_raisedby'=>$af_raisedby,
                    'af_approvedby'=>$af_approvedby,
                );

                $get_ticket_report_recruiter_result = $this->recrepo->get_ticket_report_recruiter_afilter( $advanced_filter );

            }
            
            else{
                
                // get all data
                $input_details = array(
                    'assigned_to'=>$assigned_to,
                );

                $get_ticket_report_recruiter_result = $this->recrepo->get_ticket_report_recruiter( $input_details );

            }
            
            return Datatables::of($get_ticket_report_recruiter_result)
                    ->addIndexColumn()
                    ->addColumn('ageing', function($row) {
                        $from = strtotime($row->open_date);

                        $today = time();
                        $difference = $today - $from;
                        $difference_days = floor($difference / 86400);  // (60 * 60 * 24)
                        
                        // $originalDate = $row->open_date;
                        // $currentDate = date("Y-m-d");

                        // $datetime1 = new DateTime($originalDate);

                        // $datetime2 = new DateTime($currentDate);

                        // $difference = $datetime1->diff($datetime2);

                        $ageing = $difference_days;
                        // $ageing = $difference->y.' years, ' .$difference->m.' months, '.$difference->d.' days';

                        if($row->open_date >= '2021-12-08'){
                            $ageing_btn = '<a href="#" style="color:black;" onclick="show_stagesof_recruitment_pop('."'".$row->hepl_recruitment_ref_number."'".','."'".$row->rfh_no."'".','."'".$row->assigned_to."'".')">'.$ageing.'</a>';

                        }else{
                            $ageing_btn = $ageing;
                        }

                        return $ageing_btn;
                    })
                    
                    ->addColumn('close_date', function($row) {
                        if($row->request_status =='Closed'){

                            $originalDate_cd = $row->close_date;
                            $closed_date = date("d-m-Y", strtotime($originalDate_cd));

                            return $closed_date;
                        }else{
                            $closed_date = '-';
                            return $closed_date;
                        }
                    })

                    ->addColumn('open_date', function($row) {
                        
                        $originalDate = $row->open_date;
                        $newDate = date("d-m-Y", strtotime($originalDate));

                        return $newDate;
                    })
                    ->addColumn('assigned_status', function($row) {
                        
                        if($row->assigned_status =='Assigned'){
                            $btn = '<span class="badge bg-success" title="'.$row->assigned_status.'"><i class="bi bi-shield-check"></i></span>';
                        }else{
                            $btn = '<span class="badge bg-warning" title="'.$row->assigned_status.'"><i class="bi bi-shield-slash"></i></span>';
                        }
                        
                        if($row->request_status =='Open'){
                            $btn1 = '<span class="badge bg-warning" title="Open"><i class="fa fa-book-open"></i></span>';
                        }
                        else if($row->request_status =='Closed'){
                            $btn1 = '<span class="badge bg-success" title="Closed"><i class="fa fa-book"></i></span>';
                        }
                        else if($row->request_status =='On Hold'){
                            $btn1 = '<span class="badge bg-onhold" title="On Hold"><i class="bi bi-pause-fill"></i></span>';
                        }
                        else if($row->request_status =='Re Open'){
                            $btn1 = '<span class="badge bg-dark" title="Re Open"><i class="bi bi-exclude"></i></span>';
                        }

                        return $btn." ".$btn1;
                    })
                    ->addColumn('action', function($row){
                        $btn = '<a href="ticket_cd_recruiter?hr_refno='.$row->hepl_recruitment_ref_number.'" target="_blank"><span class="badge bg-primary" id="btnAssign" title="Candidate Details"><i class="bi bi-people-fill"></i></span></a>';
                        
                        return $btn;
                    })
                    ->addColumn('tat_process', function($row) {
                        
                        $from = strtotime($row->open_date);

                        $today = time();
                        $difference = $today - $from;
                        $difference_days = floor($difference / 86400);  // (60 * 60 * 24)

                        // $originalDate = $row->open_date;
                        // $currentDate = date("Y-m-d");

                        // $datetime1 = new DateTime($originalDate);

                        // $datetime2 = new DateTime($currentDate);

                        // $difference = $datetime1->diff($datetime2);

                        $ageing = $difference_days;

                        $ageing_end = 15;

                        // if($row->critical_position =='Yes'){
                        //     $ageing_end = 15;

                            
                        // }else if($row->critical_position =='Nill'){
                        //     $ageing_end = 15;

                        // }
                        // else{
                        //     $ageing_end = $row->tat_days;

                        // }

                        if($ageing >= $ageing_end){
                            return "show_tat_highlight";
                        }
                        else{
                            return "hide_tat_highlight";
                        }

                        
                    })

                    ->addColumn('interviewer', function($row){
                        $get_interviewer = $row->interviewer;
                        
                        if($get_interviewer =='Self' || $get_interviewer =='SELF' || $get_interviewer =='self'){
                            $get_interviewer_self = $this->recrepo->get_interviewer_self( $row->rfh_no );
        
                            $get_interviewer = $get_interviewer_self[0]->name;
                        }
                        return $get_interviewer;
        
                    })
                    ->addColumn('cv_count', function($row) {

                        $get_assigned_to = auth()->user();
                        $assigned_to = $get_assigned_to->empID;
        
                        $credentials = array(
                            'created_by'=>$assigned_to,
                            'hepl_recruitment_ref_number'=>$row->hepl_recruitment_ref_number
                        );
        
                        $cv_count = $this->recrepo->get_cv_count( $credentials );
        
                        return $cv_count;
                    })
                    ->addColumn('profile_ageing', function($row) {
                        if($row->request_status == 'Closed' || $row->request_status == 'On Hold'){
                            $ageing = 0;
                            return $ageing;
                        }else{

                            // get all data
                            $recruiter_last_modified_date = array(
                                'rfh_no'=>$row->rfh_no,
                                'hepl_recruitment_ref_number'=>$row->hepl_recruitment_ref_number,
                                'created_by'=>$row->assigned_to,
                            );
                            $recruiter_ageing_result = $this->corepo->recruiter_last_modified_date( $recruiter_last_modified_date );
                            if(isset($recruiter_ageing_result[0]->updated_at)){
                                // echo $row->hepl_recruitment_ref_number;
                                $orl_mdate = $recruiter_ageing_result[0]->updated_at;
                                // echo "<pre>";
                                $newrl_mdate = date("d-m-Y", strtotime($orl_mdate));
                                $thirdrl_mdate= date('d-m-Y', strtotime($newrl_mdate. ' + 3 days'));
                                $forthrl_mdate= date('d-m-Y', strtotime($newrl_mdate. ' + 4 days'));
                                $currentDate=date('d-m-Y');

                                $recruiter_ageing_filter = array(
                                    'rfh_no'=>$row->rfh_no,
                                    'hepl_recruitment_ref_number'=>$row->hepl_recruitment_ref_number,
                                    'created_by'=>$row->assigned_to,
                                    'check_date_1'=>$orl_mdate,
                                    'check_date_2'=>$thirdrl_mdate,
                                    
                                );

                                $recruiter_ageing_result = $this->corepo->recruiter_ageing_details( $recruiter_ageing_filter );
                                // echo count($recruiter_ageing_result);
                                if(count($recruiter_ageing_result) ==0){
                                    if($currentDate ==$forthrl_mdate){

                                        // check for fourth day
                                        $recruiter_ageing_filter = array(
                                            'rfh_no'=>$row->rfh_no,
                                            'hepl_recruitment_ref_number'=>$row->hepl_recruitment_ref_number,
                                            'created_by'=>$row->assigned_to,
                                            'check_date_1'=>$orl_mdate,
                                            'check_date_2'=>$forthrl_mdate,
                                        );
                                        $recruiter_ageing_result_fd = $this->corepo->recruiter_ageing_details( $recruiter_ageing_filter );
                                        
                                        if(count($recruiter_ageing_result_fd) ==0){

                                            $new_updated_at = date("Y-m-d", strtotime($orl_mdate));

                                            $from = strtotime($new_updated_at);

                                            $today = time();
                                            $difference = $today - $from;
                                            $difference_days = floor($difference / 86400);  // (60 * 60 * 24)
                    
                                            $ageing = $difference_days;
                    
                                            return $ageing;
                                        }
                                        else{
                                            $ageing = "0";
                
                                            return $ageing;
                                        }
                                    }
                                    else{

                                        $new_updated_at = date("Y-m-d", strtotime($orl_mdate));

                                        $from = strtotime($new_updated_at);

                                        $today = time();
                                        $difference = $today - $from;
                                        $difference_days = floor($difference / 86400);  // (60 * 60 * 24)
                    
                                        $ageing = $difference_days;
                                        return $ageing;
                                    }
                                }
                                else{

                                    $ageing = 0;

                                    return $ageing;

                                }


                            }
                            else{

                                if($row->assigned_date !=''){
                                    $from = strtotime($row->assigned_date);
        
                                    $today = time();
                                    $difference = $today - $from;
                                    $difference_days = floor($difference / 86400);  // (60 * 60 * 24)
            
                                    $ageing = $difference_days;
            
                                    return $ageing;
                                }
                                else{
                                    $from = strtotime($row->open_date);
        
                                    $today = time();
                                    $difference = $today - $from;
                                    $difference_days = floor($difference / 86400);  // (60 * 60 * 24)
            
                                    $ageing = $difference_days;
            
                                    return $ageing;
                                }
                            }
                        }

                    })
                    ->addColumn('profile_ageing_status', function($row) {

                        if($row->request_status == 'Closed' || $row->request_status == 'On Hold'){
                            return "";
                        }else{
                            // get all data
                            $recruiter_last_modified_date = array(
                                'rfh_no'=>$row->rfh_no,
                                'hepl_recruitment_ref_number'=>$row->hepl_recruitment_ref_number,
                                'created_by'=>$row->assigned_to,
                            );
                            $recruiter_ageing_result = $this->corepo->recruiter_last_modified_date( $recruiter_last_modified_date );
                            if(isset($recruiter_ageing_result[0]->updated_at)){
                            
                                $orl_mdate = $recruiter_ageing_result[0]->updated_at;
                                
                                $newrl_mdate = date("d-m-Y", strtotime($orl_mdate));
                                
                                $thirdrl_mdate= date('d-m-Y', strtotime($newrl_mdate. ' + 3 days'));
                                $forthrl_mdate= date('d-m-Y', strtotime($newrl_mdate. ' + 4 days'));
                                $currentDate=date('d-m-Y');
                                $recruiter_ageing_filter = array(
                                    'rfh_no'=>$row->rfh_no,
                                    'hepl_recruitment_ref_number'=>$row->hepl_recruitment_ref_number,
                                    'created_by'=>$row->assigned_to,
                                    'check_date_1'=>$orl_mdate,
                                    'check_date_2'=>$thirdrl_mdate,
                                    
                                );

                                $recruiter_ageing_result = $this->corepo->recruiter_ageing_details( $recruiter_ageing_filter );

                                if(count($recruiter_ageing_result) ==0){

                                    if($currentDate >=$forthrl_mdate){
                                        // check for fourth day
                                        $recruiter_ageing_filter = array(
                                            'rfh_no'=>$row->rfh_no,
                                            'hepl_recruitment_ref_number'=>$row->hepl_recruitment_ref_number,
                                            'check_date_1'=>$orl_mdate,
                                            'created_by'=>$row->assigned_to,
                                            'check_date_2'=>$forthrl_mdate,
                                        );
                                        $recruiter_ageing_result_fd = $this->corepo->recruiter_ageing_details( $recruiter_ageing_filter );
                                        
                                        if(count($recruiter_ageing_result_fd) ==0){

                                            $new_updated_at = date("Y-m-d", strtotime($orl_mdate));

                                            $from = strtotime($new_updated_at);

                                            $today = time();
                                            $difference = $today - $from;
                                            $difference_days = floor($difference / 86400);  // (60 * 60 * 24)
                    
                                            $ageing = $difference_days;
                                        }
                                        else{
                                            
                                            $ageing= 0;
                                        }
                                    }
                                    else{
                                        $new_updated_at = date("Y-m-d", strtotime($orl_mdate));

                                        $from = strtotime($new_updated_at);

                                        $today = time();
                                        $difference = $today - $from;
                                        $difference_days = floor($difference / 86400);  // (60 * 60 * 24)
                    
                                        $ageing = $difference_days;
                                    }
                                }
                                else{
                                    $ageing = 0;

                                }


                            }else{

                                if($row->assigned_date !=''){
                                    $from = strtotime($row->assigned_date);

                                    $today = time();
                                    $difference = $today - $from;
                                    $difference_days = floor($difference / 86400);  // (60 * 60 * 24)
            
                                    $ageing = $difference_days;
                                }
                                else{
                                    $from = strtotime($row->open_date);

                                    $today = time();
                                    $difference = $today - $from;
                                    $difference_days = floor($difference / 86400);  // (60 * 60 * 24)
            
                                    $ageing = $difference_days;
                                }
        
                            }
                            $currentDate = date("Y-m-d");

                            if($ageing ==3){
                                return "three_show_recruiter_ageing_highlight";

                            }
                            elseif($ageing ==4){
                                return "four_show_recruiter_ageing_highlight";

                            }elseif ($ageing >4) {
                                return "five_show_recruiter_ageing_highlight";
                            }
                            else{
                                return "";

                            }
                        }
                        
                    })
                    ->addColumn('current_status', function($row) {
                
                        $input_details_cc = array(
                            'hepl_recruitment_ref_number'=>$row->hepl_recruitment_ref_number,
                            'assigned_to'=>$row->assigned_to,
                        );
        
                        $get_current_status = $this->recrepo->get_current_status_rr( $input_details_cc );
        
                        $current_status ='';
                        if(count($get_current_status) !=0){
                            foreach ($get_current_status as $key => $gcs_value) {
                                if($gcs_value->status !=''){
                                    $current_status .= $gcs_value->status;
                                }
                            }
                        }
        
                        return $current_status;
                    })
                    ->rawColumns(['current_status','profile_ageing','profile_ageing_status','cv_count','ageing','open_date','close_date','action','assigned_status','tat_process','interviewer'])
                    ->make(true);
        }
        return view('ticket_report_recruiter');
    }

    public function ticket_candidate_details(Request $request){

        if ($request->ajax()) {
           
            // get all data
            $session_user_details = auth()->user();
            $created_by = $session_user_details->empID;

            $af_from_date = (!empty($_POST["af_from_date"])) ? ($_POST["af_from_date"]) : ('');
            $af_to_date = (!empty($_POST["af_to_date"])) ? ($_POST["af_to_date"]) : ('');
            $af_position_status = (!empty($_POST["af_position_status"])) ? ($_POST["af_position_status"]) : ('');
            
            if( $af_from_date || $af_to_date || $af_position_status ){

                $input_details = array(
                    'af_from_date'=>$af_from_date,
                    'af_to_date'=>$af_to_date,
                    'af_position_status'=>$af_position_status,
                    'created_by'=>$created_by,
                    'hepl_recruitment_ref_number'=>$request->input('hr_refno'),
                );
    
                $get_ticket_candidate_details_result = $this->recrepo->ticket_candidate_details_af( $input_details );
    
            }
            else{
                $input_details = array(
                    'created_by'=>$created_by,
                    'hepl_recruitment_ref_number'=>$request->input('hr_refno'),
                );
    
                $get_ticket_candidate_details_result = $this->recrepo->ticket_candidate_details_recuiter( $input_details );
    
            }
            
            return Datatables::of($get_ticket_candidate_details_result)
                ->addIndexColumn()
                ->addColumn('candidate_cv', function($row) {
                            
                    $btn = '<a href="../cv_upload/'.$row->candidate_cv.'" class="badge bg-info" target="_blank"><i class="bi bi-eye"></i></a>';
                    return $btn;
                })
                
                ->addColumn('history', function($row) {
                    
                    $btn = '<span class="badge bg-secondary" title="Followup History" onclick="candidate_follow_up('."'".$row->created_by."'".','."'".$row->hepl_recruitment_ref_number."'".','."'".$row->cdID."'".','."'".$row->candidate_name."'".');"><i class="bi bi-stack"></i></span>';
                            return $btn;

                    
                })
                ->addColumn('status', function($row) {
                    
                    
                    $btn = '<button class="btn btn-sm btn-dark">'.$row->status.'</button>';
                                        
                    return $btn;
                })
                ->addColumn('status_cont', function($row) {
                    
                    return $row->status;
                })
                ->addColumn('created_on', function($row) {
                        
                    $originalDate = $row->created_on;
                    $created_on = date("d-m-Y", strtotime($originalDate));

                    return $created_on;
                })
                ->rawColumns(['candidate_cv','history','status','status_cont','created_on'])
                ->make(true);

        }

        return view('ticket_cd_recruiter');
    }

    
    public function view_recruit_request_new(){
        return view('view_recruit_request_new');

    }

    public function get_recruitment_view_details_new(Request $req){

        $input_details = array(
            'rfh_no'=>$req->input('rfh_no'),
        );
        
        $get_tblrfh_result = $this->recrepo->get_tblrfh_details( $input_details );

        return response()->json( [
            'tbl_rfh' => $get_tblrfh_result
        ] );

    }

    public function closedate_update(Request $req){

        $user_details = auth()->user();
        $assigned_to = $user_details->empID;
        
        $input_details = array(
            'hrr_cd'=>$req->input('hrr_cd'),
            'close_date'=>$req->input('close_date'),
            'id'=>$req->input('cd_rowid'),
            'assigned_to'=>$assigned_to,
        );
        
        $modify_close_date_result = $this->recrepo->modify_close_date( $input_details );

        $response = 'Updated';
        return response()->json( ['response' => $response] );

    }

    public function process_candidate_delete_rl(Request $req){
        $input_details = array(
            'cdID'=>$req->input('candidate_id'),
        );

        $process_candidate_delete_result = $this->recrepo->process_candidate_delete( $input_details );

        $response = 'success';
        return response()->json( ['response' => $response] );
    }

    public function get_candidate_details_ed(Request $req){

        $input_details = array(
            'cdID'=>$req->input('candidate_id'),
        );

        $get_candidate_details_result = $this->recrepo->get_candidate_details_ed( $input_details );
        
        return $get_candidate_details_result;

    }

    public function process_candidate_edit(Request $req){

        $input_details = array(
            'cdID'=>$req->input('cdID'),
            'candidate_name'=>$req->input('candidate_name'),
            'candidate_gender'=>$req->input('candidate_gender'),
            'candidate_email'=>$req->input('candidate_email'),
            'candidate_source'=>$req->input('candidate_source'),
        );

        $process_candidate_edit_result = $this->recrepo->process_candidate_edit( $input_details );

        $response = 'success';
        return response()->json( ['response' => $response] );
    }

    public function dateofjoining_update(Request $req){
        $input_details = array(
            'cdID'=>$req->input('cdID'),
            'date_of_joining'=>$req->input('get_new_doj'),
            
        );

        $process_doj_edit_result = $this->recrepo->dateofjoining_update( $input_details );

        $response = 'success';
        return response()->json( ['response' => $response] );
    }

    public function document_collection(Request $request){

        if ($request->ajax()) {
           
            // get all data
            $session_user_details = auth()->user();
            $created_by = $session_user_details->empID;
                
                $input_details = array(
                    'created_by'=>$created_by,
                    'doc_status'=>"1",
                );
    
                $get_candidate_profile_result = $this->recrepo->get_candidate_profile_dc( $input_details );
    
            
            return Datatables::of($get_candidate_profile_result)
                ->addIndexColumn()
                
                ->addColumn('history', function($row) {
                    
                    $btn = '<span class="badge bg-secondary" title="Followup History" onclick="candidate_follow_up('."'".$row->created_by."'".','."'".$row->hepl_recruitment_ref_number."'".','."'".$row->cdID."'".','."'".$row->candidate_name."'".');"><i class="bi bi-stack"></i></span>';
                            return $btn;

                    
                })
                ->addColumn('c_doc_status', function($row) {
                    
                    if($row->c_doc_status =='Verified'){
                        $btn = '<span class="badge bg-success">'.$row->c_doc_status.'</span>';

                    }elseif($row->c_doc_status =='Not Verified'){
                        $btn = '<span class="badge bg-danger">'.$row->c_doc_status.'</span>';

                    }else{
                        $btn = '<span class="badge bg-warning">Pending</span>';

                    }
                                        
                    return $btn;
                })
                ->addColumn('payroll_status', function($row) {

                    if($row->payroll_status == 0){
                        $btn = '<span class="badge bg-danger">Not sent</span>';
                    }elseif($row->payroll_status == 1){
                        $btn = '<span class="badge bg-warning">Pending</span>';
                    }
                    elseif($row->payroll_status == 2){
                        $btn = '<span class="badge bg-info">Inprogress</span>';
                    }
                    elseif($row->payroll_status == 3){
                        $btn = '<span class="badge bg-success">Verified</span>';
                    }
                   else{
                       $btn='';
                   }                          
                    return $btn;
                })
                ->addColumn('finance_status', function($row) {

                    if($row->po_finance_status == 0){
                        $btn = '<span class="badge bg-danger">Not sent</span>';
                    }elseif($row->po_finance_status == 1){
                        $btn = '<span class="badge bg-warning">Pending</span>';
                    }
                    elseif($row->po_finance_status == 2){
                        $btn = '<span class="badge bg-success">Approved</span>';
                    }
                    elseif($row->po_finance_status == 3){
                        $btn = '<span class="badge bg-danger">Rejected</span>';
                    }
                   else{
                       $btn='';
                   }                          
                    return $btn;
                })
              
                ->addColumn('leader_status', function($row) {

                    if($row->leader_status == 0){
                        $btn = '<span class="badge bg-purple">Not sent</span>';
                    }elseif($row->leader_status == 1){
                        $btn = '<span class="badge bg-vr">Inprogress</span>';
                    }
                    elseif($row->leader_status == 2){
                        $btn = '<span class="badge bg-success">Approved</span>';
                    }
                    elseif($row->leader_status == 3){
                        $btn = '';
                            $btn .= '<div class="dropdown dropdown-color-icon">';
                                $btn .= '<button class="btn btn-danger btn-sm btn-smn dropdown-toggle" type="button" id="dropdownMenuButtonEmoji" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 0.01rem 0.4rem !important;">';
                                    $btn .= '<span class="me-50"></span>Rejected';
                                $btn .= '</button>';
                                
                                $btn .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuButtonEmoji" style="box-shadow: 0 0 30px rgb(0 0 0 / 40%) !important;">';
                                    
                                    if($row->ld_reject_remark !=''){
                                        $btn .= '<a class="dropdown-item" href="#" onclick="show_ld_remark('."'".$row->cdID."'".');" style="border:unset !important;"><i class="bi bi-file-earmark-medical-fill"></i> Remark</a>';
                                        $btn .='<input type="hidden" name="ld_reject_remark_'.$row->cdID.'" id="ld_reject_remark_'.$row->cdID.'" value="'.$row->ld_reject_remark.'">';
                                    }
                                    
                                $btn .= '</div>';
                            $btn .= '</div>';
                    }
                    else{
                        $btn = '';
                    }                   
                    return $btn;
                })
                ->addColumn('remark', function($row) {
                    
                
                    $btn ='';
                    if($row->payroll_status == 4){
                    $btn .= '<button onclick ="get_payroll_remark('."'".$row->payroll_remark."'".');"class="btn btn-primary btn-sm btn-smn" type="button"><i class="bi bi-eye"></i></button>';
                    }
                    else{
                       $btn .= '<button onclick ="get_payroll_remark('."'".$row->payroll_remark."'".');"class="btn btn-primary btn-sm btn-smn" type="button" disabled><i class="bi bi-eye"></i></button>';
 
                    }
                    return $btn;
                                        
                    //return $offer_date;
                })
                ->addColumn('action',function($row){
                    
                    if($row->c_doc_upload_status == 0){

                        $btn = '<span class="badge bg-primary disabled" id="btnAssign" title="Candidate Profile"><i class="bi bi-person-lines-fill"></i></span>';
                    }else{
                        $btn = '<a href="cd_preview?cdID='.$row->cdID.'" target="_blank"><span class="badge bg-primary" id="btnAssign" title="Candidate Profile"><i class="bi bi-person-lines-fill"></i></span></a>';

                    }
                    if($row->c_doc_status =='Verified' && $row->payroll_status == 0 || $row->payroll_status == 4){
                        $or_btn = '<span class="badge bg-info" title="Offer Release Action" type="button" onclick="get_offer_release_pop('."'".$row->cdID."'".','."'".$row->rfh_no."'".','."'".$row->hepl_recruitment_ref_number."'".')"><i class="fa fa-file"></i></span>';
                    }
                    else{
                        $or_btn ='';
                    }
                    
                    return $btn." ".$or_btn;
                })
                ->addColumn('ageing',function($row){
                    $credentials = array(

                        'cdID' => $row->cdID,

                        'rfh_no' => $row->rfh_no,

                    );

                    $check_po_details_result = $this->payrepo->check_offer_oat( $credentials );

                    if($check_po_details_result != 0){
                    $credentials = array(
                        'cdID' => $row->cdID,
                        'rfh_no' => $row->rfh_no,
                    );
            
                    $offer_date = $this->payrepo->get_offer_oat_offrat_date( $credentials );
                    $recruiter_last_modified_date = array(
                        'rfh_no'=>$row->rfh_no,
                        'hepl_recruitment_ref_number'=>$row->hepl_recruitment_ref_number,
                        'created_by'=>$row->created_by,
                    );
                    $recruiter_ageing_result = $this->corepo->recruiter_last_modified_date( $recruiter_last_modified_date );
                     $offer_date = $offer_date[0]->created_at;
                
                     $from = strtotime($offer_date);
                        $update_date=$row->client_po_update_date;
                        if($update_date == ""){
                            $today = time();
                        }
                        else{
                            $today = $update_date;   
                        }
                   
                    $difference = $today - $from;
                    $difference_days = floor($difference / 86400);  // (60 * 60 * 24)

                    $ageing = $difference_days;
                   // $ageing = $difference->y.' years, ' .$difference->m.' months, '.$difference->d.' days';

                   // return $offer_date[0]->created_at;
                   // return $recruiter_ageing_result;
                   $btn ='';
                   $btn .= '<button onclick ="get_oat_age_dt('."'".$row->created_by."'".','."'".$row->hepl_recruitment_ref_number."'".','."'".$row->cdID."'".','."'".$row->candidate_name."'".');" class="btn btn-vr btn-sm btn-smn" type="button">'.$ageing.'</button>';

                   return $btn;
                  
                  }
                  else{
                      return "";
                  }
                })
                ->addColumn('offer_letter_preview',function($row){

                    if($row->payroll_status !=0){
                        $btn = '<a href="../'.$row->offer_letter_filename.'" target="_blank"><span class="badge bg-primary" id="" title="Preview Offer Letter"><i class="bi bi-eye"></i></span></a>';

                    }
                   else{
                       $btn ='';
                   }
                    return $btn;
                })
                ->rawColumns(['finance_status','leader_status','history','c_doc_status','payroll_status','ageing','remark','action','offer_letter_preview'])
                ->make(true);

        }
        return view('document_collection');
    }

    public function cd_preview(){
        return view('cd_preview');

    }

    public function get_candidate_preview_details(Request $req){

        $input_details = array(
            'cdID'=>$req->input('cdID'),
        );

        $get_candidate_details_result = $this->recrepo->get_candidate_details_ed( $input_details );
        
        $get_candidate_edu_result = $this->recrepo->get_candidate_edu_details( $input_details );

        $get_candidate_exp_result = $this->recrepo->get_candidate_exp_details( $input_details );

        $get_candidate_benefits_result = $this->recrepo->get_candidate_benefits_details( $input_details );

        return response()->json( [
            'candidate_basic_details' => $get_candidate_details_result,
            'candidate_education' => $get_candidate_edu_result,
            'candidate_experience' => $get_candidate_exp_result,
            'candidate_benefits' => $get_candidate_benefits_result
            ] );

    }

    public function update_cdoc_status(Request $req){

        $input_details = array(
            'cdID'=>$req->input('cdID'),
            'c_doc_status'=>$req->input('c_doc_status'),
            'c_doc_remark'=>$req->input('c_doc_remark'),
        );
  $cc1="karthikdavid@hemas.in";
        // $cc2="rfh@hemas.in";
        //$cc1="durgadevi.r@hemas.in";
        $cc2="rfh@hemas.in";
        $process_cdoc_status_result = $this->recrepo->process_cdoc_status( $input_details );

        $user_row = $this->recrepo->get_candidate_row( $req->input('cdID') );

        $to_email = $user_row[0]->candidate_email;
        if($req->input('c_doc_status') =='Not Verified'){
            $details = [
                'candidate_name' => $user_row[0]->candidate_name,
                'remark' => $req->input('c_doc_remark'),
            ];
            
            \Mail::to($to_email)
            ->cc([$cc1,$cc2])
            ->send(new \App\Mail\NotifyCandidateDocNotVerifiedMail($details));
        }else{

            $details = [
                'candidate_name' => $user_row[0]->candidate_name,
            ];
            
            \Mail::to($to_email)
            ->cc([$cc1,$cc2])
            ->send(new \App\Mail\NotifyCandidateDocVerifiedMail($details));
        }

        // offer release followup entry
        $session_user_details = auth()->user();
        $created_by = $session_user_details->empID;

        // $or_followup_input = array(
        //     'cdID' => $req->input('cdID'),
        //     'rfh_no' => $rfh_no,
        //     'hepl_recruitment_ref_number' => $hepl_recruitment_ref_number,
        //     'description' => "Document verified by recruiter",
        //     'created_by' => $created_by,
        // );
        // $put_or_followup_result = $this->recrepo->offer_release_followup_entry( $or_followup_input );

        $response = 'success';
        return response()->json( ['response' => $response] );

    }

    public function get_buddylist(){

        $get_buddylist_result = $this->corepo->get_buddylist(  );

        return $get_buddylist_result;
    }

    public function send_to_payroll(Request $req){
     //  $att_file = $req->input('proof_attach');
       // exit;
        $or_cdID = $req->input('cdID');
        $or_rfh_no = $req->input('rfh_no');
        $get_emp_mode = $req->input('get_emp_mode');
        $closed_salary_pa = $req->input('closed_salary_pa');
        $or_salary_review = $req->input('or_salary_review');
        $or_joining_type = $req->input('or_joining_type');
        $or_remark = $req->input('or_remark');
        $or_doj = $req->input('or_doj');
        $or_bc_mailid = $req->input('or_bc_mailid');
        $or_cc_mailid = $req->input('or_cc_mailid');
        $welcome_buddy = $req->input('welcome_buddy_id');
        $offer_letter_name = $req->input('offer_letter_name');
        $or_department = $req->input('or_department');
        $last_drawn_ctc = $req->input('last_drawn_ctc');
        $registration_type = $req->input('get_reg_type');
        $po_type = $req->input('po_type');
        $approver = $req->input('or_approver');

        $session_user_details = auth()->user();
        $or_recruiter_name = $session_user_details->name;
        $or_recruiter_email = $session_user_details->email;
        $or_recruiter_mobile_no = $session_user_details->mobile_no;

        $input_details = array(
            'cdID'=>$or_cdID,
            'rfh_no'=>$or_rfh_no,
            'or_cc_mailid'=>$or_cc_mailid,
            'or_bc_mailid'=>$or_bc_mailid,
            'or_doj'=>$or_doj,
            'closed_salary_pa'=>$closed_salary_pa,
            'get_emp_mode'=>$get_emp_mode,
            'welcome_buddy'=>$welcome_buddy,
            'offer_letter_filename'=>$offer_letter_name,
            'or_department'=>$or_department,
            'last_drawn_ctc'=>$last_drawn_ctc,
            'register_type'=>$registration_type,
            'or_recruiter_name'=>$or_recruiter_name,
            'or_recruiter_email'=>$or_recruiter_email,
            'or_recruiter_mobile_no'=>$or_recruiter_mobile_no,
            'po_type'=>$po_type,
            'approver'=>$approver,
            'payroll_status'=>1,
            'po_finance_status'=>0,
            'leader_status'=>0,
        );
        $process_sendto_payroll_result = $this->recrepo->process_orpop_status( $input_details );

       

        $input_details_rr = array(
            'rfh_no' => $or_rfh_no ,
        );
        $get_tblrfh_result = $this->corepo->get_recruitment_edit_details($input_details_rr);

        // print_r($get_tblrfh_result);
        // check details already exits
        $input_details = array(
            'cdID'=>$req->input('cdID'),
            'hepl_recruitment_ref_number'=>$get_tblrfh_result[0]->hepl_recruitment_ref_number,
        );

        $check_details_exits = $this->recrepo->check_offer_release_details( $input_details );
        
        if($check_details_exits == 0){
            // insert process
            $input_details = array(
                'cdID'=>$or_cdID,
                'rfh_no'=>$or_rfh_no,
                'profile_status'=>"Offer Released",
                'hepl_recruitment_ref_number'=>$get_tblrfh_result[0]->hepl_recruitment_ref_number,
                'closed_salary'=>$closed_salary_pa,
                'salary_review'=>$or_salary_review,
                'joining_type'=>$or_joining_type,
                'date_of_joining'=>$or_doj,
                'remark'=>$or_remark,
                'created_by'=>auth()->user()->empID,
                'created_on'=>date('Y-m-d')

            );

            $process_offer_release_details_result = $this->recrepo->process_offer_release_details( $input_details );
            
        }

        
        
        // send to mail to all
        $input_details_c = array(
            'cdID'=>$req->input('cdID'),
        );

        // $to_email = 'hrpayroll1@hemas.in';
       // $to_email = 'ganagavathy.k@hemas.in';
       $input_details_ad = array(
        'empID' => 'PR001',
    );

    $get_user_result = $this->corepo->get_user_details($input_details_ad); 
    $to_email=$get_user_result[0]->email;
        $get_candidate_details_result = $this->recrepo->get_candidate_details_ed( $input_details_c );

        $details = [
            'candidate_name' => $get_candidate_details_result[0]->candidate_name,
            'candidate_position' => $get_tblrfh_result[0]->position_title,
            'rfh_no' => $get_candidate_details_result[0]->hepl_recruitment_ref_number,
        ];
       
        $get_cc_mails =$req->input('or_cc_mailid');
        $cc_emails = explode(",",$get_cc_mails);

        $get_bc_mails =$req->input('or_bc_mailid');
        $bcc_emails = explode(",",$get_bc_mails);
    
        \Mail::send('emails.OfferRatificationMail', array('details' => $details), function($message) use($to_email, $cc_emails, $bcc_emails){
            $message->subject('CAREERS@HEPL: OFFER RATIFICATION ');
            $message->to($to_email);
            if(count($cc_emails) >1){
                $message->cc($cc_emails);
            }
            else{
               // $message->cc(['durgadevi.r@hemas.in','rfh@hemas.in']);
                $message->cc(['karthikdavid@hemas.in','rfh@hemas.in']);
            }
            if(count($bcc_emails) >1){
                $message->bcc($bcc_emails);
            }
        });

        // offer release followup entry
        $session_user_details = auth()->user();
        $created_by = $session_user_details->empID;

        $or_followup_input = array(
            'cdID' => $req->input('cdID'),
            'rfh_no' => $or_rfh_no,
            'hepl_recruitment_ref_number' => $get_tblrfh_result[0]->hepl_recruitment_ref_number,
            'description' => "Offer Ratification",
            'created_by' => $created_by,
        );
        $put_or_followup_result = $this->recrepo->offer_release_followup_entry( $or_followup_input );


        $response = 'success';
        return response()->json( ['response' => $response] );

    }

    public function offers_recruiter(){

        return view('offers_recruiter');

    }

    public function get_offer_list_rt_apo(Request $request){

        if ($request->ajax()) {
           
            // get all data
            $session_user_details = auth()->user();
            $created_by = $session_user_details->empID;
                
                $input_details = array(
                    'created_by'=>$created_by,
                    'leader_status'=>"2",
                    'payroll_status' =>"3",
                    'offer_rel_status' =>"0",
                    'offer_rel_status_or' =>"1",
                );
    
                $get_candidate_profile_result = $this->recrepo->get_approved_offers( $input_details );
    
            
            return Datatables::of($get_candidate_profile_result)
                ->addIndexColumn()
                
                ->addColumn('history', function($row) {
                    
                    $btn = '<span class="badge bg-secondary" title="Followup History" onclick="candidate_follow_up('."'".$row->created_by."'".','."'".$row->hepl_recruitment_ref_number."'".','."'".$row->cdID."'".','."'".$row->candidate_name."'".');"><i class="bi bi-stack"></i></span>';
                            return $btn;

                    
                })
                ->addColumn('c_doc_status', function($row) {
                    
                    if($row->c_doc_status =='Verified'){
                        $btn = '<span class="badge bg-success">'.$row->c_doc_status.'</span>';

                    }elseif($row->c_doc_status =='Not Verified'){
                        $btn = '<span class="badge bg-danger">'.$row->c_doc_status.'</span>';

                    }else{
                        $btn = '<span class="badge bg-warning">Pending</span>';

                    }
                                        
                    return $btn;
                })
                ->addColumn('payroll_status', function($row) {

                    if($row->payroll_status == 0){
                        $btn = '<span class="badge bg-danger">Not sent</span>';
                    }elseif($row->payroll_status == 1){
                        $btn = '<span class="badge bg-warning">Pending</span>';
                    }
                    elseif($row->payroll_status == 2){
                        $btn = '<span class="badge bg-info">Inprogress</span>';
                    }
                    elseif($row->payroll_status == 3){
                        $btn = '<span class="badge bg-success">Completed</span>';
                    }
                   else{
                       $btn='';
                   }                         
                    return $btn;
                })
                ->addColumn('ageing',function($row){
                    $credentials = array(

                        'cdID' => $row->cdID,

                        'rfh_no' => $row->rfh_no,

                    );

                    $check_po_details_result = $this->payrepo->check_offer_oat( $credentials );

                    if($check_po_details_result != 0){
                    $credentials = array(
                        'cdID' => $row->cdID,
                        'rfh_no' => $row->rfh_no,
                    );
            
                    $offer_date = $this->payrepo->get_offer_oat_offrat_date( $credentials );
                    $recruiter_last_modified_date = array(
                        'rfh_no'=>$row->rfh_no,
                        'hepl_recruitment_ref_number'=>$row->hepl_recruitment_ref_number,
                        'created_by'=>$row->created_by,
                    );
                    $recruiter_ageing_result = $this->corepo->recruiter_last_modified_date( $recruiter_last_modified_date );
                     $offer_date = $offer_date[0]->created_at;
                
                     $from = strtotime($offer_date);
                $update_date = $row->client_po_update_date;
                if($update_date == ""){
                    $today = time();
                }
                else{
                    $today = $update_date;
                }
                   
                    $difference = $today - $from;
                    $difference_days = floor($difference / 86400);  // (60 * 60 * 24)

                    $ageing = $difference_days;
                   // $ageing = $difference->y.' years, ' .$difference->m.' months, '.$difference->d.' days';

                   // return $offer_date[0]->created_at;
                   // return $recruiter_ageing_result;
                   $btn ='';
                   $btn .= '<button onclick ="get_oat_age_dt('."'".$row->created_by."'".','."'".$row->hepl_recruitment_ref_number."'".','."'".$row->cdID."'".','."'".$row->candidate_name."'".');" class="btn btn-vr btn-sm btn-smn" type="button">'.$ageing.'</button>';

                   return $btn;
                  
                  }
                  else{
                      return "";
                  }
                })
                ->addColumn('finance_status', function($row) {

                    if($row->po_type =='po'){

                        if($row->po_finance_status == 0){
                            $btn = '<span class="badge bg-purple">Not sent</span>';
                        }elseif($row->po_finance_status == 1){
                            $btn = '<span class="badge bg-warning">Pending</span>';
                        }
                        elseif($row->po_finance_status == 2){
                            $btn = '<span class="badge bg-success">Approved</span>';
                        }
                        elseif($row->po_finance_status == 3){
                            $btn = '<span class="badge bg-danger">Rejected</span>';
                        }
                        else{
                            $btn='';
                        }   
                    }    
                    else{
                        $btn='';
                    }                    
                    return $btn;
                })
                ->addColumn('leader_status', function($row) {

                    if($row->leader_status == 0){
                        $btn = '<span class="badge bg-purple">Not sent</span>';
                    }elseif($row->leader_status == 1){
                        $btn = '<span class="badge bg-warning">Pending</span>';
                    }
                    elseif($row->leader_status == 2){
                        $btn = '<span class="badge bg-success">Approved</span>';
                    }
                    elseif($row->leader_status == 3){
                        $btn = '<span class="badge bg-danger">Rejected</span>';
                    }
                   else{
                       $btn='';
                   }                         
                    return $btn;
                })
                ->addColumn('action',function($row){

                    $btn = '<span class="badge bg-primary" onclick="approver_ld_pop('."'".$row->rfh_no."'".','."'".$row->cdID."'".');">Submit</span>';
                    return $btn;
                })
                ->addColumn('offer_letter_preview',function($row){

                    
                        $btn = '<a href="../'.$row->offer_letter_filename.'" target="_blank"><span class="badge bg-primary" id="" title="Preview Offer Letter"><i class="bi bi-eye"></i></span></a>';
                        
                        // $btn .= ' <a href="edit_ctc_oat?cdID='.$row->cdID.'&rfh_no='.$row->rfh_no.'" target="_blank"><span style="margin-top:2px;" class="badge bg-dark" id="" title="Edit Offer Letter"><i class="bi bi-pencil"></i></span><a>';

                  
                    return $btn;
                })

                ->addColumn('po_type',function($row){

                    if($row->po_type =='po'){
                        $btn = '<span  class="badge bg-success">PO</span>';
                    }elseif($row->po_type =='non_po'){
                        $btn = '<span  class="badge bg-danger">NonPO</span>';
                    }else{
                        $btn='';
                    }
                    

              
                return $btn;
                })
                ->addColumn('offer_release',function($row){

                    if($row->offer_rel_status ==0 && $row->payroll_status == 3 && $row->leader_status == 2){
                        $btn = '<span  class="badge bg-primary" id="or_btn_'.$row->cdID.'" onclick="send_offerpop('."'".$row->rfh_no."'".','."'".$row->cdID."'".');">Send</span>';

                    }
                    elseif($row->offer_rel_status ==1){
                        $btn = '<span  class="badge bg-info" disabled="disabled">Sent</span>';

                    }
                    else{
                        $btn = '';

                    }
                return $btn;
                })
                ->rawColumns(['finance_status','leader_status','offer_release','history','c_doc_status','payroll_status','action','offer_letter_preview','ageing','po_type'])
                ->make(true);

        }
    }

    public function send_to_candidate_ol(Request $request){

        $get_assigned_to = auth()->user();
        $created_by = $get_assigned_to->empID;

        $input_details_rr = array(
            'rfh_no' => $request->input('rfh_no'),
        );
        $get_tblrfh_result = $this->corepo->get_recruitment_edit_details($input_details_rr);

        $input_details_c = array(
            'cdID'=>$request->input('cdID'),
        );

        $get_candidate_details_result = $this->recrepo->get_candidate_details_ed( $input_details_c );

        // print_r($get_tblrfh_result);
        
            
            // update status in candidate details table
            $input_details_cd = array(
                'cdID'=>$request->input('cdID'),
                'status'=>"Offer Released",
                'offer_rel_status'=>"1"

            );

            $cd_status_update = $this->recrepo->cd_status_update_ors( $input_details_cd );

            // update candidate follow up
            $cfdID = 'CFD'.( ( DB::table( 'candidate_followup_details' )->max( 'id' ) )+1 );

            $candidate_followup_details = array(
                'cfdID'=>$cfdID,
                'cdID'=>$request->input('cdID'),
                'rfh_no'=>$request->input('rfh_no'),
                'follow_up_status'=>"Offer Released",
                'created_on'=>date('Y-m-d'),
                'created_by'=>$created_by
            );
            Candidate_followup_details::create($candidate_followup_details);

            // $response = 'Updated';
       
        $doc_upload_link =  url('/')."/candidate_dc/".base64_encode($request->input('cdID'));

        $to_email=$get_candidate_details_result[0]->candidate_email;

        $get_title = "CAREERS@HEPL:  OFFER LETTER";
        $get_body1 = "Dear Mr / Ms ".$get_candidate_details_result[0]->candidate_name;
        $get_body2 ='CONGRATULATIONS !  We are delighted to offer you the position of '.$get_tblrfh_result[0]->position_title.' in the Band '.$get_tblrfh_result[0]->band;
        $get_body3 ='You are expected to join us on or before '.$get_candidate_details_result[0]->or_doj.' This offer automatically stands cancelled in the event of you not joining on the said date. ';
        $get_body4 ='Please reach out to our Talent Acquisition Specialist for any clarifications related to the offer. ';
        $get_body5 ='We wish you a very rewarding career with us. ';
        $get_body6 =$doc_upload_link;
        $details = [
                    'title' => $get_title,
                    'body1' => $get_body1,
                    'body2' => $get_body2,
                    'body3' => $get_body3,
                    'body4' => $get_body4,
                    'body5' => $get_body5,
                    'body6' => $get_body6,
        ];
           // ->cc(['durgadevi.r@hemas.in','rfh@hemas.in'])
        \Mail::to($to_email)
       ->cc(['karthikdavid@hemas.in','rfh@hemas.in'])
        ->send(new \App\Mail\MyTestMail($details));
        $response = 'success';
        return response()->json( ['response' => $response] );

    }

    public function get_offer_accepted_for_rr(Request $request){

        if ($request->ajax()) {
           
            // get all data
            $session_user_details = auth()->user();
            $created_by = $session_user_details->empID;
                
                $input_details = array(
                    'created_by'=>$created_by,
                    'offer_rel_status'=>"2",
                );
    
                $get_candidate_profile_result = $this->recrepo->get_offer_accepted_for_rr_dt( $input_details );
    
            
            return Datatables::of($get_candidate_profile_result)
                ->addIndexColumn()
                
                ->addColumn('history', function($row) {
                    
                    $btn = '<span class="badge bg-secondary" title="Followup History" onclick="candidate_follow_up('."'".$row->created_by."'".','."'".$row->hepl_recruitment_ref_number."'".','."'".$row->cdID."'".','."'".$row->candidate_name."'".');"><i class="bi bi-stack"></i></span>';
                            return $btn;

                    
                })
                ->addColumn('c_doc_status', function($row) {
                    
                    if($row->c_doc_status =='Verified'){
                        $btn = '<span class="badge bg-success">'.$row->c_doc_status.'</span>';

                    }elseif($row->c_doc_status =='Not Verified'){
                        $btn = '<span class="badge bg-danger">'.$row->c_doc_status.'</span>';

                    }else{
                        $btn = '<span class="badge bg-warning">Pending</span>';

                    }
                                        
                    return $btn;
                })
                ->addColumn('payroll_status', function($row) {

                    if($row->payroll_status == 0){
                        $btn = '<span class="badge bg-danger">Not sent</span>';
                    }elseif($row->payroll_status == 1){
                        $btn = '<span class="badge bg-warning">Pending</span>';
                    }
                    elseif($row->payroll_status == 2){
                        $btn = '<span class="badge bg-info">Inprogress</span>';
                    }
                    elseif($row->payroll_status == 3){
                        $btn = '<span class="badge bg-success">Completed</span>';
                    }
                   else{
                       $btn='';
                   }                         
                    return $btn;
                })
                
                
                ->addColumn('offer_letter_preview',function($row){

                        $btn = '<a href="../'.$row->offer_letter_filename.'" target="_blank"><span class="badge bg-primary" id="" title="Preview Offer Letter"><i class="bi bi-eye"></i></span></a>';
                        
                    return $btn;
                })

                ->addColumn('po_type',function($row){

                    if($row->po_type =='po'){
                        $btn = '<span  class="badge bg-success">PO</span>';
                    }elseif($row->po_type =='non_po'){
                        $btn = '<span  class="badge bg-danger">NonPO</span>';
                    }else{
                        $btn='';
                    }
                    
                return $btn;
                })
                ->addColumn('offer_release',function($row){

                    if($row->offer_rel_status ==0 && $row->payroll_status == 3 && $row->leader_status == 2){
                        $btn = '<span  class="badge bg-info" id="or_btn_'.$row->cdID.'" onclick="send_offerpop('."'".$row->rfh_no."'".','."'".$row->cdID."'".');">Send</span>';

                    }
                    elseif($row->offer_rel_status ==1){
                        $btn = '<span  class="badge bg-info" disabled="disabled">Sent</span>';

                    }
                    else{
                        $btn = '';

                    }
                return $btn;
                })
                ->rawColumns(['offer_release','history','c_doc_status','payroll_status','offer_letter_preview','po_type'])
                ->make(true);

        }

    }

    public function get_offer_rejected_for_rr(Request $request){

        if ($request->ajax()) {
           
            // get all data
            $session_user_details = auth()->user();
            $created_by = $session_user_details->empID;
                
                $input_details = array(
                    'created_by'=>$created_by,
                    'offer_rel_status'=>"3",
                );
    
                $get_candidate_profile_result = $this->recrepo->get_offer_accepted_for_rr_dt( $input_details );
    
            
            return Datatables::of($get_candidate_profile_result)
                ->addIndexColumn()
                
                ->addColumn('history', function($row) {
                    
                    $btn = '<span class="badge bg-secondary" title="Followup History" onclick="candidate_follow_up('."'".$row->created_by."'".','."'".$row->hepl_recruitment_ref_number."'".','."'".$row->cdID."'".','."'".$row->candidate_name."'".');"><i class="bi bi-stack"></i></span>';
                            return $btn;

                    
                })
                ->addColumn('c_doc_status', function($row) {
                    
                    if($row->c_doc_status =='Verified'){
                        $btn = '<span class="badge bg-success">'.$row->c_doc_status.'</span>';

                    }elseif($row->c_doc_status =='Not Verified'){
                        $btn = '<span class="badge bg-danger">'.$row->c_doc_status.'</span>';

                    }else{
                        $btn = '<span class="badge bg-warning">Pending</span>';

                    }
                                        
                    return $btn;
                })
                ->addColumn('payroll_status', function($row) {

                    if($row->payroll_status == 0){
                        $btn = '<span class="badge bg-danger">Not sent</span>';
                    }elseif($row->payroll_status == 1){
                        $btn = '<span class="badge bg-warning">Pending</span>';
                    }
                    elseif($row->payroll_status == 2){
                        $btn = '<span class="badge bg-info">Inprogress</span>';
                    }
                    elseif($row->payroll_status == 3){
                        $btn = '<span class="badge bg-success">Completed</span>';
                    }
                   else{
                       $btn='';
                   }                         
                    return $btn;
                })
                
                
                ->addColumn('offer_letter_preview',function($row){

                        $btn = '<a href="../'.$row->offer_letter_filename.'" target="_blank"><span class="badge bg-primary" id="" title="Preview Offer Letter"><i class="bi bi-eye"></i></span></a>';
                        
                    return $btn;
                })

                ->addColumn('po_type',function($row){

                    if($row->po_type =='po'){
                        $btn = '<span  class="badge bg-success">PO</span>';
                    }elseif($row->po_type =='non_po'){
                        $btn = '<span  class="badge bg-danger">NonPO</span>';
                    }else{
                        $btn='';
                    }
                    
                return $btn;
                })
                ->addColumn('offer_release',function($row){

                    if($row->offer_rel_status ==0 && $row->payroll_status == 3 && $row->leader_status == 2){
                        $btn = '<span  class="badge bg-info" id="or_btn_'.$row->cdID.'" onclick="send_offerpop('."'".$row->rfh_no."'".','."'".$row->cdID."'".');">Send</span>';

                    }
                    elseif($row->offer_rel_status ==1){
                        $btn = '<span  class="badge bg-info" disabled="disabled">Sent</span>';

                    }
                    else{
                        $btn = '';

                    }
                return $btn;
                })
                ->rawColumns(['offer_release','history','c_doc_status','payroll_status','offer_letter_preview','po_type'])
                ->make(true);

        }

    }



    public function get_oat_ageing_dt(Request $req){
        $input_details_oat = array(
            'cdID' => $req->input('cdID'),
            
        );
        $ageing_dt = $this->payrepo->get_offer_oat_date($input_details_oat);

        $input_details_pd = array(
            'hepl_recruitment_ref_number'=>$req->input('hepl_recruitment_ref_number'),
            'assigned_to'=>$req->input('created_by'),
        );

        $cfu_history_pd_result = $this->payrepo->candidate_follow_up_history_pd( $input_details_pd );

        return response()->json( [
            'age_dt' => $ageing_dt,
            'ch_pdr' => $cfu_history_pd_result
            ] );
       // return response()->json( ['age_dt' => $ageing_dt] ); 
    }

    public function get_department_list(){
        $get_department_result = $this->corepo->get_department(  );

        return $get_department_result;
    }
public function upload_rc_file_attach(Request $req){
    $input_details_f = array(
        'cdID' => $req->input('cdID'),
        'proof_attach' => $req->file('proof_attach'),
    );
  //  print_r( $input_details_f );
    if($req->hasFile('proof_attach')){
            $path = public_path().'/rc_app_attach/'.$req->input('cdID');

            File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);
            
            $foo = File::extension($req->file('proof_attach')->getClientOriginalName());

            $rc_po_attach =  $req->input('rfh_no').'_'.$req->input('cdID').'.'.$foo;  
            
            $image = $req->file('proof_attach');
            
            if (\File::exists($path)) \File::deleteDirectory($path);

            $image->move($path, $rc_po_attach);

          
            $input_details_f = array(
                'cdID' => $req->input('cdID'),
                'proof_attach' => $rc_po_attach,
            );
            $file_update = $this->recrepo->file_proof_attach( $input_details_f );

        }
return "success";
         // print_r($file_update);
}

}
