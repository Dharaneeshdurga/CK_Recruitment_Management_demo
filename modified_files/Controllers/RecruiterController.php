<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\IRecruiterRepository; 
use DataTables;
use DB;
use App\Models\Candidate_details;
use App\Models\Candidate_followup_details;
use DateTime;

class RecruiterController extends Controller
{
    public function __construct(IRecruiterRepository $recrepo)
    {
        $this->recrepo = $recrepo;

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

                        $ageing = $difference_days.' days';
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
                            $status_btn = '<button type="button" class="btn btn-primary btn-sm" onclick="process_actionday(this,'."'".$row->hepl_recruitment_ref_number."'".','."'".$row->rfh_no."'".');"  title="Fresh Profiles submitted"><i class="bi bi-file-earmark-medical-fill"></i></button>';

                        }
                        
                        $btn = '<button class="btn btn-sm btn-success" id="btnHistory" style="margin-top:2px;" onclick="view_history('."'".$row->hepl_recruitment_ref_number."'".','."'".$row->rfh_no."'".');" title="Edit"><i class="bi bi-pen-fill"></i></button>';
                        
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

                        if($row->critical_position =='Yes'){
                            $ageing_end = 15;

                            
                        }else if($row->critical_position =='Nill'){
                            $ageing_end = 15;

                        }
                        else{
                            $ageing_end = $row->tat_days;

                        }

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
                    'created_on'=>date('Y-m-d'),
                    'created_by'=>$created_by,
                    'candidate_cv'=>$cv_name,
                    'red_flag_status'=>"0"
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

        $hepl_recruitment_ref_number=$req->input( 'hepl_recruitment_ref_number' );

        $show_uploaded_cv_result = $this->recrepo->show_uploaded_cv( $hepl_recruitment_ref_number );

