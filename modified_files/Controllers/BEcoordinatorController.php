<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ICoordinatorRepository; 
use App\Repositories\IRecruiterRepository; 

use DataTables;
use DateTime;
use DB;
class BEcoordinatorController extends Controller
{
    public function __construct(ICoordinatorRepository $corepo,IRecruiterRepository $recrepo)
    {
        $this->corepo = $corepo;
        $this->recrepo = $recrepo;

        $this->middleware('backend_coordinator');
    }

    public function add_recruit_request()
    {
        return view('add_recruit_request');
    }

    public function view_recruit_request_default(Request $request){
        if ($request->ajax()) {
            
            // get all data
            $where_cond = 'Pending';
            $get_reqcruitment_request_result = $this->corepo->get_reqcruitment_request_default( $where_cond );

            // get recruiter list
            $get_recruiter_result = $this->corepo->get_recruiter_list( );
            
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
                    ->addColumn('assigned_status', function($row) {
                        
                        if($row->assigned_status =='Assigned'){
                            $btn = '<span class="badge bg-secondary" title="'.$row->assigned_status.'"><i class="bi bi-shield-check"></i></span>';
                        }else{
                            $btn = '<span class="badge bg-danger" title="'.$row->assigned_status.'"><i class="bi bi-shield-slash"></i></span>';
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

                        $btn2 = '<span class="badge bg-info" title="Edit"  onclick="ticket_edit_process('."'".$row->rfh_no."'".');"><i class="bi bi-pencil-square"></i></span>';
                        return $btn." ".$btn1." ".$btn2;
                    })
                    ->addColumn('action', function($row) {
                        $btn = '<a href="view_recruit_request?rfh_no='.$row->rfh_no.'"><span class="badge bg-primary" id="btnAssign" title="Assign"><i class="bi bi-person-lines-fill"></i></span></a>';
                        
                        $btn .= ' <span class="badge bg-danger" style="margin-top:2px"  onclick="ticket_delete_process('."'".$row->rfh_no."'".');" id="btndelete" title="Delete"><i class="bi bi-trash"></i></span>';
                        
                        return $btn;

                    })
                    ->addColumn('assigned_status_text', function($row){
                        $btn = $row->assigned_status;
                        
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
                            $get_interviewer_self = $this->corepo->get_interviewer_self( $row->rfh_no );
                            // print_r($get_interviewer_self);
                        }
                        return $get_interviewer;

                    })
                    ->rawColumns(['ageing','open_date','action','assigned_status','tat_process','interviewer'])
                    ->make(true);
        }
        return view('view_recruit_request_default');
    }
    public function view_recruit_request_default_ag(Request $request){
        if ($request->ajax()) {
            
            // get all data
            $where_cond = 'Assigned';
            $get_reqcruitment_request_result = $this->corepo->get_reqcruitment_request_default( $where_cond  );

            // get recruiter list
            $get_recruiter_result = $this->corepo->get_recruiter_list( );
            
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

                        $ageing_nw = $difference_days.' days';
                        return $ageing_nw;
                    })
                    ->addColumn('open_date', function($row) {
                        
                        $originalDate = $row->open_date;
                        $newDate = date("d-m-Y", strtotime($originalDate));

                        return $newDate;
                    })
                    ->addColumn('assigned_status', function($row) {
                        
                        if($row->assigned_status =='Assigned'){
                            $btn = '<span class="badge bg-secondary" title="'.$row->assigned_status.'"><i class="bi bi-shield-check"></i></span>';
                        }else{
                            $btn = '<span class="badge bg-danger" title="'.$row->assigned_status.'"><i class="bi bi-shield-slash"></i></span>';
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

                        $btn2 = '<span class="badge bg-info" title="Edit"  onclick="ticket_edit_process('."'".$row->rfh_no."'".');"><i class="bi bi-pencil-square"></i></span>';
                        return $btn." ".$btn1." ".$btn2;
                    })
                    ->addColumn('action', function($row) {
                        $btn = '<a href="view_recruit_request?rfh_no='.$row->rfh_no.'"><span class="badge bg-primary" id="btnAssign" title="Assign"><i class="bi bi-person-lines-fill"></i></span></a>';
                        
                        $btn .= ' <span class="badge bg-danger" style="margin-top:2px"  onclick="ticket_delete_process('."'".$row->rfh_no."'".');" id="btndelete" title="Delete"><i class="bi bi-trash"></i></span>';
                        
                        return $btn;

                    })
                    ->addColumn('assigned_status_text', function($row){
                        $btn = $row->assigned_status;
                        
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
                            $get_interviewer_self = $this->corepo->get_interviewer_self( $row->rfh_no );

                            $get_interviewer = $get_interviewer_self[0]->name;
                        }
                        return $get_interviewer;

                    })
                    ->rawColumns(['ageing','open_date','action','assigned_status','tat_process','interviewer'])
                    ->make(true);
        }
        return view('view_recruit_request_default');
    }
    public function view_recruit_request(Request $request){

        if ($request->ajax()) {
            
            // get all data
            $rfh_no = $request->input( 'rfh_no' );
            
            $get_reqcruitment_request_result = $this->corepo->get_reqcruitment_request( $rfh_no );

            // get recruiter list
            $get_recruiter_result = $this->corepo->get_recruiter_list( );
            
            
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

                    ->addColumn('assigned_status', function($row) {
                        
                        if($row->assigned_status =='Assigned'){
                            $btn = '<span class="badge bg-secondary" title="'.$row->assigned_status.'"><i class="bi bi-shield-check"></i></span>';
                        }else{
                            $btn = '<span class="badge bg-danger" title="'.$row->assigned_status.'"><i class="bi bi-shield-slash"></i></span>';
                        }
                        
                        if($row->request_status =='Open'){
                            $btn1 = '<span class="badge bg-warning" title="Open"><i class="fa fa-book-open"></i></span>';
                        }else{
                            $btn1 = '<span class="badge bg-success" title="Closed"><i class="fa fa-book"></i></span>';
                        }
                        return $btn." ".$btn1;
                    })
                    ->addColumn('action', function($row) use ($get_recruiter_result){
                        
                        $btn = '<span class="badge bg-danger" id="err_show_'.$row->recReqID.'" style="display:none;margin-bottom: 5px;">* Fields Required</span>';
                        
                        if($row->assigned_status =='Assigned'){
                            $btn .= '<button class="btn btn-sm btn-primary" id="btnAssign" onclick="process_assign('."'".$row->recReqID."'".');">Assign</button>';
                            
                            $recruiter_list ='<h6>Assigned To</h6>';
                            $recruiter_list .='<p>'.$row->recruiter_name.'</p>';


                            $recruiter_list .= '<select name="recruiter_list_'.$row->recReqID.'" id="recruiter_list_'.$row->recReqID.'" class="choices multiple-remove form-control" multiple>';
                            $recruiter_list .='<option value="">Select Recruiter*</option>';
                            
                            foreach ($get_recruiter_result as $key => $grr) {
                                $recruiter_list .='<option value="'.$grr->empID.'">'.$grr->name.'</option>';
                            }
                            $recruiter_list .='</select><br>';

                            $recruiter_list .='<input type="hidden" disabled name="hepl_recruitment_ref_number_'.$row->recReqID.'" id="hepl_recruitment_ref_number_'.$row->recReqID.'" value="'.$row->hepl_recruitment_ref_number.'" class="form-control" style="margin-top: 5px;margin-bottom: 5px;" placeholder="HEPL Recruitment Refernce Number*">';
                            $recruiter_list .='<input type="hidden" disabled name="hidden_status_'.$row->recReqID.'" id="hidden_status_'.$row->recReqID.'" value="'.$row->assigned_status.'" class="form-control" style="margin-top: 5px;margin-bottom: 5px;" placeholder="assigned_status">';
                            
                           
                            
                        }
                        else{
                            $btn .= '<button class="btn btn-sm btn-primary" id="btnAssign" onclick="process_assign('."'".$row->recReqID."'".');">Assign</button>';
                            
                            $recruiter_list = '<select name="recruiter_list_'.$row->recReqID.'" id="recruiter_list_'.$row->recReqID.'" class="choices multiple-remove form-control" multiple>';
                            $recruiter_list .='<option value="">Select Recruiter*</option>';
                            
                            foreach ($get_recruiter_result as $key => $grr) {
                                $recruiter_list .='<option value="'.$grr->empID.'">'.$grr->name.'</option>';
                            }
                            $recruiter_list .='</select>';

                            $recruiter_list .='<input type="hidden" name="hepl_recruitment_ref_number_'.$row->recReqID.'" id="hepl_recruitment_ref_number_'.$row->recReqID.'" value="'.$row->hepl_recruitment_ref_number.'" class="form-control" style="margin-top: -18px;margin-bottom: 5px;" placeholder="HEPL Recruitment Refernce Number*">';
                            $recruiter_list .='<input type="hidden" disabled name="hidden_status_'.$row->recReqID.'" id="hidden_status_'.$row->recReqID.'" value="'.$row->assigned_status.'" class="form-control" style="margin-top: 5px;margin-bottom: 5px;" placeholder="assigned_status">';
                            
                        }

                            return $recruiter_list."".$btn;
                            // return $recruiter_list;
                    })
                    
                    ->rawColumns(['ageing','open_date','action','assigned_status'])
                    ->make(true);
        }
        return view('view_recruit_request');
    }

    
    public function reqcruitment_request_process(Request $req){

        $form_credentials = array(
            'rfh_no' => $req->input( 'rfh_no' ),
            'position_title' => $req->input( 'position_title' ),
            'no_of_position' => $req->input( 'no_of_position' ),
            'band' => $req->input( 'band' ),
            'open_date' => $req->input( 'open_date' ),
            'critical_position' => $req->input( 'critical_position' ),
            'business' => $req->input( 'business' ),
            'division' => $req->input( 'division' ),
            'function' => $req->input( 'function' ),
            'location' => $req->input( 'location' ),
            'billing_status' => $req->input( 'billing_status' ),
            'interviewer' => $req->input( 'interviewer' ),
            'salary_range' => $req->input( 'salary_range' ),
            'close_date' => date('Y-m-d'),
            'request_status' => 'Open',
            'assigned_status' => 'Pending',
            'assigned_to' =>'',
            'assigned_date'=>'',
            'hepl_recruitment_ref_number' => '',
            'created_by'=> auth()->user()->empID,
            'modified_by'=>auth()->user()->empID,
        ); 

        for ($i=0; $i < $req->input( 'no_of_position' ); $i++) { 
            $insert_reqcruitment_request_result = $this->corepo->reqcruitment_requestEntry( $form_credentials );

        }

        if($insert_reqcruitment_request_result){
            return response()->json( ['response' => "success"] );
        }else{
            return response()->json( ['response' => "failed"] );
        }
    }

    public function process_recruitment_assign(Request $request){
        
        
         $recruiter_list_arr = $request->input('recruiter_list');

         $get_count = count($recruiter_list_arr);
        if($get_count == 1){
            $input_details = array(
                'recReqID'=>$request->input('rowID'),
                'assigned_to'=>$request->input('recruiter_list')[0],
                'assigned_date'=>date('Y-m-d'),
                'hepl_recruitment_ref_number'=>$request->input('hepl_recruitment_ref_number'),
                'assigned_status'=>"Assigned"
            );
            $edit_recruitreq_result = $this->corepo->process_recruitment_assign( $input_details );
        }else{

            $ssno = 1;
            foreach ($recruiter_list_arr as $key => $recruiter_list_individual) {

                // get row values for duplicate recruitment
                $input_details_c = array(
                    'recReqID'=>$request->input('rowID'),
                );
                $get_recruitreq_dup_result = $this->corepo->get_recruitment_for_duplicate( $input_details_c );
                
                if($ssno ==1){
                    $input_details = array(
                        'recReqID'=>$request->input('rowID'),
                        'assigned_to'=>$recruiter_list_individual,
                        'assigned_date'=>date('Y-m-d'),
                        'hepl_recruitment_ref_number'=>$request->input('hepl_recruitment_ref_number'),
                        'assigned_status'=>"Assigned"
                    );
                    $edit_recruitreq_result = $this->corepo->process_recruitment_assign( $input_details );
                }else{
                    if(count($get_recruitreq_dup_result) != 0){

                        $form_credentials = array(
                            'rfh_no' => $get_recruitreq_dup_result[0]->rfh_no,
                            'position_title' => $get_recruitreq_dup_result[0]->position_title,
                            'no_of_position' => $get_recruitreq_dup_result[0]->no_of_position,
                            'band' => $get_recruitreq_dup_result[0]->band,
                            'open_date' =>  $get_recruitreq_dup_result[0]->open_date,
                            'critical_position' => $get_recruitreq_dup_result[0]->critical_position,
                            'business' => $get_recruitreq_dup_result[0]->business,
                            'division' => $get_recruitreq_dup_result[0]->division,
                            'function' => $get_recruitreq_dup_result[0]->function,
                            'location' => $get_recruitreq_dup_result[0]->location,
                            'billing_status' => $get_recruitreq_dup_result[0]->billing_status,
                            'interviewer' => $get_recruitreq_dup_result[0]->interviewer,
                            'salary_range' => $get_recruitreq_dup_result[0]->salary_range,
                            'close_date' => $get_recruitreq_dup_result[0]->close_date,
                            'request_status' => $get_recruitreq_dup_result[0]->request_status,
                            'assigned_status' => "Assigned",
                            'assigned_to' =>$recruiter_list_individual,
                            'assigned_date'=>date('Y-m-d'),
                            'hepl_recruitment_ref_number' => $request->input('hepl_recruitment_ref_number'),
                            'created_by'=> auth()->user()->empID,
                            'modified_by'=>auth()->user()->empID,
                        ); 
    
                       
                        $insert_reqcruitment_request_result = $this->corepo->reqcruitment_requestEntry( $form_credentials );
    
                    }
                }

                

                $ssno++;
            }
            
        }
        

        $response = 'Updated';
        return response()->json( ['response' => $response] );

    }
    

    public function process_recruitment_assigned_assign(Request $request){
        
        
        $recruiter_list_arr = $request->input('recruiter_list');

        $get_count = count($recruiter_list_arr);
       

           $ssno = 1;
           foreach ($recruiter_list_arr as $key => $recruiter_list_individual) {

               // get row values for duplicate recruitment
               $input_details_c = array(
                   'recReqID'=>$request->input('rowID'),
               );
               $get_recruitreq_dup_result = $this->corepo->get_recruitment_for_duplicate( $input_details_c );
               

                   if(count($get_recruitreq_dup_result) != 0){

                       $form_credentials = array(
                           'rfh_no' => $get_recruitreq_dup_result[0]->rfh_no,
                           'position_title' => $get_recruitreq_dup_result[0]->position_title,
                           'no_of_position' => $get_recruitreq_dup_result[0]->no_of_position,
                           'band' => $get_recruitreq_dup_result[0]->band,
                           'open_date' =>  $get_recruitreq_dup_result[0]->open_date,
                           'critical_position' => $get_recruitreq_dup_result[0]->critical_position,
                           'business' => $get_recruitreq_dup_result[0]->business,
                           'division' => $get_recruitreq_dup_result[0]->division,
                           'function' => $get_recruitreq_dup_result[0]->function,
                           'location' => $get_recruitreq_dup_result[0]->location,
                           'billing_status' => $get_recruitreq_dup_result[0]->billing_status,
                           'interviewer' => $get_recruitreq_dup_result[0]->interviewer,
                           'salary_range' => $get_recruitreq_dup_result[0]->salary_range,
                           'close_date' => $get_recruitreq_dup_result[0]->close_date,
                           'request_status' => $get_recruitreq_dup_result[0]->request_status,
                           'assigned_status' => "Assigned",
                           'assigned_to' =>$recruiter_list_individual,
                           'assigned_date'=>date('Y-m-d'),
                           'hepl_recruitment_ref_number' => $request->input('hepl_recruitment_ref_number'),
                           'created_by'=> auth()->user()->empID,
                           'modified_by'=>auth()->user()->empID,
                       ); 
   
                      
                       $insert_reqcruitment_request_result = $this->corepo->reqcruitment_requestEntry( $form_credentials );

                       //    send mail
                        $empID=$recruiter_list_individual;
                        $user_row = $this->corepo->get_user_row( $empID );
                        $to_email=$user_row[0]->email;
                        $body_content1 = "Dear ".$user_row[0]->name;
                        $body_content2 = "Your Got New Allocation";
                        $body_content3 = "Position Title : ".$form_credentials['position_title']."<br>No of Position : ".$form_credentials['no_of_position']."<br>Assigned From : ".auth()->user()->name."";
                        $body_content4 = "Have any queries please contact our support Team."; 
                        // $body_content5 = "Support Number : 9087428914"; 
                        $body_content5 = ""; 
                        $details = [
                            'subject' => 'Assigned To',
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
   
                   }
               
               $ssno++;
           }
           
       

       $response = 'Updated';
       return response()->json( ['response' => $response] );

    }

    public function get_last_hepl_reference_no(){

        
        $last_heplno_result = $this->corepo->get_last_hepl_reference_no( );

        if(count($last_heplno_result) != 0){ // or if(count($rows) === 0)

            $response = $last_heplno_result[0]->hepl_recruitment_ref_number;
            return response()->json( ['response' => $response] );

        }
        else{
            $response = "no_data";
            return response()->json( ['response' => $response] );
        }
    }

    public function getlast_rfhno(){

        
        $last_rfhno_result = $this->corepo->getlast_rfhno( );

        if(count($last_rfhno_result) != 0){ // or if(count($rows) === 0)

            $response = $last_rfhno_result[0]->res_id;
            return response()->json( ['response' => $response] );

        }
        else{
            $response = "no_data";
            return response()->json( ['response' => $response] );
        }
    }

    public function get_candidate_profile(Request $request){
        
        if ($request->ajax()) {
           
            // get all data
            $session_user_details = auth()->user();
            $created_by = $session_user_details->empID;
            $get_role_type = $session_user_details->role_type;

            $af_from_date = (!empty($_POST["af_from_date"])) ? ($_POST["af_from_date"]) : ('');
            $af_to_date = (!empty($_POST["af_to_date"])) ? ($_POST["af_to_date"]) : ('');
            $af_position_title = (!empty($_POST["af_position_title"])) ? ($_POST["af_position_title"]) : ('');
            $af_position_status = (!empty($_POST["af_position_status"])) ? ($_POST["af_position_status"]) : ('');
            $af_created_by = (!empty($_POST["af_created_by"])) ? ($_POST["af_created_by"]) : ('');
            
            if( $af_from_date || $af_to_date  || $af_position_title || $af_position_status || $af_created_by) 
            {
                // get all data
                $advanced_filter = array(
                    'af_from_date'=>$af_from_date,
                    'af_to_date'=>$af_to_date,
                    'af_position_title'=>$af_position_title,
                    'af_position_status'=>$af_position_status,
                    'af_created_by'=>$af_created_by,
                    'status'=>"Candidate Onboarded",

                );
                $get_candidate_profile_result = $this->corepo->get_candidate_profile_all_af( $advanced_filter );

            }
            else{
                
                $input_details = array(
                    'status'=>"Candidate Onboarded",
                );
                $get_candidate_profile_result = $this->corepo->get_candidate_profile_all( $input_details );
    
            }
            
            return Datatables::of($get_candidate_profile_result)
                ->addIndexColumn()
                ->addColumn('candidate_cv', function($row) {
                            
                    $btn = '<a href="cv_upload/'.$row->candidate_cv.'" class="badge bg-info" target="_blank"><i class="bi bi-eye"></i></a>';
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

        return view('candidate_profile');
        

    }

    public function candidate_follow_up_history_bc(Request $req){
        
        $input_details = array(
            'cdID'=>$req->input('cdID'),
        );

        $cfu_history_result = $this->recrepo->candidate_follow_up_history( $input_details );

        return $cfu_history_result;
    }

    public function get_offer_released_report_bc(Request $req){
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

    public function process_ticket_edit(Request $req){
        $input_details = array(
            'rfh_no'=>$req->input('ticket_rfh_no'),
            'request_status'=>$req->input('ticket_status'),
        );

        $process_ticket_edit_result = $this->corepo->process_ticket_edit( $input_details );

        $response = 'Updated';
        return response()->json( ['response' => $response] );

    }

    

    public function process_ticket_delete(Request $req){
        $input_details = array(
            'res_id'=>$req->input('rfh_no'),
        );

        $process_ticket_delete_result = $this->corepo->process_ticket_delete( $input_details );

        $response = 'success';
        return response()->json( ['response' => $response] );

    }

    public function process_recruiter_delete(Request $req){
        $input_details = array(
            'empID'=>$req->input('empID'),
        );

        $process_recruiter_delete_result = $this->corepo->process_recruiter_delete( $input_details );

        $response = 'success';
        return response()->json( ['response' => $response] );

    }
    
    public function ticket_report(Request $request){
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
            $af_billable = (!empty($_POST["af_billable"])) ? ($_POST["af_billable"]) : ('');


            if( $af_from_date || $af_to_date  || $af_position_title || 
                $af_critical_position || $af_position_status || $af_assigned_status || 
                $af_salary_range || $af_band || $af_location || $af_business || 
                $af_billing_status || $af_billing_status || $af_billable ) 
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
                    'af_billable'=>$af_billable,
                );

                $get_reqcruitment_request_result = $this->corepo->get_reqcruitment_request_afilter( $advanced_filter );

            }
            else{
                // get all data
                $get_reqcruitment_request_result = $this->corepo->get_reqcruitment_request_default_report(  );

            }
            
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
                    

                    ->addColumn('assigned_status', function($row) {
                        
                        if($row->assigned_status =='Assigned'){
                            $btn = '<span class="badge bg-secondary" title="'.$row->assigned_status.'"><i class="bi bi-shield-check"></i></span>';
                        }else{
                            $btn = '<span class="badge bg-danger" title="'.$row->assigned_status.'"><i class="bi bi-shield-slash"></i></span>';
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
                    ->addColumn('action', function($row) {
                        $btn = '<a href="ticket_candidate_details?hr_refno='.$row->hepl_recruitment_ref_number.'"><span class="badge bg-primary" id="btnAssign" title="Candidate Details"><i class="bi bi-people-fill"></i></span></a>';
                        
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
                    ->addColumn('recruiters', function($row) {
                        $get_recruiters = $this->corepo->get_assigned_recruiters( $row->hepl_recruitment_ref_number );
                        
                        $recruiters_name ='';
                        if(count($get_recruiters) !=0){
                            foreach ($get_recruiters as $key => $gr_value) {
                                if($gr_value->name !=''){
                                    $recruiters_name .= '<p style="margin-bottom: 0.2rem;"><i class="bi bi-caret-right-fill"></i>'.$gr_value->name.'</p>';
                                }
                            }
                        }

                        return $recruiters_name;
                    })
                    ->addColumn('interviewer', function($row){
                        $get_interviewer = $row->interviewer;
                        
                        if($get_interviewer =='Self' || $get_interviewer =='SELF' || $get_interviewer =='self'){
                            $get_interviewer_self = $this->corepo->get_interviewer_self( $row->rfh_no );

                            $get_interviewer = $get_interviewer_self[0]->name;
                        }
                        return $get_interviewer;

                    })

                    ->addColumn('cv_count', function($row) {
                        
                        $hepl_recruitment_ref_number = $row->hepl_recruitment_ref_number;
                        $cv_count = $this->corepo->get_cv_count( $hepl_recruitment_ref_number );

                        return $cv_count;
                    })

                    ->rawColumns(['cv_count','ageing','open_date','action','assigned_status','tat_process','recruiters','interviewer'])
                    ->make(true);
        }
        return view('ticket_report');
    }

    public function ticket_candidate_details(Request $request){

        if ($request->ajax()) {
            
            $af_from_date = (!empty($_POST["af_from_date"])) ? ($_POST["af_from_date"]) : ('');
            $af_to_date = (!empty($_POST["af_to_date"])) ? ($_POST["af_to_date"]) : ('');
            $af_position_status = (!empty($_POST["af_position_status"])) ? ($_POST["af_position_status"]) : ('');
            $af_created_by = (!empty($_POST["af_created_by"])) ? ($_POST["af_created_by"]) : ('');
            
            if( $af_from_date || $af_to_date || $af_position_status || $af_created_by ){

                $input_details = array(
                    'af_from_date'=>$af_from_date,
                    'af_to_date'=>$af_to_date,
                    'af_position_status'=>$af_position_status,
                    'af_created_by'=>$af_created_by,
                    'hepl_recruitment_ref_number'=>$request->input('hr_refno'),
                );
    
                // get all data
                $get_reqcruitment_request_result = $this->corepo->get_ticket_candidate_details_af( $input_details );
    
            }
            else{
                $input_details = array(
                    'hepl_recruitment_ref_number'=>$request->input('hr_refno'),
                );
    
                // get all data
                $get_reqcruitment_request_result = $this->corepo->get_ticket_candidate_details( $input_details );
    
            }
            
            return Datatables::of($get_reqcruitment_request_result)
            ->addIndexColumn()
            ->addColumn('candidate_cv', function($row) {
                        
                $btn = '<a href="cv_upload/'.$row->candidate_cv.'" class="badge bg-info" target="_blank"><i class="bi bi-eye"></i></a>';
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
                $newDate = date("d-m-Y", strtotime($originalDate));

                return $newDate;
            })

            
            ->rawColumns(['candidate_cv','history','status','status_cont','created_on'])
            ->make(true);
        }
        return view('ticket_candidate_details');
    }

    public function get_recruiter_list_af(){

        $get_recruiter_list_af_result = $this->corepo->get_recruiter_list_af(  );

        return $get_recruiter_list_af_result;
    }

    public function edit_recruit_request(){
        return view('edit_recruit_request');

    }
    public function edit_recruit_request_new(){
        return view('edit_recruit_request_new');

    }

    public function get_recruitment_edit_details(Request $req){

        $input_details = array(
            'rfh_no'=>$req->input('rfh_no'),
        );

        $get_recruitment_edit_details_result = $this->corepo->get_recruitment_edit_details( $input_details );

        return $get_recruitment_edit_details_result;

    }

    public function get_recruitment_edit_details_new(Request $req){

        $input_details = array(
            'rfh_no'=>$req->input('rfh_no'),
        );

        // $get_recruitment_edit_details_new_result = $this->corepo->get_recruitment_edit_details( $input_details );
        
        $get_tblrfh_result = $this->corepo->get_tblrfh_details( $input_details );

        return response()->json( [
            // 'recruitment_requests' => $get_recruitment_edit_details_new_result,
            'tbl_rfh' => $get_tblrfh_result
        ] );

    }
    
    public function reqcruitment_request_editprocess_new(Request $req){

        $division1 = $req->input('division1', TRUE );
		$division2 = $req->input('division2', TRUE);
		$division3 = $req->input('division3', TRUE);
		$division4 = $req->input('division4', TRUE);

		if($division1 != "") {
			$division = $division1;
		}
		elseif($division2 != "") {
			$division = $division2;
		}
		elseif($division3 != "") {
			$division = $division3;
		}
		elseif($division4 != "") {
			$division = $division4;
		}
		else {
            $division="";
        }

        $expBand = explode('_',$req->input('band', TRUE));
		$bandID = $expBand[0];

        
        $form_credentials = array(
            'res_id' => $req->input( 'res_id' ),
            'rolls_option' => $req->input( 'rolls_option' ),
            'name' => $req->input( 'name' ),
            'mobile' => $req->input( 'mobile' ),
            'email' => $req->input( 'email' ),
            'position_reports' => $req->input( 'position_reports' ),
            'approved_by' => $req->input( 'approved_by' ),
            'ticket_number' => $req->input( 'ticket_number' ),
            'position_title' => $req->input( 'position_title' ),
            'location' => $req->input( 'location' ),
            'location_preferred' => $req->input( 'location_preferred' ),
            'business' => $req->input( 'business' ),
            'band' => $bandID,
            'division' => $division,
            'function' => $req->input( 'function' ),
            'no_of_positions' => $req->input( 'no_of_positions' ),
            'jd_roles' => $req->input( 'jd_roles' ),
            'qualification' => $req->input( 'qualification' ),
            'essential_skill' => $req->input( 'essential_skill' ),
            'good_skill' => $req->input( 'good_skill' ),
            'experience' => $req->input( 'experience' ),
            'salary_range' => $req->input( 'salary_range' ),
            'any_specific' => $req->input( 'any_specific' ),
        ); 
        $reqcruitment_request_editprocess_result = $this->corepo->reqcruitment_request_editprocess_new( $form_credentials );
        
        if($req->hasfile('approval_hire')){
            $cd_id = 'RFHAH'.( ( DB::table( 'tbl_rfh' )->max( 'id' ) )+1 );
            $ah_name = $cd_id.time().'.'.$req->file('approval_hire')->extension();
            $req->file('approval_hire')->move(public_path().'/uploads/', $ah_name);  
            
            $update_mdlrecruitreqtbl = DB::table('tbl_rfh');
            $update_mdlrecruitreqtbl = $update_mdlrecruitreqtbl->where( 'res_id', '=', $req->input( 'res_id' ) );
    
            $update_mdlrecruitreqtbl->update( [ 
                'approval_hire' => $ah_name,
            ] );
        }

        
        $response = 'Updated';
        return response()->json( ['response' => $response] );
    }

    public function reqcruitment_request_editprocess(Request $req){
        $form_credentials = array(
            'rfh_no' => $req->input( 'rfh_no' ),
            'position_title' => $req->input( 'position_title' ),
            'band' => $req->input( 'band' ),
            'open_date' => $req->input( 'open_date' ),
            'critical_position' => $req->input( 'critical_position' ),
            'business' => $req->input( 'business' ),
            'division' => $req->input( 'division' ),
            'function' => $req->input( 'function' ),
            'location' => $req->input( 'location' ),
            'billing_status' => $req->input( 'billing_status' ),
            'interviewer' => $req->input( 'interviewer' ),
            'salary_range' => $req->input( 'salary_range' ),
        ); 
        $reqcruitment_request_editprocess_result = $this->corepo->reqcruitment_request_editprocess( $form_credentials );
        
        $response = 'Updated';
        return response()->json( ['response' => $response] );
    }

    public function view_recruiter(Request $request){
        if ($request->ajax()) {
            
            $get_recruiter_list_result = $this->corepo->get_recruiter_list(  );

            return Datatables::of($get_recruiter_list_result)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                        
                $btn = '<span class="badge bg-info" title="Reset Password" onclick="reset_password('."'".$row->empID."'".');"><i class="bi bi-key-fill"></i></span>';
                $btn .= ' <span class="badge bg-danger" title="Delete" onclick="recruiter_delete_process('."'".$row->empID."'".');"><i class="bi bi-trash"></i></span>';
                
                return $btn;


            })
           
            ->rawColumns(['action'])
            ->make(true);
        }
        return view('view_recruiter');
    }
    public function add_recruiter(){
        return view('add_recruiter');
    }
    public function add_recruiter_process(Request $req){

        $form_credentials = array(
            'empID' => $req->input( 'empID' ),
            'name' => $req->input( 'emp_name' ),
            'designation' => $req->input( 'designation' ),
            'email' => $req->input( 'email' ),
            'role_type' => "recruiter",
            'profile_status' => "Active",
            'password' => bcrypt("123456")
        ); 
        $add_recruiter_process_result = $this->corepo->add_recruiter_process( $form_credentials );
        
        $response = 'success';
        return response()->json( ['response' => $response] );
    }

    public function reset_password(Request $req){
        $input_details = array(
            'empID'=>$req->input('empID'),
            'confirm_password'=>bcrypt("123456"),
        );

        $change_password_process_result = $this->corepo->change_password_process( $input_details );

        $response = 'Updated';
        return response()->json( ['response' => $response] );
    }

    public function recruiter_report(Request $request){
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
            $af_recruiters = (!empty($_POST["af_recruiters"])) ? ($_POST["af_recruiters"]) : ('');

            $session_user_details = auth()->user();
            $assigned_to = $session_user_details->empID;

            if( $af_from_date || $af_to_date  || $af_position_title || 
                $af_critical_position || $af_position_status || $af_assigned_status || 
                $af_salary_range || $af_band || $af_location || $af_business || 
                $af_billing_status || $af_billing_status || $af_recruiters) 
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
                    'assigned_to'=>$af_recruiters,
                );

                $get_ticket_report_recruiter_result = $this->corepo->get_ticket_report_recruiter_afilter( $advanced_filter );

            }
            else{
                $get_ticket_report_recruiter_result = $this->corepo->get_ticket_report_recruiter(  );

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
                            $btn = '<span class="badge bg-secondary" title="'.$row->assigned_status.'"><i class="bi bi-shield-check"></i></span>';
                        }else{
                            $btn = '<span class="badge bg-danger" title="'.$row->assigned_status.'"><i class="bi bi-shield-slash"></i></span>';
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
                        $btn = '<a href="recruiter_report_cp?hr_refno='.$row->hepl_recruitment_ref_number.'" class="up_href"><span class="badge bg-primary" id="btnAssign" title="Candidate Details"><i class="bi bi-people-fill"></i></span></a>';
                        
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
                    ->addColumn('recruiter_ageing', function($row) {
                        
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

                            $recruiter_ageing_filter = array(
                                'rfh_no'=>$row->rfh_no,
                                'hepl_recruitment_ref_number'=>$row->hepl_recruitment_ref_number,
                                'created_by'=>$row->assigned_to,
                                'check_date'=>$thirdrl_mdate,
                                
                            );

                            $recruiter_ageing_result = $this->corepo->recruiter_ageing_details( $recruiter_ageing_filter );

                            if(count($recruiter_ageing_result) ==0){
                                $new_updated_at = date("Y-m-d", strtotime($orl_mdate));

                                 $from = strtotime($new_updated_at);

                                $today = time();
                                $difference = $today - $from;
                                $difference_days = floor($difference / 86400);  // (60 * 60 * 24)
        
                                 $ageing = $difference_days;
        
                                return $ageing;
                            }
                            else{

                                 $ageing = 0;

                                return $ageing;

                            }


                        }else{

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

                    })

                    ->addColumn('recruiter_ageing_status', function($row) {

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

                            $recruiter_ageing_filter = array(
                                'rfh_no'=>$row->rfh_no,
                                'hepl_recruitment_ref_number'=>$row->hepl_recruitment_ref_number,
                                'created_by'=>$row->assigned_to,
                                'check_date'=>$thirdrl_mdate,
                                
                            );

                            $recruiter_ageing_result = $this->corepo->recruiter_ageing_details( $recruiter_ageing_filter );

                            if(count($recruiter_ageing_result) ==0){
                                $new_updated_at = date("Y-m-d", strtotime($orl_mdate));

                                $from = strtotime($new_updated_at);

                                $today = time();
                                $difference = $today - $from;
                                $difference_days = floor($difference / 86400);  // (60 * 60 * 24)
        
                                $ageing = $difference_days;
        
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

                       
                        
                        $fourthDate= date('Y-m-d', strtotime($row->assigned_date. ' + 4 days'));

                        $thirdDate= date('Y-m-d', strtotime($row->assigned_date. ' + 3 days'));
                        
                        if($ageing >= 3){
                            // get all data
                            $recruiter_ageing_filter = array(
                                'rfh_no'=>$row->rfh_no,
                                'hepl_recruitment_ref_number'=>$row->hepl_recruitment_ref_number,
                                'created_by'=>$row->assigned_to,
                                'check_date'=>$thirdDate,
                                
                            );

                            $recruiter_ageing_result = $this->corepo->recruiter_ageing_details( $recruiter_ageing_filter );

                            if(count($recruiter_ageing_result) ==0){

                                if($currentDate >=$fourthDate){
                                    // get all data
                                    $recruiter_ageing_filter_in = array(
                                        'rfh_no'=>$row->rfh_no,
                                        'hepl_recruitment_ref_number'=>$row->hepl_recruitment_ref_number,
                                        'created_by'=>$row->assigned_to,
                                        'check_date'=>$fourthDate,
                                        
                                    );
                                   
                                    $recruiter_ageing_result_in = $this->corepo->recruiter_ageing_details( $recruiter_ageing_filter_in );
                                    if(count($recruiter_ageing_result_in) ==0){
                                        return "four_show_recruiter_ageing_highlight";
                                    }
                                    
                                }
                                else{
                                    return "three_show_recruiter_ageing_highlight";

                                }

                                
                            }else{
                                return "hide_recruiter_ageing_highlight";

                            }
                        }
                        
                        else{
                            return "";
                        }
                        
                        
                    })

                    ->addColumn('interviewer', function($row){
                        $get_interviewer = $row->interviewer;
                        
                        if($get_interviewer =='Self' || $get_interviewer =='SELF' || $get_interviewer =='self'){
                            $get_interviewer_self = $this->corepo->get_interviewer_self( $row->rfh_no );

                            $get_interviewer = $get_interviewer_self[0]->name;
                        }
                        return $get_interviewer;

                    })
                    ->addColumn('cv_count', function($row) {
                        
                        $hepl_recruitment_ref_number = $row->hepl_recruitment_ref_number;
                        $cv_count = $this->corepo->get_cv_count( $hepl_recruitment_ref_number );

                        return $cv_count;
                    })
                    ->rawColumns(['cv_count','ageing','open_date','action','assigned_status','tat_process','recruiter_ageing','recruiter_ageing_status','interviewer'])
                    ->make(true);
        }

        return view('recruiter_report');
    }


    public function recruiter_report_cp(Request $request){

        if ($request->ajax()) {
           
            // get all data
            $session_user_details = auth()->user();
            $created_by = $_POST["rec_id"];

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

        return view('recruiter_report_cp');

    }

    
}