        return Datatables::of($show_uploaded_cv_result)
                    ->addIndexColumn()
                    ->addColumn('status', function($row) {
                        
                        $btn = $row->status;
                        return $btn;
                    })
                    ->addColumn('follow_up', function($row) {
                        
                        $btn = '<span class="badge bg-secondary" title="Followup History" onclick="candidate_follow_up('."'".$row->cdID."'".','."'".$row->candidate_name."'".');"><i class="bi bi-stack"></i></span>';
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

                        $ageing = $difference_days.' days';
                        // $ageing = $difference->y.' years, ' .$difference->m.' months, '.$difference->d.' days';

                        return $ageing;

                    })
                    ->addColumn('action', function($row) {
                        
                        if($row->status =='Candidate Onboarded'){
                            $status_btn = '<select name="cv_action_for_the_day" id="cv_action_for_the_day_'.$row->cdID.'" class="form-control" disabled onchange="cv_process_actionday(this,'."'".$row->cdID."'".','."'".$row->candidate_name."'".','."'".$row->hepl_recruitment_ref_number."'".');">';
                        }
                        else{
                            $status_btn = '<select name="cv_action_for_the_day" id="cv_action_for_the_day_'.$row->cdID.'" class="form-control" onchange="cv_process_actionday(this,'."'".$row->cdID."'".','."'".$row->candidate_name."'".','."'".$row->hepl_recruitment_ref_number."'".');">';
                        }
                        $status_btn .= '<option value="">Select</option>';
                        $status_btn .= '<option value="Fresh Profile Submitted">Fresh Profile Submitted</option>';
                        $status_btn .= '<option value="Profiles Shortlist On-going">Profiles Shortlist On-going</option>';
                        $status_btn .= '<option value="Profiles shared for Screening with Interviewing Manager">Profiles shared for Screening with Interviewing Manager</option>';
                        $status_btn .= '<option value="Interview Ongoing">Interview Ongoing</option>';
                        $status_btn .= '<option value="CTC Negotiation on">CTC Negotiation on</option>';
                        $status_btn .= '<option value="Offer Released">Offer Released</option>';
                        $status_btn .= '<option value="Offer Accepted">Offer Accepted</option>';
                        $status_btn .= '<option value="Offer Rejected">Offer Rejected</option>';
                        $status_btn .= '<option value="Candidate Onboarded">Candidate Onboarded</option>';
                        $status_btn .= '<option value="Profile Rejected">Profile Rejected</option>';
                        $status_btn .= '</select>';
                        
                        $btn ='  <button class="btn btn-sm btn-primary btnDefaultSubmit" style="margin-top:5px;display:none;" id="btnDefaultSubmit_'.$row->cdID.'" onclick="process_default_status('."'".$row->cdID."'".','."'".$row->hepl_recruitment_ref_number."'".');" >Submit</button>';


                        return $status_btn." ".$btn;
                    })
                    ->rawColumns(['candidate_cv','status','action','follow_up','updated_on'])
                    ->make(true);
    }

    public function process_default_status(Request $request){

        $get_assigned_to = auth()->user();
        $assigned_to = $get_assigned_to->empID;
        
        $history_rfh_no = $request->input('history_rfh_no');

        if($request->input('action_for_the_day') =='Offer Accepted' ||$request->input('action_for_the_day') =='Candidate Onboarded'){
            
            // update position status as closed 
            $credentials = array(
                'hepl_recruitment_ref_number'=>$request->input('hepl_recruitment_ref_number'),
                'closed_date'=>date('Y-m-d'),
                'request_status'=>"Closed"
            );
            $update_position_status_result = $this->recrepo->update_position_status_closed( $credentials );

            $input_details = array(
                'action_for_the_day_status'=>$request->input('action_for_the_day'),
                'hepl_recruitment_ref_number'=>$request->input('hepl_recruitment_ref_number'),
                'cdID'=>$request->input('cdID'),
                'assigned_to'=>$assigned_to,
    
            );
    
            $process_default_status_result = $this->recrepo->process_default_status( $input_details );

            // send mail to recrutor
            $candidate_row = $this->recrepo->get_candidate_row( $request->input('cdID') );
            $recruitment_requests_row = $this->recrepo->get_recr_req( $request->input('hepl_recruitment_ref_number') );
            $user_row = $this->corepo->get_user_row( $assigned_to );
            $to_email=$user_row[0]->email;
            $body_content1 = "Dear ".$user_row[0]->name;
            $body_content2 = "".$request->input('action_for_the_day')."&nbsp;For&nbsp;".$candidate_row[0]->candidate_name."(".$request->input('cdID').")";
            $body_content3 = "Position Title : ".$recruitment_requests_row[0]->position_title."";
            $body_content4 = "Have any queries please contact our support Team."; 
            // $body_content5 = "Support Number : 9087428914"; 
            $body_content5 = ""; 
            $details = [
                'subject' => $request->input('action_for_the_day'),
                // 'title' => 'Your code is: '.$otp,
                'body_content1' => $body_content1,
                'body_content2' => $body_content2,
                'body_content3' => $body_content3,
                'body_content4' => $body_content4,
                'body_content5' => $body_content5, 
            ];

            $footer_img='<img src="http://hemas.in/wp-content/uploads/2021/04/cropped-Hema-logo-1.png" alt="" style="width:100px;">';
            $footer_th='<p>Thank you</p>';
            $footer_ad='<p>The HEPL Team</p>';

            $to      = $to_email;
            $subject = $details['subject'];
            $message = '<html>
            <body><p>'.$body_content1."</p>\r\n<h3>".$body_content2."</h3>\r\n<p>".$body_content3."</p>\r\n<p>".$body_content4."</p>\r\n".$footer_th."\r\n".$footer_img."\r\n".$footer_ad."</body>
            </html>";
            // To send HTML mail, the Content-type header must be set
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= 'From: lakshminarayanan@hemas.in'. "\r\n" .
                        'Reply-To: lakshminarayanan@hemas.in' . "\r\n" .
                        'X-Mailer: PHP/' . phpversion();
            mail($to, $subject, $message, $headers);
            //    send mail end

            // send mail to backendcoordinator
            // $back_end_co_id=$recruitment_requests_row[0]->modified_by;
            $back_end_co_row = $this->corepo->get_user_row( "900101" );
            $to_email=$back_end_co_row[0]->email;
            $body_content1 = "Dear ".$back_end_co_row[0]->name;
            $body_content2 = "".$request->input('action_for_the_day')."&nbsp;For&nbsp;".$candidate_row[0]->candidate_name."(".$request->input('cdID').")";
            $body_content3 = "Position Title : ".$recruitment_requests_row[0]->position_title."";
            $body_content4 = "Have any queries please contact our support Team."; 
            // $body_content5 = "Support Number : 9087428914"; 
            $body_content5 = ""; 
            
            $details = [
                'subject' => $request->input('action_for_the_day'),
                // 'title' => 'Your code is: '.$otp,
                'body_content1' => $body_content1,
                'body_content2' => $body_content2,
                'body_content3' => $body_content3,
                'body_content4' => $body_content4,
                'body_content5' => $body_content5, 
            ];

            $footer_img='<img src="http://hemas.in/wp-content/uploads/2021/04/cropped-Hema-logo-1.png" alt="" style="width:100px;">';
            $footer_th='<p>Thank you</p>';
            $footer_ad='<p>The HEPL Team</p>';

            $to      = $to_email;
            $subject = $details['subject'];
            $message = '<html>
            <body><p>'.$body_content1."</p>\r\n<h3>".$body_content2."</h3>\r\n<p>".$body_content3."</p>\r\n<p>".$body_content4."</p>\r\n".$footer_th."\r\n".$footer_img."\r\n".$footer_ad."</body>
            </html>";
            // To send HTML mail, the Content-type header must be set
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= 'From: lakshminarayanan@hemas.in'. "\r\n" .
                        'Reply-To: lakshminarayanan@hemas.in' . "\r\n" .
                        'X-Mailer: PHP/' . phpversion();
            mail($to, $subject, $message, $headers);
            // exit();
            
        }
        else{
            $input_details = array(
                'action_for_the_day_status'=>$request->input('action_for_the_day'),
                'hepl_recruitment_ref_number'=>$request->input('hepl_recruitment_ref_number'),
                'cdID'=>$request->input('cdID'),
                'assigned_to'=>$assigned_to,
    
            );
    
            $process_default_status_result = $this->recrepo->process_default_status( $input_details );
        }

        if($request->input('action_for_the_day') =='Profile Rejected'){
            // update position status as closed 
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
                'closed_date'=>$request->input('or_closed_date'),
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
            'joining_type'=>"Later Date",
            'request_status'=>"Open",
            'profile_status_1'=>"Profile Rejected",
            'profile_status_2'=>"Candidate Onboarded"
        );

        $get_offer_released_tb_result = $this->recrepo->get_offer_released_tb( $input_details );

        return Datatables::of($get_offer_released_tb_result)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                        
                $btn = '<span class="badge bg-primary" title="Edit" onclick="edit_offer_released('."'".$row->cdID."'".','."'".$row->candidate_name."'".','."'".$row->hepl_recruitment_ref_number."'".','."'".$row->rfh_no."'".');"><i class="bi bi-pen-fill"></i></span>';
                
                $btn_select = '<span class="badge bg-success" title="Candidate Onboard" onclick="or_ldj_onboard('."'".$row->cdID."'".','."'".$row->rfh_no."'".','."'".$row->hepl_recruitment_ref_number."'".');"><i class="bi bi-person-badge-fill"></i></span>';
                
                return $btn."  ".$btn_select;


            })
            ->addColumn('candidate_cv', function($row) {
                        
                $btn = '<a href="../cv_upload/'.$row->candidate_cv.'" class="badge bg-info" target="_blank"><i class="bi bi-eye"></i></a>';
                return $btn;
            })

            ->addColumn('closed_date', function($row) {
                        
                $originalDate = $row->closed_date;
                $closed_date = date("d-m-Y", strtotime($originalDate));

                return $closed_date;
            })
        
            ->addColumn('date_of_joining', function($row) {
                        
                $originalDate = $row->date_of_joining;
                $date_of_joining = date("d-m-Y", strtotime($originalDate));

                return $date_of_joining;
            })
            ->rawColumns(['candidate_cv','action','closed_date','date_of_joining'])
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

                $ageing = $difference_days.' days';
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
            ->addColumn('action', function($row) {
                
                if($row->request_status == 'Closed'){
                    $status_btn = '<button type="button" class="btn btn-primary btn-sm" disabled onclick="process_actionday(this,'."'".$row->hepl_recruitment_ref_number."'".','."'".$row->rfh_no."'".');"  title="Fresh Profiles submitted"><i class="bi bi-file-earmark-medical-fill"></i></button>';

                }
                else{
                    $status_btn = '<button type="button" class="btn btn-primary btn-sm" onclick="process_actionday(this,'."'".$row->hepl_recruitment_ref_number."'".','."'".$row->rfh_no."'".');"  title="Fresh Profiles submitted"><i class="bi bi-file-earmark-medical-fill"></i></button>';

                }
                        
                $btn = '<button class="btn btn-sm btn-success" id="btnHistory" style="margin-top:2px;" onclick="view_history('."'".$row->hepl_recruitment_ref_number."'".','."'".$row->rfh_no."'".');" title="Edit"><i class="bi bi-pen-fill"></i></button>';
                                      
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

                if($row->critical_position =='Yes'){
                    $ageing_end = 15;

                    
                }else if($row->critical_position =='Nill'){
                    $ageing_end = 15;

                }
                else{
                    $ageing_end = $row->tat_days;

                }

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

            ->rawColumns(['tat_process','ageing','assigned_date','open_date','action','request_status','history','action_for_the_day_status','interviewer'])
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


            $response = 'Updated';
            return response()->json( ['response' => $response] );
        }
        
    }

    public function candidate_follow_up_history(Request $req){
        
        $input_details = array(
            'cdID'=>$req->input('cdID'),
        );

        $cfu_history_result = $this->recrepo->candidate_follow_up_history( $input_details );

        return $cfu_history_result;
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
                
                $btn = '<span class="badge bg-secondary" title="Followup History" onclick="candidate_follow_up('."'".$row->cdID."'".','."'".$row->candidate_name."'".');"><i class="bi bi-stack"></i></span>';
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
            $af_position_status = (!empty($_POST["af_position_status"])) ? ($_POST["af_position_status"]) : ('');
            
            if( $af_from_date || $af_to_date  || $af_position_title || $af_position_status) 
            {
                // get all data
                $advanced_filter = array(
                    'af_from_date'=>$af_from_date,
                    'af_to_date'=>$af_to_date,
                    'af_position_title'=>$af_position_title,
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
                    
                    $btn = '<span class="badge bg-secondary" title="Followup History" onclick="candidate_follow_up('."'".$row->cdID."'".','."'".$row->candidate_name."'".');"><i class="bi bi-stack"></i></span>';
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
                ->rawColumns(['candidate_cv','history','status','status_cont'])
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

                $ageing = $difference_days.' days';
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
            $af_critical_position = (!empty($_POST["af_critical_position"])) ? ($_POST["af_critical_position"]) : ('');
            $af_position_status = (!empty($_POST["af_position_status"])) ? ($_POST["af_position_status"]) : ('');
            $af_assigned_status = (!empty($_POST["af_assigned_status"])) ? ($_POST["af_assigned_status"]) : ('');
            $af_salary_range = (!empty($_POST["af_salary_range"])) ? ($_POST["af_salary_range"]) : ('');
            $af_band = (!empty($_POST["af_band"])) ? ($_POST["af_band"]) : ('');
            $af_location = (!empty($_POST["af_location"])) ? ($_POST["af_location"]) : ('');
            $af_business = (!empty($_POST["af_business"])) ? ($_POST["af_business"]) : ('');
            $af_billing_status = (!empty($_POST["af_billing_status"])) ? ($_POST["af_billing_status"]) : ('');
            $af_function = (!empty($_POST["af_function"])) ? ($_POST["af_function"]) : ('');

            $session_user_details = auth()->user();
            $assigned_to = $session_user_details->empID;

            if( $af_from_date || $af_to_date  || $af_position_title || 
                $af_critical_position || $af_position_status || $af_assigned_status || 
                $af_salary_range || $af_band || $af_location || $af_business || 
                $af_billing_status || $af_billing_status ) 
            {
                // get all data
                $advanced_filter = array(
                    'af_from_date'=>$af_from_date,
                    'af_to_date'=>$af_to_date,
                    'af_position_title'=>$af_position_title,
                    'af_critical_position'=>$af_critical_position,
                    'af_position_status'=>$af_position_status,
                    'af_assigned_status'=>$af_assigned_status,
                    'af_salary_range'=>$af_salary_range,
                    'af_band'=>$af_band,
                    'af_location'=>$af_location,
                    'af_business'=>$af_business,
                    'af_billing_status'=>$af_billing_status,
                    'af_function'=>$af_function,
                    'assigned_to'=>$assigned_to,
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

                        $ageing = $difference_days.' days';
                        // $ageing = $difference->y.' years, ' .$difference->m.' months, '.$difference->d.' days';

                        return $ageing;
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
                        $btn = '<a href="ticket_cd_recruiter?hr_refno='.$row->hepl_recruitment_ref_number.'"><span class="badge bg-primary" id="btnAssign" title="Candidate Details"><i class="bi bi-people-fill"></i></span></a>';
                        
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

                        if($row->critical_position =='Yes'){
                            $ageing_end = 15;

                            
                        }else if($row->critical_position =='Nill'){
                            $ageing_end = 15;

                        }
                        else{
                            $ageing_end = $row->tat_days;

                        }

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

                    ->rawColumns(['ageing','open_date','action','assigned_status','tat_process','interviewer'])
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
                    'hepl_recruitment_ref_number'=>$request->input('hepl_recruitment_ref_number'),
                );
    
                $get_ticket_candidate_details_result = $this->recrepo->ticket_candidate_details_af( $input_details );
    
            }
            else{
                $input_details = array(
                    'created_by'=>$created_by,
                    'hepl_recruitment_ref_number'=>$request->input('hepl_recruitment_ref_number'),
                );
    
                $get_ticket_candidate_details_result = $this->recrepo->ticket_candidate_details( $input_details );
    
            }
            
            return Datatables::of($get_ticket_candidate_details_result)
                ->addIndexColumn()
                ->addColumn('candidate_cv', function($row) {
                            
                    $btn = '<a href="../cv_upload/'.$row->candidate_cv.'" class="badge bg-info" target="_blank"><i class="bi bi-eye"></i></a>';
                    return $btn;
                })
                
                ->addColumn('history', function($row) {
                    
                    $btn = '<span class="badge bg-secondary" title="Followup History" onclick="candidate_follow_up('."'".$row->cdID."'".','."'".$row->candidate_name."'".');"><i class="bi bi-stack"></i></span>';
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


}
