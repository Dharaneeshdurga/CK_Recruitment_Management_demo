<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ICoordinatorRepository;
use App\Repositories\IRecruiterRepository;
use App\Repositories\IPayrollRepository;
use Illuminate\Support\Str;

use DataTables;
use DateTime;
use DB;
use Mail;

class BEcoordinatorController extends Controller
{
    public function __construct(IPayrollRepository $payrepo,ICoordinatorRepository $corepo,IRecruiterRepository $recrepo)
    {
        $this->corepo = $corepo;
        $this->recrepo = $recrepo;
        $this->payrepo = $payrepo;

        $this->middleware('backend_coordinator');
    }

    public function add_recruit_request()
    {
        return view('add_recruit_request');
    }

    public function view_recruit_request_default(Request $request){


        if ($request->ajax()) {

            $af_from_date = (!empty($_POST["af_from_date"])) ? ($_POST["af_from_date"]) : ('');
            $af_to_date = (!empty($_POST["af_to_date"])) ? ($_POST["af_to_date"]) : ('');
            $af_position_title = (!empty($_POST["af_position_title"])) ? ($_POST["af_position_title"]) : ('');
            $af_sub_position_title = (!empty($_POST["af_sub_position_title"])) ? ($_POST["af_sub_position_title"]) : ('');
            $af_critical_position = (!empty($_POST["af_critical_position"])) ? ($_POST["af_critical_position"]) : ('');
            $af_position_status = (!empty($_POST["af_position_status"])) ? ($_POST["af_position_status"]) : ('');
            $af_assigned_status = (!empty($_POST["af_assigned_status"])) ? ($_POST["af_assigned_status"]) : ('');
            $af_band = (!empty($_POST["af_band"])) ? ($_POST["af_band"]) : ('');
            $af_location = (!empty($_POST["af_location"])) ? ($_POST["af_location"]) : ('');
            $af_business = (!empty($_POST["af_business"])) ? ($_POST["af_business"]) : ('');
            $af_function = (!empty($_POST["af_function"])) ? ($_POST["af_function"]) : ('');
            $af_division = (!empty($_POST["af_division"])) ? ($_POST["af_division"]) : ('');
            $af_billable = (!empty($_POST["af_billable"])) ? ($_POST["af_billable"]) : ('');
            $af_raisedby = (!empty($_POST["af_raisedby"])) ? ($_POST["af_raisedby"]) : ('');
            $af_approvedby = (!empty($_POST["af_approvedby"])) ? ($_POST["af_approvedby"]) : ('');


            $advanced_filter = array(
                'af_assigned_status'=>'Pending',
                'af_from_date'=>$af_from_date,
                'af_to_date'=>$af_to_date,
                'af_position_title'=>$af_position_title,
                'af_sub_position_title'=>$af_sub_position_title,
                'af_critical_position'=>$af_critical_position,
                'af_position_status'=>$af_position_status,
                'af_band'=>$af_band,
                'af_location'=>$af_location,
                'af_business'=>$af_business,
                'af_function'=>$af_function,
                'af_division'=>$af_division,
                'af_billable'=>$af_billable,
                'af_raisedby'=>$af_raisedby,
                'af_approvedby'=>$af_approvedby,
            );

            $get_reqcruitment_request_result = $this->corepo->get_reqcruitment_request_default( $advanced_filter );

            // get recruiter list
            // $get_recruiter_result = $this->corepo->get_recruiter_list( );

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
                    ->addColumn('closed_date', function($row) {
                        if($row->request_status =='Closed'){

                            $originalDate_cd = $row->close_date;
                            $closed_date = date("d-m-Y", strtotime($originalDate_cd));

                            return $closed_date;
                        }else{
                            $closed_date = '-';
                            return $closed_date;
                        }
                    })
                    ->addColumn('assigned_status', function($row) {

                        if($row->assigned_status =='Assigned'){
                            $btn = '<span class="badge bg-secondary" title="'.$row->assigned_status.'"><i class="bi bi-shield-check"></i></span>';
                        }else{
                            $btn = '<span class="badge bg-danger" title="'.$row->assigned_status.'"><i class="bi bi-shield-slash"></i></span>';
                        }

                        $credentials = array(
                            'request_status'=>'Closed',
                            'rfh_no'=>$row->rfh_no
                        );

                        $get_closed_count = $this->corepo->get_position_closed_count( $credentials );

                        $closed_count = count($get_closed_count);
                        if($closed_count < $row->no_of_position){
                            $btn1 = '<span class="badge bg-warning" title="Open"><i class="fa fa-book-open"></i></span>';
                        }
                        elseif ($closed_count == $row->no_of_position) {
                            $btn1 = '<span class="badge bg-success" title="Closed"><i class="fa fa-book"></i></span>';
                        }
                        else{
                            $btn1 ='';
                        }

                        // if($row->request_status =='Open'){
                        //     $btn1 = '<span class="badge bg-warning" title="Open"><i class="fa fa-book-open"></i></span>';
                        // }
                        // else if($row->request_status =='Closed'){
                        //     $btn1 = '<span class="badge bg-success" title="Closed"><i class="fa fa-book"></i></span>';
                        // }
                        // else if($row->request_status =='On Hold'){
                        //     $btn1 = '<span class="badge bg-onhold" title="On Hold"><i class="bi bi-pause-fill"></i></span>';
                        // }
                        // else if($row->request_status =='Re Open'){
                        //     $btn1 = '<span class="badge bg-dark" title="Re Open"><i class="bi bi-exclude"></i></span>';
                        // }

                        $btn2 = '<span class="badge bg-info" title="Edit"  onclick="ticket_edit_process('."'".$row->rfh_no."'".');"><i class="bi bi-pencil-square"></i></span>';
                        return $btn." ".$btn1." ".$btn2;
                    })
                    ->addColumn('action', function($row) {
                        $btn = '<a href="view_recruit_request?rfh_no='.$row->rfh_no.'" target="_blank"><span class="badge bg-primary" id="btnAssign" title="Assign"><i class="bi bi-person-lines-fill"></i></span></a>';

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


                        $ageing = $difference_days;
                        $ageing_end = 15;

                        // if($row->critical_position =='Yes'){
                        //     $ageing_end = 15;

                        // }else if($row->critical_position =='Nill'){
                        //     $ageing_end = 15;

                        // }
                        // else{
                        //    $ageing_end = $row->tat_days;

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
                            $get_interviewer_self = $this->corepo->get_interviewer_self( $row->rfh_no );
                            // print_r($get_interviewer_self);
                            $get_interviewer = $get_interviewer_self[0]->name;
                        }
                        return $get_interviewer;

                    })
                    ->addColumn('edit_btn', function($row){
                        $edit_btn = '<a href="edit_recruit_request_new?rfh_no='.$row->rfh_no.'" target="_blank"><button class="btn btn-sm btn-secondary" id="btnAssign" title="Edit Recruitment"><i class="bi bi-pen-fill"></i></button></a>';
                        return $edit_btn;
                    })
                    ->addColumn('as_title', function($row) {

                        $as_title = $row->assigned_status;

                        return $as_title;
                    })
                    ->addColumn('ps_title', function($row) {

                        $ps_title = $row->request_status;

                        return $ps_title;
                    })
                    ->addColumn('no_of_position', function($row){

                        $no_of_position = $row->no_of_position;

                        $position_edit = '<button type="button" onclick=pop_noofposition('."'".$row->no_of_position."'".','."'".$row->rfh_no."'".'); class="btn btn-sm btn-dark"><i class="fa fa-edit"></i></button>';
                        return $no_of_position." ".$position_edit;

                    })
                    ->addColumn('approval_for_hire', function($row){

                        $rfh_no = $row->rfh_no;
                        $get_approval_for_hire = $this->corepo->get_approval_for_hire( $rfh_no );
                        if(count($get_approval_for_hire) !=0){
                            if($get_approval_for_hire[0]->approval_hire !=''){

                                if($get_approval_for_hire[0]->approval_hire_path == '0'){
                                    $file_url = "http://hub1.cavinkare.in/CK_RFH/uploads/".$get_approval_for_hire[0]->approval_hire;
                                    $approval_hire = '<a href="'.$file_url.'" target="_blank"><button type="button" class="btn btn-secondary btn-sm"><i class="bi bi-download"></i></button></a>';
                                }elseif($get_approval_for_hire[0]->approval_hire_path == '1'){
                                    $file_url = "../recruit_request_files/".$get_approval_for_hire[0]->approval_hire;
                                    $approval_hire = '<a href="'.$file_url.'" target="_blank"><button type="button" class="btn btn-secondary btn-sm"><i class="bi bi-download"></i></button></a>';
                                }
                            }elseif ($get_approval_for_hire[0]->ticket_number !='' || $get_approval_for_hire[0]->ticket_number !=Null) {
                                $approval_hire = $get_approval_for_hire[0]->ticket_number;

                            }else{
                                $approval_hire = '';

                            }
                        }else{
                            $approval_hire = '';
                        }
                        return $approval_hire;

                    })
                    ->rawColumns(['approval_for_hire','no_of_position','as_title','ps_title','edit_btn','ageing','open_date','closed_date','action','assigned_status','tat_process','interviewer'])
                    ->make(true);
        }
        return view('view_recruit_request_default');
    }
    public function view_recruit_request_default_ag(Request $request){
        if ($request->ajax()) {

            $af_from_date = (!empty($_POST["af_from_date"])) ? ($_POST["af_from_date"]) : ('');
            $af_to_date = (!empty($_POST["af_to_date"])) ? ($_POST["af_to_date"]) : ('');
            $af_position_title = (!empty($_POST["af_position_title"])) ? ($_POST["af_position_title"]) : ('');
            $af_sub_position_title = (!empty($_POST["af_sub_position_title"])) ? ($_POST["af_sub_position_title"]) : ('');
            $af_critical_position = (!empty($_POST["af_critical_position"])) ? ($_POST["af_critical_position"]) : ('');
            $af_position_status = (!empty($_POST["af_position_status"])) ? ($_POST["af_position_status"]) : ('');
            $af_assigned_status = (!empty($_POST["af_assigned_status"])) ? ($_POST["af_assigned_status"]) : ('');
            $af_band = (!empty($_POST["af_band"])) ? ($_POST["af_band"]) : ('');
            $af_location = (!empty($_POST["af_location"])) ? ($_POST["af_location"]) : ('');
            $af_business = (!empty($_POST["af_business"])) ? ($_POST["af_business"]) : ('');
            $af_function = (!empty($_POST["af_function"])) ? ($_POST["af_function"]) : ('');
            $af_division = (!empty($_POST["af_division"])) ? ($_POST["af_division"]) : ('');
            $af_billable = (!empty($_POST["af_billable"])) ? ($_POST["af_billable"]) : ('');
            $af_raisedby = (!empty($_POST["af_raisedby"])) ? ($_POST["af_raisedby"]) : ('');
            $af_approvedby = (!empty($_POST["af_approvedby"])) ? ($_POST["af_approvedby"]) : ('');


            $advanced_filter = array(
                'af_assigned_status'=>'Assigned',
                'af_from_date'=>$af_from_date,
                'af_to_date'=>$af_to_date,
                'af_position_title'=>$af_position_title,
                'af_sub_position_title'=>$af_sub_position_title,
                'af_critical_position'=>$af_critical_position,
                'af_position_status'=>$af_position_status,
                'af_band'=>$af_band,
                'af_location'=>$af_location,
                'af_business'=>$af_business,
                'af_function'=>$af_function,
                'af_division'=>$af_division,
                'af_billable'=>$af_billable,
                'af_raisedby'=>$af_raisedby,
                'af_approvedby'=>$af_approvedby,
            );
            // get all data
            // $where_cond = 'Assigned';
            $get_reqcruitment_request_result = $this->corepo->get_reqcruitment_request_default( $advanced_filter  );

            // get recruiter list
            // $get_recruiter_result = $this->corepo->get_recruiter_list( );

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

                        $ageing_nw = $difference_days;
                        return $ageing_nw;
                    })
                    ->addColumn('open_date', function($row) {

                        $originalDate = $row->open_date;
                        $newDate = date("d-m-Y", strtotime($originalDate));

                        return $newDate;
                    })
                    ->addColumn('closed_date', function($row) {
                        if($row->request_status =='Closed'){

                            $originalDate_cd = $row->close_date;
                            $closed_date = date("d-m-Y", strtotime($originalDate_cd));

                            return $closed_date;
                        }else{
                            $closed_date = '-';
                            return $closed_date;
                        }
                    })
                    ->addColumn('assigned_status', function($row) {

                        if($row->assigned_status =='Assigned'){
                            $btn = '<span class="badge bg-secondary" title="'.$row->assigned_status.'"><i class="bi bi-shield-check"></i></span>';
                        }else{
                            $btn = '<span class="badge bg-danger" title="'.$row->assigned_status.'"><i class="bi bi-shield-slash"></i></span>';
                        }

                        $credentials = array(
                            'request_status'=>'Closed',
                            'rfh_no'=>$row->rfh_no
                        );

                        $get_closed_count = $this->corepo->get_position_closed_count( $credentials );

                        $closed_count = count($get_closed_count);

                        if($row->request_status =='On Hold'){
                            $btn1 = '<span class="badge bg-onhold" title="On Hold"><i class="bi bi-pause-fill"></i></span>';

                        }else{
                            if($closed_count < $row->no_of_position){
                                $btn1 = '<span class="badge bg-warning" title="Open"><i class="fa fa-book-open"></i></span>';
                            }
                            elseif ($closed_count == $row->no_of_position) {
                                $btn1 = '<span class="badge bg-success" title="Closed"><i class="fa fa-book"></i></span>';
                            }
                            else{
                                $btn1 ='';
                            }
                        }

                        // if($row->request_status =='Open'){
                        //     $btn1 = '<span class="badge bg-warning" title="Open"><i class="fa fa-book-open"></i></span>';
                        // }
                        // else if($row->request_status =='Closed'){
                        //     $btn1 = '<span class="badge bg-success" title="Closed"><i class="fa fa-book"></i></span>';
                        // }
                        // else if($row->request_status =='On Hold'){
                        //     $btn1 = '<span class="badge bg-onhold" title="On Hold"><i class="bi bi-pause-fill"></i></span>';
                        // }
                        // else if($row->request_status =='Re Open'){
                        //     $btn1 = '<span class="badge bg-dark" title="Re Open"><i class="bi bi-exclude"></i></span>';
                        // }

                        $btn2 = '<span class="badge bg-info" title="Edit"  onclick="ticket_edit_process('."'".$row->rfh_no."'".');"><i class="bi bi-pencil-square"></i></span>';
                        return $btn." ".$btn1." ".$btn2;
                    })
                    ->addColumn('action', function($row) {
                        $btn = '<a href="view_recruit_request?rfh_no='.$row->rfh_no.'" target="_blank"><span class="badge bg-primary" id="btnAssign" title="Assign"><i class="bi bi-person-lines-fill"></i></span></a>';

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
                            $get_interviewer_self = $this->corepo->get_interviewer_self( $row->rfh_no );

                            $get_interviewer = $get_interviewer_self[0]->name;
                        }
                        return $get_interviewer;

                    })
                    ->addColumn('edit_btn', function($row){
                        $edit_btn = '<a href="edit_recruit_request_new?rfh_no='.$row->rfh_no.'" target="_blank"><button class="btn btn-sm btn-secondary" id="btnAssign" title="Edit Recruitment"><i class="bi bi-pen-fill"></i></button></a>';
                        return $edit_btn;
                    })
                    ->addColumn('as_title', function($row) {

                        $as_title = $row->assigned_status;

                        return $as_title;
                    })
                    ->addColumn('ps_title', function($row) {

                        $ps_title = $row->request_status;

                        return $ps_title;
                    })
                    ->addColumn('no_of_position', function($row){

                        $no_of_position = $row->no_of_position;

                        $position_edit = '<button type="button" onclick=pop_noofposition('."'".$row->no_of_position."'".','."'".$row->rfh_no."'".'); class="btn btn-sm btn-dark"><i class="fa fa-edit"></i></button>';
                        return $no_of_position." ".$position_edit;

                    })
                    ->addColumn('approval_for_hire', function($row){

                        $rfh_no = $row->rfh_no;
                        $get_approval_for_hire = $this->corepo->get_approval_for_hire( $rfh_no );
                        if(count($get_approval_for_hire) !=0){
                            if($get_approval_for_hire[0]->approval_hire !=''){

                                if($get_approval_for_hire[0]->approval_hire_path == '0'){
                                    $file_url = "http://hub1.cavinkare.in/CK_RFH/uploads/".$get_approval_for_hire[0]->approval_hire;
                                    $approval_hire = '<a href="'.$file_url.'" target="_blank"><button type="button" class="btn btn-secondary btn-sm"><i class="bi bi-download"></i></button></a>';
                                }elseif($get_approval_for_hire[0]->approval_hire_path == '1'){
                                    $file_url = "../recruit_request_files/".$get_approval_for_hire[0]->approval_hire;
                                    $approval_hire = '<a href="'.$file_url.'" target="_blank"><button type="button" class="btn btn-secondary btn-sm"><i class="bi bi-download"></i></button></a>';
                                }
                            }elseif ($get_approval_for_hire[0]->ticket_number !='' || $get_approval_for_hire[0]->ticket_number !=Null) {
                                $approval_hire = $get_approval_for_hire[0]->ticket_number;

                            }else{
                                $approval_hire = '';

                            }
                        }else{
                            $approval_hire = '';
                        }
                        return $approval_hire;

                    })
                    ->rawColumns(['approval_for_hire','no_of_position','edit_btn','as_title','ps_title','ageing','open_date','action','assigned_status','tat_process','interviewer'])
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

                        $ageing = $difference_days;
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
                            $btn .= '<button class="btn btn-sm btn-primary" id="btnAssign_'.$row->recReqID.'" onclick="process_assign('."'".$row->recReqID."'".');">Assign</button>';

                            $recruiter_list ='<h6>Assigned To</h6>';
                            $recruiter_list .='<p>'.$row->recruiter_name.'</p>';


                            $recruiter_list .= '<select name="recruiter_list_'.$row->recReqID.'" size="3" id="recruiter_list_'.$row->recReqID.'" class="choices multiple-remove form-control" multiple>';
                            $recruiter_list .='<option value="">Select Recruiter*</option>';

                            foreach ($get_recruiter_result as $key => $grr) {
                                $recruiter_list .='<option value="'.$grr->empID.'">'.$grr->name.'</option>';
                            }
                            $recruiter_list .='</select>';

                            $recruiter_list .='<input type="text" name="sub_position_title_'.$row->recReqID.'" id="sub_position_title_'.$row->recReqID.'" class="form-control" placeholder="Sub Position Title" style="margin-bottom: 6px;">';

                            $recruiter_list .='<input type="hidden" disabled name="main_position_title_'.$row->recReqID.'" id="main_position_title_'.$row->recReqID.'" value="'.$row->position_title.'" class="form-control" style="margin-top: 5px;margin-bottom: 5px;" placeholder="Position Title">';
                            $recruiter_list .='<input type="hidden" disabled name="hepl_recruitment_ref_number_'.$row->recReqID.'" id="hepl_recruitment_ref_number_'.$row->recReqID.'" value="'.$row->hepl_recruitment_ref_number.'" class="form-control" style="margin-top: 5px;margin-bottom: 5px;" placeholder="HEPL Recruitment Refernce Number*">';
                            $recruiter_list .='<input type="hidden" disabled name="hidden_status_'.$row->recReqID.'" id="hidden_status_'.$row->recReqID.'" value="'.$row->assigned_status.'" class="form-control" style="margin-top: 5px;margin-bottom: 5px;" placeholder="assigned_status">';

                            $unassign_btn = ' <button class="btn btn-sm btn-warning" type="button" id="unassign_yal" onclick="process_unassign('."'".$row->hepl_recruitment_ref_number."'".','."'".$row->recReqID."'".')">Unassign</button>';

                            $delete_btn = ' <button class="btn btn-sm btn-danger" style="margin-top:2px;" type="button" id="delete_hepl_row" onclick="delete_heplrr_row('."'".$row->hepl_recruitment_ref_number."'".','."'".$row->recReqID."'".')">Delete</button>';

                            return $recruiter_list."".$btn."".$unassign_btn."".$delete_btn;

                        }
                        else{
                            $btn .= '<button class="btn btn-sm btn-primary" id="btnAssign_'.$row->recReqID.'" onclick="process_assign('."'".$row->recReqID."'".');">Assign</button>';

                            $recruiter_list = '<select name="recruiter_list_'.$row->recReqID.'" id="recruiter_list_'.$row->recReqID.'" size="3" class="choices multiple-remove form-control" multiple>';
                            $recruiter_list .='<option value="">Select Recruiter*</option>';

                            foreach ($get_recruiter_result as $key => $grr) {
                                $recruiter_list .='<option value="'.$grr->empID.'">'.$grr->name.'</option>';
                            }
                            $recruiter_list .='</select>';
                            $recruiter_list .='<input type="text" name="sub_position_title_'.$row->recReqID.'" id="sub_position_title_'.$row->recReqID.'" class="form-control" placeholder="Sub Position Title" style="margin-bottom: 6px;">';
                            $recruiter_list .='<input type="hidden" disabled name="main_position_title_'.$row->recReqID.'" id="main_position_title_'.$row->recReqID.'" value="'.$row->position_title.'" class="form-control" style="margin-top: 5px;margin-bottom: 5px;" placeholder="Position Title">';

                            $recruiter_list .='<input type="hidden" name="hepl_recruitment_ref_number_'.$row->recReqID.'" id="hepl_recruitment_ref_number_'.$row->recReqID.'" value="'.$row->hepl_recruitment_ref_number.'" class="form-control" style="margin-top: -18px;margin-bottom: 5px;" placeholder="HEPL Recruitment Refernce Number*">';
                            $recruiter_list .='<input type="hidden" disabled name="hidden_status_'.$row->recReqID.'" id="hidden_status_'.$row->recReqID.'" value="'.$row->assigned_status.'" class="form-control" style="margin-top: 5px;margin-bottom: 5px;" placeholder="assigned_status">';

                            $delete_btn = ' <button class="btn btn-sm btn-danger" style="margin-top:2px;" type="button" id="delete_hepl_row" onclick="delete_heplrr_row('."'".$row->hepl_recruitment_ref_number."'".','."'".$row->recReqID."'".')">Delete</button>';

                            return $recruiter_list."".$btn."".$delete_btn;

                        }

                            // return $recruiter_list;
                    })
                    ->addColumn('interviewer', function($row){
                        $get_interviewer = $row->interviewer;

                        if($get_interviewer =='Self' || $get_interviewer =='SELF' || $get_interviewer =='self'){
                            $get_interviewer_self = $this->corepo->get_interviewer_self( $row->rfh_no );

                            $get_interviewer = $get_interviewer_self[0]->name;
                        }
                        return $get_interviewer;

                    })
                    ->addColumn('as_title', function($row) {

                        $as_title = $row->assigned_status;

                        return $as_title;
                    })
                    ->addColumn('ps_title', function($row) {

                        $ps_title = $row->request_status;

                        return $ps_title;
                    })
                    ->rawColumns(['as_title','ps_title','interviewer','ageing','open_date','action','assigned_status'])
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
         $sub_position_title = $request->input('sub_position_title');
         $position_title = $request->input('position_title');
         $hepl_recruitment_ref_number = $request->input('hepl_recruitment_ref_number');

        $input_details = array(
            'sub_position_title'=>$sub_position_title,
            'hepl_recruitment_ref_number'=>$request->input('hepl_recruitment_ref_number'),
        );
        $update_sub_position_title_res = $this->corepo->update_sub_position_title( $input_details );


        $get_count = count($recruiter_list_arr);

        if($get_count == 1){

            $check_input_details = array(
                'assigned_to'=>$request->input('recruiter_list')[0],
                'hepl_recruitment_ref_number'=>$request->input('hepl_recruitment_ref_number'),
            );
            $check_already_assigned_result = $this->corepo->check_recruiter_already_assigned( $check_input_details );

            if($check_already_assigned_result ==0){
                $input_details = array(
                    'recReqID'=>$request->input('rowID'),
                    'sub_position_title'=>$request->input('sub_position_title'),
                    'assigned_to'=>$request->input('recruiter_list')[0],
                    'assigned_date'=>date('Y-m-d'),
                    'hepl_recruitment_ref_number'=>$request->input('hepl_recruitment_ref_number'),
                    'assigned_status'=>"Assigned"
                );
                $edit_recruitreq_result = $this->corepo->process_recruitment_assign( $input_details );

                //    send mail


                $empID=$request->input('recruiter_list')[0];
                $user_row = $this->corepo->get_user_row( $empID );
                $to_email=$user_row[0]->email;
                // $to_email="ganagavathy.k@hemas.in";

                $get_title = "ProHire - Assigned To";
                $get_body1 = "Dear ".$user_row[0]->name;
                $get_body2 ='We have assigned a new position to you in ProHire, Good Luck!';
                $get_body3 ='Position Title: '.$position_title;
                $get_body4 ='Sub Position Title: '.$sub_position_title;
                $get_body5 ='Assigned From: '.auth()->user()->name;
                $get_body6 ='HEPl Recruitment Ref number: '.$hepl_recruitment_ref_number;
                $get_body7 ='Have any queries please contact our support Team.';
                $details = [
                    'title' => $get_title,
                    'body1' => $get_body1,
                    'body2' => $get_body2,
                    'body3' => $get_body3,
                    'body4' => $get_body4,
                    'body5' => $get_body5,
                    'body6' => $get_body6,
                    'body7' => $get_body7
                ];
                \Mail::to($to_email)->send(new \App\Mail\BeMail($details));

                // print_r($details);

                $response = 'Updated';
                return response()->json( ['response' => $response] );

            }
            else{
                $response = 'already_assigned';
                return response()->json( ['response' => $response] );
            }
        }else{

            $ssno = 1;

            $already_exits_status = 0;

            foreach ($recruiter_list_arr as $key => $recruiter_list_individual) {

                // get row values for duplicate recruitment
                $input_details_c = array(
                    'recReqID'=>$request->input('rowID'),
                );
                $get_recruitreq_dup_result = $this->corepo->get_recruitment_for_duplicate( $input_details_c );

                if($ssno ==1){

                    $check_input_details = array(
                        'assigned_to'=>$recruiter_list_individual,
                        'hepl_recruitment_ref_number'=>$request->input('hepl_recruitment_ref_number'),
                    );
                    $check_already_assigned_result = $this->corepo->check_recruiter_already_assigned( $check_input_details );

                    if($check_already_assigned_result == 0){
                        $input_details = array(
                            'recReqID'=>$request->input('rowID'),
                            'sub_position_title'=>$request->input('sub_position_title'),
                            'assigned_to'=>$recruiter_list_individual,
                            'assigned_date'=>date('Y-m-d'),
                            'hepl_recruitment_ref_number'=>$request->input('hepl_recruitment_ref_number'),
                            'assigned_status'=>"Assigned"
                        );
                        $edit_recruitreq_result = $this->corepo->process_recruitment_assign( $input_details );

                            // send mail
                            $empID=$recruiter_list_individual;
                            $user_row = $this->corepo->get_user_row( $empID );
                            $to_email=$user_row[0]->email;
                            // $to_email="ganagavathy.k@hemas.in";

                            $get_title = "ProHire - Assigned To";
                            $get_body1 = "Dear ".$user_row[0]->name;
                            $get_body2 ='We have assigned a new position to you in ProHire, Good Luck!';
                            $get_body3 ='Position Title: '.$request->input('position_title');
                            $get_body4 ='Sub Position Title: '.$request->input('sub_position_title');
                            $get_body5 ='Assigned From: '.auth()->user()->name;
                            $get_body6 ='Have any queries please contact our support Team.';
                            $details = [
                                'title' => $get_title,
                                'body1' => $get_body1,
                                'body2' => $get_body2,
                                'body3' => $get_body3,
                                'body4' => $get_body4,
                                'body5' => $get_body5,
                                'body6' => $get_body6
                            ];
                            \Mail::to($to_email)->send(new \App\Mail\BeMail($details));

                            // echo "3";
                            // print_r($details);

                    }
                    else{
                        $already_exits_status = 1;
                    }
                }else{
                    if(count($get_recruitreq_dup_result) != 0){

                        $form_credentials = array(
                            'rfh_no' => $get_recruitreq_dup_result[0]->rfh_no,
                            'position_title' => $get_recruitreq_dup_result[0]->position_title,
                            'sub_position_title' => $request->input('sub_position_title'),
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

                        $check_input_details = array(
                            'assigned_to'=>$recruiter_list_individual,
                            'hepl_recruitment_ref_number'=>$request->input('hepl_recruitment_ref_number'),
                        );
                        $check_already_assigned_result = $this->corepo->check_recruiter_already_assigned( $check_input_details );

                        if($check_already_assigned_result == 0){
                            $insert_reqcruitment_request_result = $this->corepo->reqcruitment_requestEntry( $form_credentials );
                             // send mail
                             $empID=$recruiter_list_individual;
                             $user_row = $this->corepo->get_user_row( $empID );
                             $to_email=$user_row[0]->email;
                            //  $to_email="ganagavathy.k@hemas.in";

                             $get_title = "ProHire - Assigned To";
                            $get_body1 = "Dear ".$user_row[0]->name;
                            $get_body2 ='We have assigned a new position to you in ProHire, Good Luck!';
                            $get_body3 ='Position Title: '.$get_recruitreq_dup_result[0]->position_title;
                            $get_body4 ='Sub Position Title: '.$get_recruitreq_dup_result[0]->sub_position_title;
                            $get_body5 ='Assigned From: '.auth()->user()->name;
                            $get_body6 ='Have any queries please contact our support Team.';
                            $details = [
                                'title' => $get_title,
                                'body1' => $get_body1,
                                'body2' => $get_body2,
                                'body3' => $get_body3,
                                'body4' => $get_body4,
                                'body5' => $get_body5,
                                'body6' => $get_body6
                            ];
                            \Mail::to($to_email)->send(new \App\Mail\BeMail($details));

                        }
                        else{
                            $already_exits_status = 1;

                        }
                    }
                }



                $ssno++;
            }

            if($already_exits_status ==0){
                $response = 'Updated';
                return response()->json( ['response' => $response] );
            }
            else{
                $response = 'already_assigned';
                return response()->json( ['response' => $response] );
            }

        }



    }


    public function process_recruitment_assigned_assign(Request $request){


        $recruiter_list_arr = $request->input('recruiter_list');
        $sub_position_title = $request->input('sub_position_title');

        $get_count = count($recruiter_list_arr);

            $ssno = 1;

            $already_exits_status = 0;
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
                            'sub_position_title' => $get_recruitreq_dup_result[0]->sub_position_title,
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

                        $input_details = array(
                            'sub_position_title'=>$get_recruitreq_dup_result[0]->sub_position_title,
                            'hepl_recruitment_ref_number'=>$request->input('hepl_recruitment_ref_number'),
                        );
                        $update_sub_position_title_res = $this->corepo->update_sub_position_title( $input_details );


                        $check_input_details = array(
                            'assigned_to'=>$recruiter_list_individual,
                            'hepl_recruitment_ref_number'=>$request->input('hepl_recruitment_ref_number'),
                        );

                        $check_already_assigned_result = $this->corepo->check_recruiter_already_assigned( $check_input_details );

                        if($check_already_assigned_result ==0){
                            $insert_reqcruitment_request_result = $this->corepo->reqcruitment_requestEntry( $form_credentials );
                            //    send mail
                            $empID=$recruiter_list_individual;
                            $user_row = $this->corepo->get_user_row( $empID );
                            $to_email=$user_row[0]->email;
                            // $to_email="ganagavathy.k@hemas.in";

                            $get_title = "ProHire - Assigned To";
                            $get_body1 = "Dear ".$user_row[0]->name;
                            $get_body2 ='We have assigned a new position to you in ProHire, Good Luck!';
                            $get_body3 ='Position Title: '.$form_credentials['position_title'];
                            $get_body4 ='Sub Position Title: '.$form_credentials['sub_position_title'];
                            $get_body5 ='Assigned From: '.auth()->user()->name;
                            $get_body6 ='Have any queries please contact our support Team.';
                            $details = [
                                'title' => $get_title,
                                'body1' => $get_body1,
                                'body2' => $get_body2,
                                'body3' => $get_body3,
                                'body4' => $get_body4,
                                'body5' => $get_body5,
                                'body6' => $get_body6,
                            ];
                            \Mail::to($to_email)->send(new \App\Mail\BeMail($details));

                            // $body_content1 = "Dear ".$user_row[0]->name;
                            // $body_content2 = "Your Got New Allocation";
                            // $body_content3 = "Position Title : ".$form_credentials['position_title']."<br>No of Position : ".$form_credentials['no_of_position']."<br>Assigned From : ".auth()->user()->name."";
                            // $body_content4 = "Have any queries please contact our support Team.";
                            // $body_content5 = "";
                            // $details = [
                            //     'subject' => 'Assigned To',
                            //     'body_content1' => $body_content1,
                            //     'body_content2' => $body_content2,
                            //     'body_content3' => $body_content3,
                            //     'body_content4' => $body_content4,
                            //     'body_content5' => $body_content5,
                            // ];

                            // $footer_img='<img src="http://hub1.cavinkare.in/CK_recruitment_management_new/public/assets/images/logo/logo.jpg" alt="" style="width:100px;">';
                            // $footer_th='<p>Thank you</p>';
                            // $footer_ad='<p>The HEPL Team</p>';

                            // $to      = $to_email;
                            // $subject = $details['subject'];
                            // $message = '<html>
                            // <body><p>'.$body_content1."</p>\r\n<h3>".$body_content2."</h3>\r\n<p>".$body_content3."</p>\r\n<p>".$body_content4."</p>\r\n".$footer_th."\r\n".$footer_img."\r\n".$footer_ad."</body>
                            // </html>";
                            // $headers  = 'MIME-Version: 1.0' . "\r\n";
                            // $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                            // $headers .= 'From: rfh@hemas.in'. "\r\n" .
                            //             'Reply-To: rfh@hemas.in' . "\r\n" .
                            //             'X-Mailer: PHP/' . phpversion();
                            // mail($to, $subject, $message, $headers);
                        }
                        else{
                            $already_exits_status = 1;
                        }
                    }

                $ssno++;
            }

            if($already_exits_status ==0){
                $response = 'Updated';
                return response()->json( ['response' => $response] );
            }
            else{
                $response = 'already_assigned';
                return response()->json( ['response' => $response] );
            }

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
            $af_sub_position_title = (!empty($_POST["af_sub_position_title"])) ? ($_POST["af_sub_position_title"]) : ('');
            $af_position_status = (!empty($_POST["af_position_status"])) ? ($_POST["af_position_status"]) : ('');
            $af_created_by = (!empty($_POST["af_created_by"])) ? ($_POST["af_created_by"]) : ('');

            if( $af_from_date || $af_to_date  || $af_sub_position_title || $af_position_title || $af_position_status || $af_created_by)
            {
                // get all data
                $advanced_filter = array(
                    'af_from_date'=>$af_from_date,
                    'af_to_date'=>$af_to_date,
                    'af_position_title'=>$af_position_title,
                    'af_sub_position_title'=>$af_sub_position_title,
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

                    $btn = '<a href="../cv_upload/'.$row->candidate_cv.'" class="badge bg-info" target="_blank"><i class="bi bi-eye"></i></a>';
                    return $btn;
                })

                ->addColumn('history', function($row) {

                    $btn = '<span class="badge bg-secondary" title="Followup History" onclick="candidate_follow_up('."'".$row->created_by."'".','."'".$row->hepl_recruitment_ref_number."'".','."'".$row->cdID."'".','."'".$row->candidate_name."'".');"><i class="bi bi-stack"></i></span>';
                            return $btn;


                })
                ->addColumn('closed_salary',function($row){

                    $input_details = array(
                        'cdID'=>$row->cdID,
                    );
                    $get_closed_salary_result = $this->corepo->get_closed_salary( $input_details );
                    // print_r($get_closed_salary_result);
                    if($row->status =='Candidate Onboarded' || $row->status =='Offer Released' || $row->status =='Offer Accepted'){
                        $session_user_details = auth()->user();
                        $get_role_type = $session_user_details->role_type;

                        if(count($get_closed_salary_result) != 0){ // or if(count($rows) === 0)

                            if($get_role_type =='virtual_audit'){

                                $closed_salary = $get_closed_salary_result[0]->closed_salary;

                            }else{
                                $edit_salary_btn = '<button type="button" onclick=pop_salary_edit('."'".$get_closed_salary_result[0]->closed_salary."'".','."'".$row->rfh_no."'".','."'".$row->cdID."'".'); class="btn btn-sm btn-dark"><i class="fa fa-edit"></i></button>';
                                $closed_salary = $get_closed_salary_result[0]->closed_salary." ".$edit_salary_btn;

                            }
                        }
                        else{
                            $closed_salary = '';

                        }
                    }else{
                        $closed_salary = '';

                    }


                    return $closed_salary;
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
                    $session_user_details = auth()->user();
                    $get_role_type = $session_user_details->role_type;

                    if($get_role_type =='virtual_audit'){
                        $delete_btn ='';
                        $edit_btn = '<button type="button" class="btn btn-info btn-sm"  onclick="edit_candidate_pop('."'".$row->cdID."'".')" disabled><i class="fa fa-edit"></i></button>';

                    }else{

                        $edit_btn = '<button type="button" class="btn btn-info btn-sm"  onclick="edit_candidate_pop('."'".$row->cdID."'".')"><i class="fa fa-edit"></i></button>';
                        $delete_btn = '<button type="button" class="btn btn-danger btn-sm" onclick="delete_candidate_pop('."'".$row->cdID."'".')"><i class="fa fa-trash"></i></button>';

                    }

                    return $action_btn = $edit_btn." ".$delete_btn;
                })
                ->rawColumns(['closed_salary','candidate_cv','history','status','status_cont','created_on','action'])
                ->make(true);

        }

        return view('candidate_profile');


    }

    public function candidate_follow_up_history_bc(Request $req){

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
            'delete_remark'=>$req->input('delete_remark')
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
            $af_sub_position_title = (!empty($_POST["af_sub_position_title"])) ? ($_POST["af_sub_position_title"]) : ('');
            $af_critical_position = (!empty($_POST["af_critical_position"])) ? ($_POST["af_critical_position"]) : ('');
            $af_position_status = (!empty($_POST["af_position_status"])) ? ($_POST["af_position_status"]) : ('');
            $af_closed_by = (!empty($_POST["af_closed_by"])) ? ($_POST["af_closed_by"]) : ('');
            $af_assigned_status = (!empty($_POST["af_assigned_status"])) ? ($_POST["af_assigned_status"]) : ('');
            $af_salary_range = (!empty($_POST["af_salary_range"])) ? ($_POST["af_salary_range"]) : ('');
            $af_band = (!empty($_POST["af_band"])) ? ($_POST["af_band"]) : ('');
            $af_location = (!empty($_POST["af_location"])) ? ($_POST["af_location"]) : ('');
            $af_business = (!empty($_POST["af_business"])) ? ($_POST["af_business"]) : ('');
            $af_billing_status = (!empty($_POST["af_billing_status"])) ? ($_POST["af_billing_status"]) : ('');
            $af_function = (!empty($_POST["af_function"])) ? ($_POST["af_function"]) : ('');
            $af_division = (!empty($_POST["af_division"])) ? ($_POST["af_division"]) : ('');
            $af_billable = (!empty($_POST["af_billable"])) ? ($_POST["af_billable"]) : ('');
            $af_raisedby = (!empty($_POST["af_raisedby"])) ? ($_POST["af_raisedby"]) : ('');
            $af_approvedby = (!empty($_POST["af_approvedby"])) ? ($_POST["af_approvedby"]) : ('');
            $af_teams = (!empty($_POST["af_teams"])) ? ($_POST["af_teams"]) : ('');


            if( $af_from_date || $af_to_date  || $af_position_title || $af_sub_position_title ||
                $af_critical_position || $af_position_status || $af_assigned_status ||
                $af_salary_range || $af_band || $af_location || $af_business ||
                $af_billing_status || $af_billing_status || $af_billable || $af_division  ||
                $af_raisedby || $af_approvedby || $af_teams || $af_closed_by)
            {
                // get all data
                $advanced_filter = array(
                    'af_from_date'=>$af_from_date,
                    'af_to_date'=>$af_to_date,
                    'af_position_title'=>$af_position_title,
                    'af_sub_position_title'=>$af_sub_position_title,
                    'af_critical_position'=>$af_critical_position,
                    'af_position_status'=>$af_position_status,
                    'af_closed_by'=>$af_closed_by,
                    'af_assigned_status'=>$af_assigned_status,
                    'af_salary_range'=>$af_salary_range,
                    'af_band'=>$af_band,
                    'af_location'=>$af_location,
                    'af_business'=>$af_business,
                    'af_billing_status'=>$af_billing_status,
                    'af_function'=>$af_function,
                    'af_division'=>$af_division,
                    'af_billable'=>$af_billable,
                    'af_raisedby'=>$af_raisedby,
                    'af_approvedby'=>$af_approvedby,
                    'af_teams'=>$af_teams,
                );

                $get_reqcruitment_request_result = $this->corepo->get_reqcruitment_request_afilter( $advanced_filter );

            }
            else{
                // get all data
                $get_reqcruitment_request_result = $this->corepo->get_reqcruitment_request_default_report(  );

            }

            $custom_filter = $request->get('custom_filter');
            return Datatables::of($get_reqcruitment_request_result)
            ->filter(function ($query) use ($custom_filter) {
                if ($custom_filter !='') {
                    $query->where('rr.position_title', 'LIKE', '%'.$custom_filter.'%')
                            ->orWhere('rr.hepl_recruitment_ref_number', 'LIKE', '%'.$custom_filter.'%')
                            ->orWhere('rr.sub_position_title', 'LIKE', '%'.$custom_filter.'%')
                            ->orWhere('rr.location', 'LIKE', '%'.$custom_filter.'%')
                            ->orWhere('rr.rfh_no', 'LIKE', '%'.$custom_filter.'%')
                            ->orWhere('rr.no_of_position', 'LIKE', '%'.$custom_filter.'%')
                            ->orWhere('rr.business', 'LIKE', '%'.$custom_filter.'%')
                            ->orWhere('rr.band', 'LIKE', '%'.$custom_filter.'%')
                            ->orWhere('rr.critical_position', 'LIKE', '%'.$custom_filter.'%')
                            ->orWhere('rr.division', 'LIKE', '%'.$custom_filter.'%')
                            ->orWhere('rr.function', 'LIKE', '%'.$custom_filter.'%')
                            ->orWhere('rr.billing_status', 'LIKE', '%'.$custom_filter.'%')
                            ->orWhere('rr.interviewer', 'LIKE', '%'.$custom_filter.'%')
                            ->orWhere('rr.salary_range', 'LIKE', '%'.$custom_filter.'%')
                            ->orWhere('tr.name', 'LIKE', '%'.$custom_filter.'%')
                            ->orWhere('tr.approved_by', 'LIKE', '%'.$custom_filter.'%');

                }

            })

                    ->addIndexColumn()
                    ->addColumn('ageing', function($row) {
                        $from = strtotime($row->open_date);

                        $today = time();
                        $difference = $today - $from;
                        $difference_days = floor($difference / 86400);  // (60 * 60 * 24)

                        $ageing = $difference_days;
                        // $ageing = $difference->y.' years, ' .$difference->m.' months, '.$difference->d.' days';

                        if($row->open_date >= '2021-12-08'){
                            $ageing_btn = '<span class="badge bg-danger badge-pill badge-round ml-1" onclick="show_stagesof_recruitment_pop('."'".$row->hepl_recruitment_ref_number."'".','."'".$row->rfh_no."'".')">'.$ageing.'</span>';

                        }else{
                            $ageing_btn = $ageing;
                        }

                        return $ageing_btn;
                    })
                    ->addColumn('open_date', function($row) {

                        $originalDate = $row->open_date;
                        $newDate = date("d-m-Y", strtotime($originalDate));

                        return $newDate;
                    })
                    ->addColumn('closed_date', function($row) {

                        if($row->request_status =='Closed'){

                        $originalDate = $row->close_date;
                        $closed_date = date("d-m-Y", strtotime($originalDate));

                        return $closed_date;
                        }else{
                            return "-";
                        }
                    })
                    ->addColumn('closed_by_name', function($row) {

                        if($row->request_status =='Closed' && $row->closed_by !=''){

                            $get_closed_by_name = $this->corepo->get_closed_by_name( $row->hepl_recruitment_ref_number );

                            if(count($get_closed_by_name) !=0){

                                $closed_by_name = $get_closed_by_name[0]->closed_by_name;
                            }else{
                                $closed_by_name = '';

                            }
                        }else{
                            $closed_by_name = '';
                        }
                        return $closed_by_name;

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
                        $btn = '<a href="ticket_candidate_details?hr_refno='.$row->hepl_recruitment_ref_number.'" target="_blank"><span class="badge bg-primary" id="btnAssign" title="Candidate Details"><i class="bi bi-people-fill"></i></span></a>';

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
                    ->addColumn('as_title', function($row) {

                        $as_title = $row->assigned_status;

                        return $as_title;
                    })
                    ->addColumn('ps_title', function($row) {

                        $ps_title = $row->request_status;

                        return $ps_title;
                    })
                    ->addColumn('current_status', function($row) {

                        $input_details_cc = array(
                            'hepl_recruitment_ref_number'=>$row->hepl_recruitment_ref_number,
                        );

                        $get_current_status = $this->corepo->get_current_status_rr( $input_details_cc );

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
                    ->addColumn('last_updated_at', function($row) {

                        $input_details_cc = array(
                            'hepl_recruitment_ref_number'=>$row->hepl_recruitment_ref_number,
                        );

                        $get_last_updated_at = $this->corepo->get_current_status_rr( $input_details_cc );

                        $last_updated ='';
                        if(count($get_last_updated_at) !=0){
                            foreach ($get_last_updated_at as $key => $gcs_value) {
                                if($gcs_value->updated_at !=''){
                                    $last_updated .=  date("d-m-Y", strtotime($gcs_value->updated_at));
                                }
                            }
                        }

                        return $last_updated;
                    })
                    ->rawColumns(['as_title','ps_title','cv_count','ageing','open_date','closed_date','action','assigned_status','tat_process','recruiters','interviewer','current_status','last_updated_at'])
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
    public function get_recruiter_team_list_af(Request $req){

        $team = $req->input('team');
        $get_recruiter_team_list_af_result = $this->corepo->get_recruiter_team_list_af( $team );

        return $get_recruiter_team_list_af_result;
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
        $position_reports = $req->input( 'position_reports' );
        $position_reports = explode("-",$position_reports);
        $position_reports_name = $position_reports[0];
        $position_reports_id = $position_reports[1];

        $approved_by = $req->input( 'approved_by' );
        $approved_by = explode("-",$approved_by);
        $approved_by_name = $approved_by[0];
        $approved_by_id = $approved_by[1];

        $form_credentials = array(
            'res_id' => $req->input( 'res_id' ),
            'rolls_option' => $req->input( 'rolls_option' ),
            'name' => $req->input( 'name' ),
            'mobile' => $req->input( 'mobile' ),
            'email' => $req->input( 'email' ),
            'position_reports' => $position_reports_name,
            'reporter_id' => $position_reports_id,
            'approved_by' => $approved_by_name,
            'approver_id' => $approved_by_id,
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
            'department' => $req->input( 'department' ),
            'vertical' => $req->input( 'vertical' ),
            'emp_category' => $req->input( 'emp_category' ),
            'attendance_format' => $req->input( 'attendance_format' ),
            'week_off' => $req->input( 'week_off' ),
            'ck_supervisior' => $req->input( 'ck_supervisior' ),
            'ck_mail' => $req->input( 'ck_mail' ),
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

            $get_recruiter_list_result = $this->corepo->get_recruiter_list_all(  );

            return Datatables::of($get_recruiter_list_result)
            ->addIndexColumn()
            ->addColumn('action', function($row) {

                $btn = '<span class="badge bg-info" title="Reset Password" onclick="reset_password('."'".$row->empID."'".');"><i class="bi bi-key-fill"></i></span>';
                $btn .= ' <span class="badge bg-danger" title="Delete" onclick="recruiter_delete_process('."'".$row->empID."'".');"><i class="bi bi-trash"></i></span>';
                $btn .= ' <span class="badge bg-primary" title="Edit" onclick="recruiter_edit_process('."'".$row->empID."'".');"><i class="bi bi-pencil"></i></span>';

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

        if($req->input( 'designation' ) == 'Backend Coordinator'){
            $role_type = "backend_coordinator";
            $team = "";
        }
        elseif ($req->input( 'designation' ) == 'Virtual Audit') {
            $role_type = "virtual_audit";
            $team = "";
        }
        else{
            $role_type = "recruiter";
            $team = $req->input( 'team' );
        }
        $color_code = $this->rand_color();
        $form_credentials = array(
            'empID' => $req->input( 'empID' ),
            'name' => $req->input( 'emp_name' ),
            'designation' => $req->input( 'designation' ),
            'email' => $req->input( 'email' ),
            'team' => $team,
            'role_type' => $role_type,
            'profile_status' => "Active",
            'password' => bcrypt("123456"),
            'color_code' => $color_code
        );
        $add_recruiter_process_result = $this->corepo->add_recruiter_process( $form_credentials );
      //  echo $color_code;
        $response = 'success';
        return response()->json( ['response' => $response] );
    }
    function rand_color() {
        return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
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

    public function get_recruiter_details(Request $req){
        $input_details = array(
            'empID'=>$req->input('empID'),
        );

        $get_recruiter_details_result = $this->corepo->get_recruiter_details( $input_details );

        return response()->json( $get_recruiter_details_result );
    }

    public function update_recruiter_details(Request $req){

        $input_details = array(
            'empID'=>$req->input('empID'),
            'name'=>$req->input('name'),
            'designation'=>$req->input('designation'),
            'email'=>$req->input('email'),
            'team'=>$req->input('team'),
        );

        $update_recruiter_details_result = $this->corepo->update_recruiter_details( $input_details );

        $response = 'Updated';
        return response()->json( ['response' => $response] );
    }

    public function recruiter_report(Request $request){
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
            $af_recruiters = (!empty($_POST["af_recruiters"])) ? ($_POST["af_recruiters"]) : ('');
            $af_teams = (!empty($_POST["af_teams"])) ? ($_POST["af_teams"]) : ('');
            $af_raisedby = (!empty($_POST["af_raisedby"])) ? ($_POST["af_raisedby"]) : ('');
            $af_approvedby = (!empty($_POST["af_approvedby"])) ? ($_POST["af_approvedby"]) : ('');

            $session_user_details = auth()->user();
            $assigned_to = $session_user_details->empID;

            if( $af_from_date || $af_to_date  || $af_position_title || $af_sub_position_title ||
                $af_critical_position || $af_position_status || $af_assigned_status ||
                $af_salary_range || $af_band || $af_location || $af_business ||
                $af_billing_status || $af_billing_status || $af_recruiters || $af_teams ||
                $af_division || $af_raisedby || $af_approvedby)
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
                    'assigned_to'=>$af_recruiters,
                    'af_teams'=>$af_teams,
                    'af_raisedby'=>$af_raisedby,
                    'af_approvedby'=>$af_approvedby,
                );

                $get_ticket_report_recruiter_result = $this->corepo->get_ticket_report_recruiter_afilter( $advanced_filter );

            }
            else{
                $get_ticket_report_recruiter_result = $this->corepo->get_ticket_report_recruiter(  );

            }

            return Datatables::of($get_ticket_report_recruiter_result)
                    ->addIndexColumn()
                    ->addColumn('ageing', function($row) {

                        $from = strtotime($row->assigned_date);

                        $today = time();
                        $difference = $today - $from;
                        $difference_days = floor($difference / 86400);  // (60 * 60 * 24)



                        $ageing = $difference_days;
                        // $ageing = $difference->y.' years, ' .$difference->m.' months, '.$difference->d.' days';

                        // $ageing_btn = '<span class="badge bg-danger badge-pill badge-round ml-1" onclick="show_stagesof_recruitment_pop('."'".$row->hepl_recruitment_ref_number."'".','."'".$row->rfh_no."'".')">'.$ageing.'</span>';
                        if($row->open_date >= '2021-12-08'){
                            $ageing_btn = '<span class="badge bg-danger badge-pill badge-round ml-1" onclick="show_stagesof_recruitment_pop('."'".$row->hepl_recruitment_ref_number."'".','."'".$row->rfh_no."'".','."'".$row->assigned_to."'".')">'.$ageing.'</span>';

                        }else{
                            $ageing_btn = $ageing;
                        }

                        return $ageing_btn;
                    })
                    ->addColumn('open_date', function($row) {

                        $originalDate = $row->open_date;
                        $newDate = date("d-m-Y", strtotime($originalDate));

                        return $newDate;
                    })
                    ->addColumn('assigned_date', function($row) {

                        $get_assigned_date = $row->assigned_date;
                        $assigned_date = date("d-m-Y", strtotime($get_assigned_date));

                        return $assigned_date;
                    })
                    ->addColumn('closed_date', function($row) {

                        if($row->request_status =='Closed'){

                        $originalDate = $row->close_date;
                        $closed_date = date("d-m-Y", strtotime($originalDate));

                        return $closed_date;
                        }else{
                            return "-";
                        }
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
                        $btn = '<a href="recruiter_report_cp?hr_refno='.$row->hepl_recruitment_ref_number.'" class="up_href" target="_blank"><span class="badge bg-primary" id="btnAssign" title="Candidate Details"><i class="bi bi-people-fill"></i></span></a>';

                        return $btn;
                    })
                    ->addColumn('tat_process', function($row) {
                        $from = strtotime($row->assigned_date);

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


                    ->addColumn('recruiter_ageing', function($row) {
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
                                            $ageing = "0 days";

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
                    ->addColumn('recruiter_ageing_status', function($row) {

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
                    ->addColumn('interviewer', function($row){
                        $get_interviewer = $row->interviewer;

                        if($get_interviewer =='Self' || $get_interviewer =='SELF' || $get_interviewer =='self'){
                            $get_interviewer_self = $this->corepo->get_interviewer_self( $row->rfh_no );

                            $get_interviewer = $get_interviewer_self[0]->name;
                        }
                        return $get_interviewer;

                    })
                    ->addColumn('cv_count', function($row) {

                        $input_details_cc = array(
                            'hepl_recruitment_ref_number'=>$row->hepl_recruitment_ref_number,
                            'created_by'=>$row->assigned_to,

                        );

                        $cv_count = $this->corepo->get_cv_count_rr( $input_details_cc );

                        return $cv_count;
                    })
                    ->addColumn('as_title', function($row) {

                        $as_title = $row->assigned_status;

                        return $as_title;
                    })
                    ->addColumn('ps_title', function($row) {

                        $ps_title = $row->request_status;

                        return $ps_title;
                    })
                    ->addColumn('current_status', function($row) {

                        $input_details_cc = array(
                            'hepl_recruitment_ref_number'=>$row->hepl_recruitment_ref_number,
                            'created_by'=>$row->assigned_to,
                        );

                        $get_current_status = $this->corepo->get_current_status_recruiter( $input_details_cc );

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
                    ->addColumn('last_modified', function($row) {

                        $input_details_cc = array(
                            'hepl_recruitment_ref_number'=>$row->hepl_recruitment_ref_number,
                            'created_by'=>$row->assigned_to,
                        );

                        $get_last_modified = $this->corepo->get_current_status_recruiter( $input_details_cc );

                        $last_modified ='';
                        if(count($get_last_modified) !=0){
                            foreach ($get_last_modified as $key => $gcs_value) {
                                if($gcs_value->updated_at !=''){
                                    $last_modified .=  date("d-m-Y", strtotime($gcs_value->updated_at));

                                }
                            }
                        }

                        return $last_modified;
                    })
                    ->rawColumns(['as_title','ps_title','cv_count','ageing','open_date','closed_date','action','assigned_status','tat_process','recruiter_ageing','recruiter_ageing_status','interviewer','assigned_date','current_status','last_modified'])
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

        return view('recruiter_report_cp');

    }


    public function get_offer_released_bc(Request $req){

        $input_details = array(
            'request_status'=>"Open",
            'profile_status_1'=>"Profile Rejected",
            'profile_status_2'=>"Candidate Onboarded"
        );

        $get_offer_released_bc_result = $this->corepo->get_offer_released_bc( $input_details );

        return Datatables::of($get_offer_released_bc_result)
            ->addIndexColumn()

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


                return $closed_date;
            })

            ->addColumn('date_of_joining', function($row) {

                $originalDate = $row->date_of_joining;
                $date_of_joining = date("d-m-Y", strtotime($originalDate));

                return $date_of_joining;
            })
            ->rawColumns(['followup_history','candidate_cv','closed_date','date_of_joining'])
            ->make(true);
    }

    public function get_candidate_onborded_history_bc(Request $req){
        // get all data

        $input_details = array(
            'status'=>"Candidate Onboarded",
        );

        $get_candidate_onborded_history_result = $this->corepo->get_candidate_onborded_history( $input_details );


        return Datatables::of($get_candidate_onborded_history_result)
            ->addIndexColumn()
            ->addColumn('candidate_cv', function($row) {

                $btn = '<a href="../cv_upload/'.$row->candidate_cv.'" class="badge bg-info" target="_blank"><i class="bi bi-eye"></i></a>';
                return $btn;
            })
            ->addColumn('ctc', function($row) {

                $btn = '<a href="'.asset('public/'.$row->offer_letter_filename).'" class="badge bg-primary" target="_blank">'.$row->closed_salary_pa.'</i></a>';
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
            ->rawColumns(['candidate_cv','history','status','ctc'])
            ->make(true);
    }

    public function process_unassign(Request $req){

        $input_details = array(
            'hepl_recruitment_ref_number'=>$req->input('heplrr_unassign_yal'),
            'recReqID'=>$req->input('recReqID_unassign_yal'),
            'assigned_status'=>"Pending",
        );

        $process_unassign_result = $this->corepo->process_unassign( $input_details );

        $response = 'Updated';
        return response()->json( ['response' => $response] );
    }

    public function update_no_of_position(Request $req){


        if($req->input('action_type') =='add'){

            $put_no_of_position = $req->input('current_nop') + $req->input('no_of_position');
            $input_details = array(
                'rfh_no'=>$req->input('rfh_nop'),
            );
            $get_recreq_result = $this->corepo->get_recruitment_requests( $input_details );


            $no_of_positions= $req->input('no_of_position');
				$requirement_last_id = $this->corepo->get_table_last_row('recruitment_requests');
				for($i=1 ; $i<=$no_of_positions ; $i++){
					$previous_id1 = $this->corepo->get_table_last_row('recruitment_requests');

					$previous_id = $previous_id1[0]->id;
					if($previous_id != ''){
						$strLen = strlen($previous_id);
						$newId = $previous_id+1;
						if($strLen == 1){
							$recReqID = 'RR00000000'.$newId;
						}elseif($strLen == 2){
							$recReqID = 'RR0000000'.$newId;
						}elseif($strLen == 3){
							$recReqID = 'RR000000'.$newId;
						}elseif($strLen == 4){
							$recReqID = 'RR00000'.$newId;
						}elseif($strLen == 5){
							$recReqID = 'RR0000'.$newId;
						}elseif($strLen == 6){
							$recReqID = 'RR000'.$newId;
						}elseif($strLen == 7){
							$recReqID = 'RR00'.$newId;
						}elseif($strLen == 8){
							$recReqID = 'RR0'.$newId;
						}elseif($strLen == 9){
							$recReqID = 'RR'.$newId;

						}
					}else{
						$recReqID = 'RR000000001';
					}

					$last_heplno_result = $this->corepo->get_last_hepl_reference_no( );


					if(count($last_heplno_result) != 0){

						 $response = $last_heplno_result[0]->hepl_recruitment_ref_number;

						$num = preg_replace('/\D/', '',$response);
						$hepl_rrn = sprintf('HEPLRFH%s', str_pad($num + 1, "5", "0", STR_PAD_LEFT));


					}
					else{
						$hepl_rrn = 'HEPLRFH00001';

					}

                    $Date = date('Y-m-d');

					$data1['recReqID']   			= $recReqID;
					$data1['rfh_no']   				= $req->input('rfh_nop');
					$data1['position_title']  		= $get_recreq_result[0]->position_title;
					$data1['no_of_position']  		= $put_no_of_position;
					$data1['band']  				= $get_recreq_result[0]->band;
					$data1['open_date']  			= $Date;
					$data1['critical_position']		= $get_recreq_result[0]->critical_position;
					$data1['business']  			= $get_recreq_result[0]->business;
					$data1['division'] 		 		= $get_recreq_result[0]->division;
					$data1['function']  			= $get_recreq_result[0]->function;
					$data1['location']  			= $get_recreq_result[0]->location;
					$data1['billing_status']		= $get_recreq_result[0]->billing_status;
					$data1['interviewer']			= $get_recreq_result[0]->interviewer;
					$data1['salary_range']  		= $get_recreq_result[0]->salary_range;
					$data1['request_status']		= "Open";
					$data1['close_date']			= date('Y-m-d', strtotime($Date. ' + 15 days'));
					$data1['assigned_status']		= 'Pending';
					$data1['hepl_recruitment_ref_number'] = $hepl_rrn;
					$data1['created_by'] = auth()->user()->empID;
					$data1['modified_by'] = auth()->user()->empID;

					$this->corepo->insert_data($data1);
				}

                $response = 'Updated';
                return response()->json( ['response' => $response] );
        }else{
            $input_details = array(
                'rfh_no'=>$req->input('rfh_nop'),
                'request_status'=>"Open",
                'assigned_status'=>"Pending",
            );
            $unassigned_count = $this->corepo->unassigned_count_nop( $input_details );

            if($req->input('no_of_position') > $unassigned_count){
                $response = 'unassign_alert';
                return response()->json( ['response' => $response] );
            }
            elseif($req->input('no_of_position') == $unassigned_count){

                $put_no_of_position = $req->input('current_nop') - $req->input('no_of_position');

                $input_details = array(
                    'rfh_no'=>$req->input('rfh_nop'),
                    'request_status'=>"Open",
                    'assigned_status'=>"Pending",
                    'no_of_position'=>$put_no_of_position ,
                );
                $delete_unassigned_ticket_result  = $this->corepo->delete_unassigned_ticket( $input_details );

                $response = 'Updated';
                return response()->json( ['response' => $response] );

            }else{

                $put_no_of_position = $req->input('current_nop') - $req->input('no_of_position');

                $input_details = array(
                    'rfh_no'=>$req->input('rfh_nop'),
                    'request_status'=>"Open",
                    'assigned_status'=>"Pending",
                    'limit_count'=>$req->input('no_of_position'),
                    'no_of_position'=>$put_no_of_position ,

                );

                $delete_unassigned_ticket_el_result  = $this->corepo->delete_unassigned_ticket_el( $input_details );

                $response = 'Updated';
                return response()->json( ['response' => $response] );

            }
        }


    }


    public function deleted_request(Request $request){

        if ($request->ajax()) {

            $af_from_date = (!empty($_POST["af_from_date"])) ? ($_POST["af_from_date"]) : ('');
            $af_to_date = (!empty($_POST["af_to_date"])) ? ($_POST["af_to_date"]) : ('');
            $af_position_title = (!empty($_POST["af_position_title"])) ? ($_POST["af_position_title"]) : ('');
            $af_sub_position_title = (!empty($_POST["af_sub_position_title"])) ? ($_POST["af_sub_position_title"]) : ('');
            $af_critical_position = (!empty($_POST["af_critical_position"])) ? ($_POST["af_critical_position"]) : ('');
            $af_position_status = (!empty($_POST["af_position_status"])) ? ($_POST["af_position_status"]) : ('');
            $af_assigned_status = (!empty($_POST["af_assigned_status"])) ? ($_POST["af_assigned_status"]) : ('');
            $af_band = (!empty($_POST["af_band"])) ? ($_POST["af_band"]) : ('');
            $af_location = (!empty($_POST["af_location"])) ? ($_POST["af_location"]) : ('');
            $af_business = (!empty($_POST["af_business"])) ? ($_POST["af_business"]) : ('');
            $af_function = (!empty($_POST["af_function"])) ? ($_POST["af_function"]) : ('');
            $af_division = (!empty($_POST["af_division"])) ? ($_POST["af_division"]) : ('');
            $af_billable = (!empty($_POST["af_billable"])) ? ($_POST["af_billable"]) : ('');
            $af_raisedby = (!empty($_POST["af_raisedby"])) ? ($_POST["af_raisedby"]) : ('');
            $af_approvedby = (!empty($_POST["af_approvedby"])) ? ($_POST["af_approvedby"]) : ('');

            $advanced_filter = array(
                'af_assigned_status'=>'Pending',
                'af_from_date'=>$af_from_date,
                'af_to_date'=>$af_to_date,
                'af_position_title'=>$af_position_title,
                'af_sub_position_title'=>$af_sub_position_title,
                'af_critical_position'=>$af_critical_position,
                'af_position_status'=>$af_position_status,
                'af_sub_position_status'=>$af_position_status,
                'af_band'=>$af_band,
                'af_location'=>$af_location,
                'af_business'=>$af_business,
                'af_function'=>$af_function,
                'af_division'=>$af_division,
                'af_billable'=>$af_billable,
                'af_raisedby'=>$af_raisedby,
                'af_approvedby'=>$af_approvedby,
            );

            $get_reqcruitment_request_result = $this->corepo->get_deleted_request( $advanced_filter );

            return Datatables::of($get_reqcruitment_request_result)
                    ->addIndexColumn()
                    ->addColumn('ageing', function($row) {
                        $from = strtotime($row->open_date);

                        $today = time();
                        $difference = $today - $from;
                        $difference_days = floor($difference / 86400);  // (60 * 60 * 24)

                        $ageing = $difference_days;

                        return $ageing;
                    })
                    ->addColumn('open_date', function($row) {

                        $originalDate = $row->open_date;
                        $newDate = date("d-m-Y", strtotime($originalDate));

                        return $newDate;
                    })
                    ->addColumn('closed_date', function($row) {
                        if($row->request_status =='Closed'){

                            $originalDate_cd = $row->close_date;
                            $closed_date = date("d-m-Y", strtotime($originalDate_cd));

                            return $closed_date;
                        }else{
                            $closed_date = '-';
                            return $closed_date;
                        }
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
                        $btn = '<a href="deleted_cp?rfh_no='.$row->rfh_no.'" target="_blank"><span class="badge bg-primary" id="btnAssign" title="Candidate Details"><i class="bi bi-people-fill"></i></span></a>';

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
                            $get_interviewer = $get_interviewer_self[0]->name;
                        }
                        return $get_interviewer;

                    })
                    ->addColumn('edit_btn', function($row){
                        $edit_btn = '<a href="edit_recruit_request_new?rfh_no='.$row->rfh_no.'" target="_blank"><button class="btn btn-sm btn-secondary" id="btnAssign" title="Edit Recruitment"><i class="bi bi-pen-fill"></i></button></a>';
                        return $edit_btn;
                    })
                    ->addColumn('as_title', function($row) {

                        $as_title = $row->assigned_status;

                        return $as_title;
                    })
                    ->addColumn('ps_title', function($row) {

                        $ps_title = $row->request_status;

                        return $ps_title;
                    })
                    ->addColumn('no_of_position', function($row){

                        $no_of_position = $row->no_of_position;

                        return $no_of_position;

                    })
                    ->rawColumns(['no_of_position','as_title','ps_title','edit_btn','ageing','open_date','closed_date','action','assigned_status','tat_process','interviewer'])
                    ->make(true);
        }
        return view('deleted_request');
    }

    public function get_del_candidate_profile(Request $request){

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
                    'rfh_no'=>$request->input('rfh_no'),
                );

                // get all data
                $get_reqcruitment_request_result = $this->corepo->get_del_ticket_candidate_details_af( $input_details );

            }
            else{
                $input_details = array(
                    'rfh_no'=>$request->input('rfh_no'),
                );

                // get all data
                $get_reqcruitment_request_result = $this->corepo->get_del_ticket_candidate_details( $input_details );

            }

            return Datatables::of($get_reqcruitment_request_result)
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
                $newDate = date("d-m-Y", strtotime($originalDate));

                return $newDate;
            })


            ->rawColumns(['candidate_cv','history','status','status_cont','created_on'])
            ->make(true);
        }
        return view('deleted_cp');
    }

    public function update_sub_position_title(Request $request){

        $input_details = array(
            'recReqID'=>$request->input('rowID'),
            'sub_position_title'=>$request->input('sub_position_title'),
            'hepl_recruitment_ref_number'=>$request->input('hepl_recruitment_ref_number'),
        );
        $update_sub_position_title_res = $this->corepo->update_sub_position_title( $input_details );

        $response = 'Updated';
        return response()->json( ['response' => $response] );
    }

    public function process_hepldelete(Request $req){

        $input_details = array(
            'hepl_recruitment_ref_number'=>$req->input('heplrr_del'),
            'recReqID'=>$req->input('recReqID_del'),
        );

        $process_hepldelete_result = $this->corepo->process_hepldelete( $input_details );

        $response = 'Deleted';
        return response()->json( ['response' => $response] );
    }

    public function daily_report(Request $request){

        if ($request->ajax()) {

            $afc_from_date = (!empty($_POST["afc_from_date"])) ? ($_POST["afc_from_date"]) : ('');
            $afc_to_date = (!empty($_POST["afc_to_date"])) ? ($_POST["afc_to_date"]) : ('');
            $afc_position_title = (!empty($_POST["afc_position_title"])) ? ($_POST["afc_position_title"]) : ('');
            $afc_sub_position_title = (!empty($_POST["afc_sub_position_title"])) ? ($_POST["afc_sub_position_title"]) : ('');
            $afc_source = (!empty($_POST["afc_source"])) ? ($_POST["afc_source"]) : ('');
            $afc_location = (!empty($_POST["afc_location"])) ? ($_POST["afc_location"]) : ('');
            $afc_business = (!empty($_POST["afc_business"])) ? ($_POST["afc_business"]) : ('');
            $afc_band = (!empty($_POST["afc_band"])) ? ($_POST["afc_band"]) : ('');
            $afc_created_by = (!empty($_POST["afc_created_by"])) ? ($_POST["afc_created_by"]) : ('');
            $afc_raisedby = (!empty($_POST["afc_raisedby"])) ? ($_POST["afc_raisedby"]) : ('');
            $afc_billable = (!empty($_POST["afc_billable"])) ? ($_POST["afc_billable"]) : ('');
            $afc_teams = (!empty($_POST["afc_teams"])) ? ($_POST["afc_teams"]) : ('');

            $afc_function = (!empty($_POST["afc_function"])) ? ($_POST["afc_function"]) : ('');
            $afc_division = (!empty($_POST["afc_division"])) ? ($_POST["afc_division"]) : ('');

            $afc_doj_from_date = (!empty($_POST["afc_doj_from_date"])) ? ($_POST["afc_doj_from_date"]) : ('');
            $afc_doj_to_date = (!empty($_POST["afc_doj_to_date"])) ? ($_POST["afc_doj_to_date"]) : ('');

            // if( $afc_from_date || $afc_to_date || $afc_position_title || $afc_sub_position_title || $afc_source || $afc_location ||
            //     $afc_business || $afc_band || $afc_created_by || $afc_raisedby || $afc_billable || $afc_teams
            //     || $afc_function || $afc_division || $afc_doj_from_date || $afc_doj_to_date){
if($afc_teams == ''){
    $afc_teams = 'HEPL';
}
                $input_details = array(
                    'afc_from_date'=>$afc_from_date,
                    'afc_to_date'=>$afc_to_date,
                    'afc_position_title'=>$afc_position_title,
                    'afc_sub_position_title'=>$afc_sub_position_title,
                    'afc_source'=>$afc_source,
                    'afc_location'=>$afc_location,
                    'afc_business'=>$afc_business,
                    'afc_band'=>$afc_band,
                    'afc_created_by'=>$afc_created_by,
                    'afc_raisedby'=>$afc_raisedby,
                    'afc_billable'=>$afc_billable,
                    'afc_function'=>$afc_function,
                    'afc_division'=>$afc_division,
                    'afc_doj_from_date' =>$afc_doj_from_date,
                    'afc_doj_to_date' =>$afc_doj_to_date,
                    'afc_teams' =>$afc_teams
                );

                // get all data
                $get_closed_position_result = $this->corepo->get_closed_position_details_af( $input_details );

            // }
            // else{
            //     $input_details = array(
            //         'request_status'=>"Closed",
            //     );

            //     // get all data
            //     $get_closed_position_result = $this->corepo->get_closed_position_details( $input_details );

            // }

            return Datatables::of($get_closed_position_result)
            ->addIndexColumn()

            ->addColumn('current_status', function($row) {

                $input_details_cc = array(
                    'hepl_recruitment_ref_number'=>$row->hepl_recruitment_ref_number,
                );

                $get_current_status = $this->corepo->get_current_status_rr( $input_details_cc );

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
            ->addColumn('closed_by_name', function($row) {

                if($row->request_status =='Closed' && $row->closed_by !=''){

                    $get_closed_by_name = $this->corepo->get_closed_by_name( $row->hepl_recruitment_ref_number );

                    if(count($get_closed_by_name) !=0){

                        $closed_by_name = $get_closed_by_name[0]->closed_by_name;
                    }else{
                        $closed_by_name = '';

                    }
                }else{
                    $closed_by_name = '';
                }
                return $closed_by_name;

            })
            ->addColumn('open_date', function($row) {

                $originalDate = $row->open_date;
                $newDate = date("d-m-Y", strtotime($originalDate));

                return $newDate;
            })
            ->addColumn('date_of_joining', function($row) {

                $originalDate = $row->date_of_joining;
                $newDate = date("d-m-Y", strtotime($originalDate));

                return $newDate;
            })
            ->addColumn('close_date', function($row) {

                $originalDate = $row->close_date;
                $newDate = date("d-m-Y", strtotime($originalDate));

                return $newDate;
            })
            ->addColumn('assigned_date', function($row) {
                $input_details = array(
                        'hepl_recruitment_ref_number'=> $row->hepl_recruitment_ref_number,
                        'closed_by'=> $row->closed_by,

                );
                        $get_dt = $this->corepo->get_assigned_date_closed_report( $input_details );

                        if(count($get_dt) !=0){
                            foreach ($get_dt as $key => $date) {

                                    $assigned_date = $date->assigned_date;
                                    $assigned_date = date("d-m-Y", strtotime($assigned_date));

                            }

                        }
                        else{
                            $assigned_date = "";
                        }
                        return $assigned_date;


            })
            ->rawColumns(['assigned_date','open_date','date_of_joining','close_date','current_status','closed_by_name'])
            ->make(true);
        }
        return view('daily_report');

    }

    public function get_open_daily_report(Request $request){
        if ($request->ajax()) {

            $af_from_date = (!empty($_POST["af_from_date"])) ? ($_POST["af_from_date"]) : ('');
            $af_to_date = (!empty($_POST["af_to_date"])) ? ($_POST["af_to_date"]) : ('');
            $af_position_title = (!empty($_POST["af_position_title"])) ? ($_POST["af_position_title"]) : ('');
            $af_sub_position_title = (!empty($_POST["af_sub_position_title"])) ? ($_POST["af_sub_position_title"]) : ('');
            $af_location = (!empty($_POST["af_location"])) ? ($_POST["af_location"]) : ('');
            $af_business = (!empty($_POST["af_business"])) ? ($_POST["af_business"]) : ('');
            $af_teams = (!empty($_POST["af_teams"])) ? ($_POST["af_teams"]) : ('');
            $af_raisedby = (!empty($_POST["af_raisedby"])) ? ($_POST["af_raisedby"]) : ('');
            $af_interviewer = (!empty($_POST["af_interviewer"])) ? ($_POST["af_interviewer"]) : ('');

            $af_billable = (!empty($_POST["af_billable"])) ? ($_POST["af_billable"]) : ('');
            $af_function = (!empty($_POST["af_function"])) ? ($_POST["af_function"]) : ('');
            $af_division = (!empty($_POST["af_division"])) ? ($_POST["af_division"]) : ('');

            // if( $af_from_date || $af_to_date || $af_position_title || $af_sub_position_title || $af_location ||
            //     $af_business || $af_teams || $af_raisedby || $af_interviewer
            //     || $af_billable || $af_function || $af_division){

                $input_details = array(
                    'af_from_date'=>$af_from_date,
                    'af_to_date'=>$af_to_date,
                    'af_position_title'=>$af_position_title,
                    'af_sub_position_title'=>$af_sub_position_title,
                    'af_location'=>$af_location,
                    'af_business'=>$af_business,
                    'af_teams'=>$af_teams,
                    'af_raisedby'=>$af_raisedby,
                    'af_interviewer'=>$af_interviewer,
                    'af_billable'=>$af_billable,
                    'af_function'=>$af_function,
                    'af_division'=>$af_division,
                    'request_status_1'=>"Closed",
                    'request_status_2'=>"On Hold",
                );
                // get all data
                $get_open_position_result = $this->corepo->get_open_position_details_af( $input_details );
          //  }
            // else{
            //     $input_details = array(
            //         'request_status_1'=>"Closed",
            //         'request_status_2'=>"On Hold",
            //     );

            //     get all data
            //     $get_open_position_result = $this->corepo->get_open_position_details( $input_details );

            // }

            return Datatables::of($get_open_position_result)
            ->addIndexColumn()
            ->addColumn('position_ageing', function($row) {
                $from = strtotime($row->open_date);

                $today = time();
                $difference = $today - $from;
                $difference_days = floor($difference / 86400);  // (60 * 60 * 24)

                $ageing = $difference_days;
                // $ageing = $difference->y.' years, ' .$difference->m.' months, '.$difference->d.' days';

                return $ageing;
            })
            ->addColumn('profile_ageing', function($row) {

                // get all data
                $recruiter_last_modified_date = array(
                    'rfh_no'=>$row->rfh_no,
                    'hepl_recruitment_ref_number'=>$row->hepl_recruitment_ref_number,
                );
                $recruiter_ageing_result = $this->corepo->recruiter_last_modified_date_dr( $recruiter_last_modified_date );
                if(isset($recruiter_ageing_result[0]->updated_at)){

                    $orl_mdate = $recruiter_ageing_result[0]->updated_at;

                    $newrl_mdate = date("d-m-Y", strtotime($orl_mdate));
                    $thirdrl_mdate= date('d-m-Y', strtotime($newrl_mdate. ' + 3 days'));
                    $forthrl_mdate= date('d-m-Y', strtotime($newrl_mdate. ' + 4 days'));
                    $currentDate=date('d-m-Y');

                    $recruiter_ageing_filter = array(
                        'rfh_no'=>$row->rfh_no,
                        'hepl_recruitment_ref_number'=>$row->hepl_recruitment_ref_number,
                        'check_date_1'=>$orl_mdate,
                        'check_date_2'=>$thirdrl_mdate,

                    );

                    $recruiter_ageing_result = $this->corepo->recruiter_ageing_details_dr( $recruiter_ageing_filter );

                    if(count($recruiter_ageing_result) ==0){

                        if($currentDate ==$forthrl_mdate){

                            // check for fourth day
                            $recruiter_ageing_filter = array(
                                'rfh_no'=>$row->rfh_no,
                                'hepl_recruitment_ref_number'=>$row->hepl_recruitment_ref_number,
                                'check_date_1'=>$orl_mdate,
                                'check_date_2'=>$forthrl_mdate,
                            );
                            $recruiter_ageing_result_fd = $this->corepo->recruiter_ageing_details_dr( $recruiter_ageing_filter );

                            if(count($recruiter_ageing_result_fd) ==0){
                                $new_updated_at = date("Y-m-d", strtotime($orl_mdate));

                                $from = strtotime($new_updated_at);

                                $today = time();
                                $difference = $today - $from;
                                $difference_days = floor($difference / 86400);  // (60 * 60 * 24)

                                $ageing = $difference_days." days";

                                return $ageing;
                            }
                            else{
                                $ageing = "0 days";

                                return $ageing;
                            }
                        }
                        else{
                            $new_updated_at = date("Y-m-d", strtotime($orl_mdate));

                            $from = strtotime($new_updated_at);

                            $today = time();
                            $difference = $today - $from;
                            $difference_days = floor($difference / 86400);  // (60 * 60 * 24)

                            $ageing = $difference_days." days";
                            return $ageing;

                        }
                    }
                    else{

                        $ageing = "0 days";

                        return $ageing;

                    }


                }else{

                    if($row->assigned_date !=''){
                        $from = strtotime($row->assigned_date);

                        $today = time();
                        $difference = $today - $from;
                        $difference_days = floor($difference / 86400);  // (60 * 60 * 24)

                         $ageing = $difference_days." days";

                        return $ageing;
                    }
                    else{
                        $from = strtotime($row->open_date);

                        $today = time();
                        $difference = $today - $from;
                        $difference_days = floor($difference / 86400);  // (60 * 60 * 24)

                         $ageing = $difference_days." days";

                        return $ageing;
                    }
                }

            })
            ->addColumn('profile_ageing_status', function($row) {


                // get all data
                    $recruiter_last_modified_date = array(
                        'rfh_no'=>$row->rfh_no,
                        'hepl_recruitment_ref_number'=>$row->hepl_recruitment_ref_number,
                    );
                    $recruiter_ageing_result = $this->corepo->recruiter_last_modified_date_dr( $recruiter_last_modified_date );
                    if(isset($recruiter_ageing_result[0]->updated_at)){

                        $orl_mdate = $recruiter_ageing_result[0]->updated_at;

                        $newrl_mdate = date("d-m-Y", strtotime($orl_mdate));

                        $thirdrl_mdate= date('d-m-Y', strtotime($newrl_mdate. ' + 3 days'));
                        $forthrl_mdate= date('d-m-Y', strtotime($newrl_mdate. ' + 4 days'));
                        $currentDate=date('d-m-Y');
                        $recruiter_ageing_filter = array(
                            'rfh_no'=>$row->rfh_no,
                            'hepl_recruitment_ref_number'=>$row->hepl_recruitment_ref_number,
                            'check_date_1'=>$orl_mdate,
                            'check_date_2'=>$thirdrl_mdate,

                        );

                        $recruiter_ageing_result = $this->corepo->recruiter_ageing_details_dr( $recruiter_ageing_filter );

                        if(count($recruiter_ageing_result) ==0){

                            if($currentDate >=$forthrl_mdate){
                                // check for fourth day
                                $recruiter_ageing_filter = array(
                                    'rfh_no'=>$row->rfh_no,
                                    'hepl_recruitment_ref_number'=>$row->hepl_recruitment_ref_number,
                                    'check_date_1'=>$orl_mdate,
                                    'check_date_2'=>$forthrl_mdate,
                                );
                                $recruiter_ageing_result_fd = $this->corepo->recruiter_ageing_details_dr( $recruiter_ageing_filter );

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


            })
            ->addColumn('recruiters', function($row) {
                $get_recruiters = $this->corepo->get_assigned_recruiters( $row->hepl_recruitment_ref_number );

                $recruiters_name ='';
                if(count($get_recruiters) !=0){
                    foreach ($get_recruiters as $key => $gr_value) {
                        if($gr_value->name !=''){
                            $recruiters_name .= "> ".$gr_value->name.'<br/>';
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

                $input_details_cc = array(
                    'hepl_recruitment_ref_number'=>$row->hepl_recruitment_ref_number,

                );

                $cv_count = $this->corepo->get_cv_count_dr( $input_details_cc );

                return $cv_count;
            })
            ->addColumn('current_status', function($row) {

                $input_details_cc = array(
                    'hepl_recruitment_ref_number'=>$row->hepl_recruitment_ref_number,
                );

                $get_current_status = $this->corepo->get_current_status_rr( $input_details_cc );

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
            ->addColumn('last_updated_at', function($row) {

                $input_details_cc = array(
                    'hepl_recruitment_ref_number'=>$row->hepl_recruitment_ref_number,
                );

                $get_last_updated_at = $this->corepo->get_current_status_rr( $input_details_cc );

                $last_updated ='';
                if(count($get_last_updated_at) !=0){
                    foreach ($get_last_updated_at as $key => $gcs_value) {
                        if($gcs_value->updated_at !=''){
                            $last_updated .=  date("d-m-Y", strtotime($gcs_value->updated_at));
                        }
                    }
                }

                return $last_updated;
            })
            ->addColumn('tat_process', function($row) {
                $from = strtotime($row->open_date);

                $today = time();
                $difference = $today - $from;
                $difference_days = floor($difference / 86400);  // (60 * 60 * 24)

                $ageing = $difference_days;

                $ageing_end = 15;


                if($ageing >= $ageing_end){

                    return "show_tat_highlight";

                }
                else{

                    return "hide_tat_highlight";
                }


            })
            ->addColumn('open_date', function($row) {

                $originalDate = $row->open_date;
                $newDate = date("d-m-Y", strtotime($originalDate));

                return $newDate;
            })

            ->rawColumns(['open_date','tat_process','profile_ageing_status','position_ageing','recruiters','interviewer','cv_count','current_status','profile_ageing','last_updated_at'])
            ->make(true);
        }
        $data['table'] = $this->get_ageing_report();
        return view('daily_report')->$data;
    }

    public function get_raisedby_list(){
        $get_raisedby_list_result = $this->corepo->get_raisedby_list(  );

        return $get_raisedby_list_result;
    }

    public function process_candidate_delete(Request $req){
        $input_details = array(
            'cdID'=>$req->input('candidate_id'),
        );

        $process_candidate_delete_result = $this->corepo->process_candidate_delete( $input_details );

        $response = 'success';
        return response()->json( ['response' => $response] );
    }

    public function get_candidate_details_ed(Request $req){

        $input_details = array(
            'cdID'=>$req->input('candidate_id'),
        );

        $get_candidate_details_result = $this->corepo->get_candidate_details_ed( $input_details );

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

        $process_candidate_edit_result = $this->corepo->process_candidate_edit( $input_details );

        $response = 'success';
        return response()->json( ['response' => $response] );
    }

    public function update_closed_salary_bc(Request $req){

        $input_details = array(
            'closed_salary'=>$req->input('current_closed_salary'),
            'rfh_no'=>$req->input('rfh_ctc'),
            'cdID'=>$req->input('cid_ctc'),
        );

        $update_closed_salary_bc_result = $this->corepo->update_closed_salary_bc( $input_details );

        $response = 'success';
        return response()->json( ['response' => $response] );

    }

    public function offers_bc(){
        return view('offers_bc');

    }
    public function get_offer_list_bc_apo(Request $request){
        if ($request->ajax()) {

            // get all data
                $input_details = array(
                    'leader_status'=>"2",
                    'payroll_status' =>"3",
                    'offer_rel_status' =>"0",
                    'offer_rel_status_or' =>"1",
                );

                $get_candidate_profile_result = $this->corepo->get_approved_offers( $input_details );


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


                        $btn = '<a href="'.asset('public/'.$row->offer_letter_filename).'" target="_blank"><span class="badge bg-primary" id="" title="Preview Offer Letter"><i class="bi bi-eye"></i></span></a>';

                        // $btn .= ' <a href="edit_ctc_oat?cdID='.$row->cdID.'&rfh_no='.$row->rfh_no.'" target="_blank"><span style="margin-top:2px;" class="badge bg-dark" id="" title="Edit Offer Letter"><i class="bi bi-pencil"></i></span><a>';


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
                ->addColumn('po_type',function($row){

                    if($row->po_type =='po'){
                        $btn ='';

                        $btn .= '<div class="dropdown dropdown-color-icon">';
                        $btn .= '<button class="btn btn-success btn-sm btn-smn dropdown-toggle" type="button" id="dropdownMenuButtonEmoji" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 0.01rem 0.4rem !important;">';
                            $btn .= '<span class="me-50"></span>PO';
                        $btn .= '</button>';

                        $btn .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuButtonEmoji" style="box-shadow: 0 0 30px rgb(0 0 0 / 40%) !important;">';
                            if($row->po_letter_filename !=''){
                                $btn .= '<a class="dropdown-item" href="../po_letter/'.$row->cdID.'/'.$row->po_letter_filename.'"  style="border:unset !important;" target="_blank"><i class="bi bi-eye-fill"></i> Preview PO Input</a>';
                            }

                            if($row->client_po_attach !=''){
                                $btn .= '<div class="dropdown-divider"></div>';
                                $btn .= '<a class="dropdown-item" href="../po_attachments/'.$row->cdID.'/'.$row->client_po_attach.'"  style="border:unset !important;" target="_blank"><i class="bi bi-eye-fill"></i> Preview Client PO </a>';
                            }
                        $btn .= '</div>';
                    $btn .= '</div>';

                    }elseif($row->po_type =='non_po'){
                        $btn = '<span class="badge bg-danger">NonPO</span>';
                    }else{
                        $btn='';
                    }

                    return $btn;
                })
                ->addColumn('finance_status', function($row) {

                    if($row->po_type =='po'){
                        if($row->po_finance_status == 0){
                            $btn = '<span class="badge bg-purple">Not sent</span>';
                        }elseif($row->po_finance_status == 1){
                            $btn = '';
                            $btn .= '<div class="dropdown dropdown-color-icon">';
                                $btn .= '<button class="btn btn-vr btn-sm btn-smn dropdown-toggle" type="button" id="dropdownMenuButtonEmoji" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 0.01rem 0.4rem !important;">';
                                    $btn .= '<span class="me-50"></span>Inprogress';
                                $btn .= '</button>';

                                $btn .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuButtonEmoji" style="box-shadow: 0 0 30px rgb(0 0 0 / 40%) !important;">';

                                    if($row->fn_po_remark !=''){
                                        $btn .= '<a class="dropdown-item" href="#" onclick="show_fn_remark('."'".$row->cdID."'".');" style="border:unset !important;"><i class="bi bi-file-earmark-medical-fill"></i> Remark</a>';
                                        $btn .='<input type="hidden" name="fn_remark_'.$row->cdID.'" id="fn_remark_'.$row->cdID.'" value="'.$row->fn_po_remark.'">';
                                    }
                                    if($row->fn_po_attach !=''){
                                        $btn .= '<div class="dropdown-divider"></div>';
                                        $btn .= '<a class="dropdown-item" href="../fn_po_attach/'.$row->cdID.'/'.$row->fn_po_attach.'"  style="border:unset !important;" target="_blank"><i class="bi bi-eye-fill"></i> Preview Finance Attach</a>';
                                    }

                                $btn .= '</div>';
                            $btn .= '</div>';
                        }
                        elseif($row->po_finance_status == 2){
                            $btn = '';
                            $btn .= '<div class="dropdown dropdown-color-icon">';
                                $btn .= '<button class="btn btn-success btn-sm btn-smn dropdown-toggle" type="button" id="dropdownMenuButtonEmoji" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 0.01rem 0.4rem !important;">';
                                    $btn .= '<span class="me-50"></span>Approved';
                                $btn .= '</button>';

                                $btn .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuButtonEmoji" style="box-shadow: 0 0 30px rgb(0 0 0 / 40%) !important;">';

                                    if($row->fn_po_remark !=''){
                                        $btn .= '<a class="dropdown-item" href="#" onclick="show_fn_remark('."'".$row->cdID."'".');" style="border:unset !important;"><i class="bi bi-file-earmark-medical-fill"></i> Remark</a>';
                                        $btn .='<input type="hidden" name="fn_remark_'.$row->cdID.'" id="fn_remark_'.$row->cdID.'" value="'.$row->fn_po_remark.'">';
                                        $btn .= '<div class="dropdown-divider"></div>';

                                    }
                                    if($row->fn_po_attach !=''){
                                        $btn .= '<a class="dropdown-item" href="../fn_po_attach/'.$row->cdID.'/'.$row->fn_po_attach.'"  style="border:unset !important;" target="_blank"><i class="bi bi-eye-fill"></i> Preview Finance Attach</a>';
                                    }

                                $btn .= '</div>';
                            $btn .= '</div>';
                        }
                        elseif($row->po_finance_status == 3){

                            $btn = '';

                            $btn .= '<div class="dropdown dropdown-color-icon">';
                                $btn .= '<button class="btn btn-danger btn-sm btn-smn dropdown-toggle" type="button" id="dropdownMenuButtonEmoji" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 0.01rem 0.4rem !important;">';
                                    $btn .= '<span class="me-50"></span>Rejected';
                                $btn .= '</button>';

                                $btn .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuButtonEmoji" style="box-shadow: 0 0 30px rgb(0 0 0 / 40%) !important;">';

                                    if($row->fn_po_remark !=''){
                                        $btn .= '<a class="dropdown-item" href="#" onclick="show_fn_remark('."'".$row->cdID."'".');" style="border:unset !important;"><i class="bi bi-file-earmark-medical-fill"></i> Remark</a>';
                                        $btn .='<input type="hidden" name="fn_remark_'.$row->cdID.'" id="fn_remark_'.$row->cdID.'" value="'.$row->fn_po_remark.'">';
                                    }
                                    if($row->fn_po_attach !=''){
                                        $btn .= '<div class="dropdown-divider"></div>';
                                        $btn .= '<a class="dropdown-item" href="../fn_po_attach/'.$row->cdID.'/'.$row->fn_po_attach.'"  style="border:unset !important;" target="_blank"><i class="bi bi-eye-fill"></i> Preview Finance Attach</a>';
                                    }

                                $btn .= '</div>';
                            $btn .= '</div>';

                        }
                        else{
                            $btn = '';
                        }
                    }
                    else{
                        $btn = '';
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
                        // $btn = '<span class="badge bg-success">Approved</span>';
                        $btn = '';
                            $btn .= '<div class="dropdown dropdown-color-icon">';
                                $btn .= '<button class="btn btn-success btn-sm btn-smn dropdown-toggle" type="button" id="dropdownMenuButtonEmoji" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 0.01rem 0.4rem !important;">';
                                    $btn .= '<span class="me-50"></span>Approved';
                                $btn .= '</button>';

                                $btn .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuButtonEmoji" style="box-shadow: 0 0 30px rgb(0 0 0 / 40%) !important;">';

                                    if($row->ld_reject_remark !=''){
                                        $btn .= '<a class="dropdown-item" href="#" onclick="show_ld_remark('."'".$row->cdID."'".');" style="border:unset !important;"><i class="bi bi-file-earmark-medical-fill"></i> Remark</a>';
                                        $btn .='<input type="hidden" name="ld_reject_remark_'.$row->cdID.'" id="ld_reject_remark_'.$row->cdID.'" value="'.$row->ld_reject_remark.'">';
                                    }

                                $btn .= '</div>';
                            $btn .= '</div>';
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
                ->addColumn('offer_release',function($row){

                    if($row->offer_rel_status ==0 && $row->payroll_status == 3 && $row->leader_status == 2){
                        $btn = '<span  class="badge bg-info">Not Sent</span>';

                    }
                    elseif($row->offer_rel_status ==1){
                        $btn = '<span  class="badge bg-info">Sent</span>';

                    }
                    else{
                        $btn = '';

                    }
                return $btn;
                })

                ->rawColumns(['leader_status','finance_status','offer_release','history','c_doc_status','payroll_status','offer_letter_preview','ageing','po_type'])
                ->make(true);

        }
    }

    public function get_offer_accepted_for_bc(Request $request){

        if ($request->ajax()) {

            // get all data

                $input_details = array(
                    'offer_rel_status'=>"2",
                );

                $get_candidate_profile_result = $this->corepo->get_offer_accepted_for_bc_dt( $input_details );


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


                ->addColumn('offer_letter_preview',function($row){

                        $btn = '<a href="'.asset('public/'.$row->offer_letter_filename).'" target="_blank"><span class="badge bg-primary" id="" title="Preview Offer Letter"><i class="bi bi-eye"></i></span></a>';

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


                ->rawColumns(['history','c_doc_status','payroll_status','offer_letter_preview','po_type'])
                ->make(true);

        }

    }

    public function get_offer_rejected_for_bc(Request $request){

        if ($request->ajax()) {

            // get all data

                $input_details = array(
                    'offer_rel_status'=>"3",
                );

                $get_candidate_profile_result = $this->corepo->get_offer_accepted_for_bc_dt( $input_details );


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

                ->rawColumns(['history','c_doc_status','payroll_status','offer_letter_preview','po_type'])
                ->make(true);

        }

    }

    public function document_collection_bc(Request $request){

        if ($request->ajax()) {

                $input_details = array(
                    'doc_status'=>"1",
                );

                $get_candidate_profile_result = $this->corepo->get_candidate_profile_dc_bc( $input_details );


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

                   // $today = time();
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

                ->addColumn('action',function($row){

                    $btn = '<a href="cd_preview_bc?cdID='.$row->cdID.'" target="_blank"><span class="badge bg-primary" id="btnAssign" title="Candidate Profile"><i class="bi bi-person-lines-fill"></i></span></a>';

                    return $btn;
                })
                ->addColumn('offer_letter_preview',function($row){

                    if($row->payroll_status !=0){
                        $btn = '<a href="'.asset('public/'.$row->offer_letter_filename).'" target="_blank"><span class="badge bg-primary" id="" title="Preview Offer Letter"><i class="bi bi-eye"></i></span></a>';

                    }
                   else{
                       $btn ='';
                   }
                    return $btn;
                })
                ->addColumn('finance_status', function($row) {

                    if($row->po_type =='po'){
                        if($row->po_finance_status == 0){
                            $btn = '<span class="badge bg-purple">Not sent</span>';
                        }elseif($row->po_finance_status == 1){
                            $btn = '';
                            $btn .= '<div class="dropdown dropdown-color-icon">';
                                $btn .= '<button class="btn btn-vr btn-sm btn-smn dropdown-toggle" type="button" id="dropdownMenuButtonEmoji" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 0.01rem 0.4rem !important;">';
                                    $btn .= '<span class="me-50"></span>Inprogress';
                                $btn .= '</button>';

                                $btn .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuButtonEmoji" style="box-shadow: 0 0 30px rgb(0 0 0 / 40%) !important;">';

                                    if($row->fn_po_remark !=''){
                                        $btn .= '<a class="dropdown-item" href="#" onclick="show_fn_remark('."'".$row->cdID."'".');" style="border:unset !important;"><i class="bi bi-file-earmark-medical-fill"></i> Remark</a>';
                                        $btn .='<input type="hidden" name="fn_remark_'.$row->cdID.'" id="fn_remark_'.$row->cdID.'" value="'.$row->fn_po_remark.'">';
                                    }
                                    if($row->fn_po_attach !=''){
                                        $btn .= '<div class="dropdown-divider"></div>';
                                        $btn .= '<a class="dropdown-item" href="../fn_po_attach/'.$row->cdID.'/'.$row->fn_po_attach.'"  style="border:unset !important;" target="_blank"><i class="bi bi-eye-fill"></i> Preview Finance Attach</a>';
                                    }

                                $btn .= '</div>';
                            $btn .= '</div>';
                        }
                        elseif($row->po_finance_status == 2){
                            $btn = '';
                            $btn .= '<div class="dropdown dropdown-color-icon">';
                                $btn .= '<button class="btn btn-success btn-sm btn-smn dropdown-toggle" type="button" id="dropdownMenuButtonEmoji" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 0.01rem 0.4rem !important;">';
                                    $btn .= '<span class="me-50"></span>Approved';
                                $btn .= '</button>';

                                $btn .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuButtonEmoji" style="box-shadow: 0 0 30px rgb(0 0 0 / 40%) !important;">';

                                    if($row->fn_po_remark !=''){
                                        $btn .= '<a class="dropdown-item" href="#" onclick="show_fn_remark('."'".$row->cdID."'".');" style="border:unset !important;"><i class="bi bi-file-earmark-medical-fill"></i> Remark</a>';
                                        $btn .='<input type="hidden" name="fn_remark_'.$row->cdID.'" id="fn_remark_'.$row->cdID.'" value="'.$row->fn_po_remark.'">';
                                        $btn .= '<div class="dropdown-divider"></div>';

                                    }
                                    if($row->fn_po_attach !=''){
                                        $btn .= '<a class="dropdown-item" href="../fn_po_attach/'.$row->cdID.'/'.$row->fn_po_attach.'"  style="border:unset !important;" target="_blank"><i class="bi bi-eye-fill"></i> Preview Finance Attach</a>';
                                    }

                                $btn .= '</div>';
                            $btn .= '</div>';
                        }
                        elseif($row->po_finance_status == 3){

                            $btn = '';

                            $btn .= '<div class="dropdown dropdown-color-icon">';
                                $btn .= '<button class="btn btn-danger btn-sm btn-smn dropdown-toggle" type="button" id="dropdownMenuButtonEmoji" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 0.01rem 0.4rem !important;">';
                                    $btn .= '<span class="me-50"></span>Rejected';
                                $btn .= '</button>';

                                $btn .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuButtonEmoji" style="box-shadow: 0 0 30px rgb(0 0 0 / 40%) !important;">';

                                    if($row->fn_po_remark !=''){
                                        $btn .= '<a class="dropdown-item" href="#" onclick="show_fn_remark('."'".$row->cdID."'".');" style="border:unset !important;"><i class="bi bi-file-earmark-medical-fill"></i> Remark</a>';
                                        $btn .='<input type="hidden" name="fn_remark_'.$row->cdID.'" id="fn_remark_'.$row->cdID.'" value="'.$row->fn_po_remark.'">';
                                    }
                                    if($row->fn_po_attach !=''){
                                        $btn .= '<div class="dropdown-divider"></div>';
                                        $btn .= '<a class="dropdown-item" href="../fn_po_attach/'.$row->cdID.'/'.$row->fn_po_attach.'"  style="border:unset !important;" target="_blank"><i class="bi bi-eye-fill"></i> Preview Finance Attach</a>';
                                    }

                                $btn .= '</div>';
                            $btn .= '</div>';

                        }
                        else{
                            $btn = '';
                        }
                    }
                    else{
                        $btn = '';
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
                        // $btn = '<span class="badge bg-success">Approved</span>';
                        $btn = '';
                            $btn .= '<div class="dropdown dropdown-color-icon">';
                                $btn .= '<button class="btn btn-success btn-sm btn-smn dropdown-toggle" type="button" id="dropdownMenuButtonEmoji" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 0.01rem 0.4rem !important;">';
                                    $btn .= '<span class="me-50"></span>Approved';
                                $btn .= '</button>';

                                $btn .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuButtonEmoji" style="box-shadow: 0 0 30px rgb(0 0 0 / 40%) !important;">';

                                    if($row->ld_reject_remark !=''){
                                        $btn .= '<a class="dropdown-item" href="#" onclick="show_ld_remark('."'".$row->cdID."'".');" style="border:unset !important;"><i class="bi bi-file-earmark-medical-fill"></i> Remark</a>';
                                        $btn .='<input type="hidden" name="ld_reject_remark_'.$row->cdID.'" id="ld_reject_remark_'.$row->cdID.'" value="'.$row->ld_reject_remark.'">';
                                    }

                                $btn .= '</div>';
                            $btn .= '</div>';
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
                ->rawColumns(['finance_status','leader_status','history','c_doc_status','payroll_status','action','ageing','offer_letter_preview'])
                ->make(true);

        }
        return view('document_collection_bc');
    }

    public function cd_preview_bc(){
        return view('cd_preview_bc');

    }
    public function get_candidate_preview_details_bc(Request $req){

        $input_details = array(
            'cdID'=>$req->input('cdID'),
        );

        $get_candidate_details_result = $this->corepo->get_candidate_details_ed( $input_details );

        $get_candidate_edu_result = $this->corepo->get_candidate_edu_details( $input_details );

        $get_candidate_exp_result = $this->corepo->get_candidate_exp_details( $input_details );

        $get_candidate_benefits_result = $this->corepo->get_candidate_benefits_details( $input_details );

        return response()->json( [
            'candidate_basic_details' => $get_candidate_details_result,
            'candidate_education' => $get_candidate_edu_result,
            'candidate_experience' => $get_candidate_exp_result,
            'candidate_benefits' => $get_candidate_benefits_result
            ] );

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
    public function score_card(){
        return view('score_card');
    }
    public function get_score_card(Request $request){
        if ($request->ajax()) {

            // get all data
                // $input_details = array(
                //     'leader_status'=>"2",
                //     'payroll_status' =>"3",
                //     'offer_rel_status' =>"0",
                //     'offer_rel_status_or' =>"1",
                // );

                $get_recruiter_details = $this->corepo->get_user_recruiter();


            return Datatables::of($get_recruiter_details)
                ->addIndexColumn()

                ->addColumn('recruiter', function($row) {
                    $btn = $row->name;
                    return  $btn;
                })
                ->addColumn('position', function($row) {
                   // $recruiter_id = $row->empID;
                   $data =  array(
                    'recruiter' =>$row->empID,
                    'date' => date('Y-m-d'),
                );
                    $get_position  = $this->corepo->get_position_working_rfh($data);

                    $btn =  count($get_position) ;
                    return  $btn;
                })
                ->addColumn('interviews', function($row) {
                    $recruiter_id = $row->empID;
                    $get_interviews  = $this->corepo->get_recruiter_interviews($recruiter_id);
                    $btn = $get_interviews;
                    return  $btn;
                })
                ->addColumn('offers', function($row) {
                    $recruiter_id = $row->empID;
                    $get_offers  = $this->corepo->get_recruiter_offers($recruiter_id);
                    $btn = $get_offers;
                    return  $btn;
                })
                ->addColumn('10am', function($row) {
                    // $date = date('Y-M-d');
                    // $from_time = "10:00";
                    // $to_time = "11:00";
                    $data =  array(
                        'recruiter' =>$row->empID,
                        'date' => date('Y-m-d'),
                        'from_time' => "06:00:00",
                        'to_time' => "11:00:00",
                    );

                    $get_time_data = $this->corepo->get_time_data( $data );
                    $btn = $get_time_data;
                    return  $btn;

                })
                ->addColumn('11am', function($row) {
                    $data =  array(
                        'recruiter' =>$row->empID,
                        'date' => date('Y-m-d'),
                        'from_time' => "11:00:00",
                        'to_time' => "12:00:00",
                    );
                    $get_time_data = $this->corepo->get_time_data( $data );
                    $btn = $get_time_data;
                    return  $btn;
                })
                ->addColumn('12pm', function($row) {
                    $data =  array(
                        'recruiter' =>$row->empID,
                        'date' => date('Y-m-d'),
                        'from_time' => "12:00:00",
                        'to_time' => "13:00:00",
                    );
                    $get_time_data = $this->corepo->get_time_data( $data );
                    $btn = $get_time_data;
                    return  $btn;
                })
                ->addColumn('1pm', function($row) {
                    $data =  array(
                        'recruiter' =>$row->empID,
                        'date' => date('Y-m-d'),
                        'from_time' => "13:00:00",
                        'to_time' => "15:00:00",
                    );
                    $get_time_data = $this->corepo->get_time_data( $data );
                    $btn = $get_time_data;
                    return  $btn;
                })
                // ->addColumn('2pm', function($row) {
                //     $data =  array(
                //         'recruiter' =>$row->empID,
                //         'date' => date('Y-m-d'),
                //         'from_time' => "14:00:00",
                //         'to_time' => "15:00:00",
                //     );
                //     $get_time_data = $this->corepo->get_time_data( $data );
                //     $btn = $get_time_data;
                //     return  $btn;
                // })
                ->addColumn('3pm', function($row) {
                    $data =  array(
                        'recruiter' =>$row->empID,
                        'date' => date('Y-m-d'),
                        'from_time' => "15:00:00",
                        'to_time' => "16:00:00",
                    );
                    $get_time_data = $this->corepo->get_time_data( $data );
                    $btn = $get_time_data;
                    return  $btn;
                })
                ->addColumn('4pm', function($row) {
                    $data =  array(
                        'recruiter' =>$row->empID,
                        'date' => date('Y-m-d'),
                        'from_time' => "16:00:00",
                        'to_time' => "17:00:00",
                    );
                    $get_time_data = $this->corepo->get_time_data( $data );
                    $btn = $get_time_data;
                    return  $btn;
                })
                ->addColumn('5pm', function($row) {
                    $data =  array(
                        'recruiter' =>$row->empID,
                        'date' => date('Y-m-d'),
                        'from_time' => "17:00:00",
                        'to_time' => "18:00:00",
                    );
                    $get_time_data = $this->corepo->get_time_data( $data );
                    $btn = $get_time_data;
                    return  $btn;
                })
                ->addColumn('6pm', function($row) {
                    $data =  array(
                        'recruiter' =>$row->empID,
                        'date' => date('Y-m-d'),
                        'from_time' => "18:00:00",
                        'to_time' => "24:00:00",
                    );
                    $get_time_data = $this->corepo->get_time_data( $data );
                    $btn = $get_time_data;
                    return  $btn;
                })
                ->addColumn('total', function($row) {
                    $data =  array(
                        'recruiter' =>$row->empID,
                        'date' => date('Y-m-d'),
                        'from_time' => "01:00:00",
                        'to_time' => "24:00:00",
                    );
                    $get_time_data = $this->corepo->get_time_data( $data );
                    $time = date('d-m-y H:i:s');
                    $time = date("H:i:s", strtotime($time));
                   // if($time == "")

                    $btn = $get_time_data;

                    return  $btn;
                })
                ->rawColumns(['recruiter','position','interviews','offers','10am','11am','12pm','1pm','3pm','4pm','5pm','6pm','Total'])
                ->make(true);

        }
        return view('score_card');
    }
    public function get_score_card_filter(Request $request){
        if ($request->ajax()) {

            // get a data
                $data = array(
                    'recruiter'=>$request->input('recruit'),
                    'from_date' =>$request->input('from_date'),
                    'to_date' =>$request->input('to_date'),

                );
                $recruiter = $request->input('recruit');
                $from_date = $request->input('from_date');
                $to_date = $request->input('to_date');
                $team = $request->input('team');

if($recruiter == "" && $team == ""){
                $get_recruiter_details_id = $this->corepo->get_user_recruiter();
}
else if($team != "" && $recruiter == ""){
    $get_recruiter_details_id = $this->corepo->get_team_recruiter( $team );
}
else{
    $get_recruiter_details_id = $this->corepo->get_user_recruiter_id($data);
}
            return Datatables::of($get_recruiter_details_id)
                ->addIndexColumn()

                ->addColumn('recruiter', function($row) use($recruiter) {

                    $btn = $row->name;
                     return  $btn;
                })
                ->addColumn('position', function($row) use($from_date,$to_date,$recruiter){


                    $data =  array(
                        'recruiter' =>$row->empID,
                        'from_date' =>$from_date,
                        'to_date' => $to_date,
                        'today_date' => date('Y-m-d'),
                    );
                    $get_position  = $this->corepo->get_position_working_filter( $data );
                    $btn =  count($get_position );
                    return  $btn;
                })
                ->addColumn('interviews', function($row) use($from_date,$to_date,$recruiter) {

                    $data =  array(
                        'recruiter' =>$row->empID,
                        'from_date' =>$from_date,
                        'to_date' => $to_date,
                        'today_date' => date('Y-m-d'),
                    );
                    $get_interviews  = $this->corepo->get_recruiter_interviews_filter( $data );
                    $btn = $get_interviews;
                    return  $btn;
                })
                ->addColumn('offers', function($row) use($from_date,$to_date,$recruiter) {

                    $data =  array(
                        'recruiter' =>$row->empID,
                        'from_date' =>$from_date,
                        'to_date' => $to_date,
                        'today_date' => date('Y-m-d'),
                    );
                    $get_offers  = $this->corepo->get_recruiter_offers_filter( $data );
                    $btn = $get_offers;
                    return  $btn;
                })
                ->addColumn('10am', function($row)  use($from_date,$to_date,$recruiter) {

                    $data =  array(
                        'recruiter' =>$row->empID,
                        'from_date' =>$from_date,
                        'to_date' => $to_date,
                        'from_time' => "06:00:00",
                        'to_time' => "11:00:00",
                        'today_date' => date('Y-m-d'),
                    );

                    $get_time_data = $this->corepo->get_time_data_filter( $data );
                    $btn = $get_time_data;
                    return  $btn;

                })
                ->addColumn('11am', function($row) use($from_date,$to_date,$recruiter) {

                    $data =  array(
                        'recruiter' =>$row->empID,
                        'from_date' =>$from_date,
                        'to_date' => $to_date,
                        'from_time' => "11:00:00",
                        'to_time' => "12:00:00",
                        'today_date' => date('Y-m-d'),
                    );
                    $get_time_data = $this->corepo->get_time_data_filter( $data );
                    $btn = $get_time_data;
                    return  $btn;
                })


                ->addColumn('12pm', function($row) use($from_date ,$to_date,$recruiter) {

                    $data =  array(
                        'recruiter' =>$row->empID,
                        'from_date' =>$from_date,
                        'to_date' => $to_date,

                        'from_time' => "12:00:00",
                        'to_time' => "13:00:00",
                        'today_date' => date('Y-m-d'),
                    );
                    $get_time_data = $this->corepo->get_time_data_filter( $data );
                    $btn = $get_time_data;
                    return  $btn;
                })
                ->addColumn('1pm', function($row) use($from_date,$to_date,$recruiter) {

                    $data =  array(
                        'recruiter' =>$row->empID,
                        'from_date' =>$from_date,
                        'to_date' => $to_date,
                        'from_time' => "13:00:00",
                        'to_time' => "15:00:00",
                        'today_date' => date('Y-m-d'),
                    );
                    $get_time_data = $this->corepo->get_time_data_filter( $data );
                    $btn = $get_time_data;
                    return  $btn;
                })
                // ->addColumn('2pm', function($row) use($from_date,$to_date) {
                //     $data =  array(
                //         'recruiter' =>$row->empID,
                //         'from_date' =>$from_date,
                //         'to_date' => $to_date,
                //         'from_time' => "14:00:00",
                //         'to_time' => "15:00:00",
                //     );
                //     $get_time_data = $this->corepo->get_time_data_filter( $data );
                //     $btn = $get_time_data;
                //     return  $btn;
                // })
                ->addColumn('3pm', function($row) use($from_date,$to_date,$recruiter){

                    $data =  array(
                        'recruiter' =>$row->empID,
                        'from_date' =>$from_date,
                        'to_date' => $to_date,
                        'from_time' => "15:00:00",
                        'to_time' => "16:00:00",
                        'today_date' => date('Y-m-d'),
                    );
                    $get_time_data = $this->corepo->get_time_data_filter( $data );
                    $btn = $get_time_data;
                    return  $btn;
                })
                ->addColumn('4pm', function($row) use($from_date,$to_date,$recruiter) {

                    $data =  array(
                        'recruiter' =>$row->empID,
                        'from_date' =>$from_date,
                        'to_date' => $to_date,
                        'from_time' => "16:00:00",
                        'to_time' => "17:00:00",
                        'today_date' => date('Y-m-d'),
                    );
                    $get_time_data = $this->corepo->get_time_data_filter( $data );
                    $btn = $get_time_data;
                    return  $btn;
                })
                ->addColumn('5pm', function($row) use($from_date,$to_date,$recruiter) {

                    $data =  array(
                        'recruiter' =>$row->empID,
                        'from_date' =>$from_date,
                        'to_date' => $to_date,
                        'from_time' => "17:00:00",
                        'to_time' => "18:00:00",
                        'today_date' => date('Y-m-d'),
                    );
                    $get_time_data = $this->corepo->get_time_data_filter( $data );
                    $btn = $get_time_data;
                    return  $btn;
                })
                ->addColumn('6pm', function($row) use($from_date,$to_date,$recruiter) {

                    $data =  array(
                        'recruiter' =>$row->empID,
                        'from_date' =>$from_date,
                        'to_date' => $to_date,
                        'from_time' => "18:00:00",
                        'to_time' => "24:00:00",
                        'today_date' => date('Y-m-d'),
                    );
                    $get_time_data = $this->corepo->get_time_data_filter( $data );
                    $btn = $get_time_data;
                    return  $btn;
                })
                ->addColumn('total', function($row) use($from_date,$to_date,$recruiter){
                // $time = time();
                // $time = date("H:i:s", strtotime($time));
                        $data =  array(
                            'recruiter' =>$row->empID,
                            'from_date' =>$from_date,
                            'to_date' => $to_date,
                            'from_time' => "01:00:00",
                            'to_time' => "24:00:00",
                            'today_date' => date('Y-m-d'),
                        );


                    $get_time_data = $this->corepo->get_time_data_filter( $data );
                    $btn = $get_time_data;
                    return   $btn ;
                })
                ->rawColumns(['recruiter','position','interviews','offers','10am','11am','12pm','1pm','3pm','4pm','5pm','6pm','Total'])
                ->make(true);

        }
        return view('score_card');
    }
public function recruiter_daily_report(){
    return view('recruiter_daily_report');
}

    public function get_recruiter_daily_report(Request $request){
        if ($request->ajax()) {

            // get all data
                // $input_details = array(
                //     'leader_status'=>"2",
                //     'payroll_status' =>"3",
                //     'offer_rel_status' =>"0",
                //     'offer_rel_status_or' =>"1",
                // );

                $get_recruiter_details = $this->corepo->get_user_recruiter();


            return Datatables::of($get_recruiter_details)
                ->addIndexColumn()

                ->addColumn('recruiter', function($row) {
                    $btn = $row->name;
                    return  $btn;
                })
                ->addColumn('position', function($row) {
                    $data =  array(
                            'recruiter' =>$row->empID,
                            'date' => date('Y-m-d'),
                        );
                    $get_position  = $this->corepo->get_position_working_rfh($data);
                    $btn = "";
                   if($get_position != "[]"){
                   foreach($get_position as $position){

                       $btn .= '<p style="width:110%;border-bottom:1px solid;">'.$position->position_title.'</p>';
                   }
                   }
                   else{
                       $btn .= "0";
                   }

                    return  $btn;
                })
                ->addColumn('rfh_no', function($row) {
                    $data =  array(
                        'recruiter' =>$row->empID,
                        'date' => date('Y-m-d'),
                    );
                $get_position  = $this->corepo->get_position_working_rfh($data);
                $btn = "";
               if($get_position != "[]"){
               foreach($get_position as $position){
                   $btn .= '<p style="width:115%;border-bottom:1px solid;">'.$position->hepl_recruitment_ref_number.'</p>';
               }
               }
               else{
                   $btn .= "0";
               }

                return  $btn;
                })
                ->addColumn('cv_per_position', function($row) {
                    $data =  array(
                        'recruiter' =>$row->empID,
                        'date' => date('Y-m-d'),
                    );
                $get_position  = $this->corepo->get_position_working_rfh($data);
                $btn = "";
               if($get_position != "[]"){
               foreach($get_position as $position){
                $data =  array(
                    'recruiter' =>$row->empID,
                    'date' => date('Y-m-d'),
                    'hepl_rfh_no' => $position->hepl_recruitment_ref_number,
                );
                $get_cv_count  = $this->corepo->get_position_cv_count($data);
                   $btn .= '<p style="width:107%;border-bottom:1px solid;">'.$get_cv_count.'</p>';
               }
               }
               else{
                   $btn .= "0";
               }

                return  $btn;
                })
                ->addColumn('total_cvs', function($row) {
                  $recruiter_id = $row->empID;
                  $get_total_csv = $this->corepo->get_total_csv($recruiter_id);
                       $btn = $get_total_csv;
                       return  $btn;
                })
                ->addColumn('offer_release_status', function($row) {
                    $recruiter_id = $row->empID;
                    $get_offers  = $this->corepo->get_recruiter_offers($recruiter_id);
                    $get_offers_position  = $this->corepo->get_offer_release_position($recruiter_id);
                    //$btn = $get_offers.'<br>';
                    $btn="";
                    if($get_offers_position !="[]"){
                $count= 0;
                    foreach ($get_offers_position as $p){
                    $count = $count+1;
                        $btn .= '<p style="width:100%;border-bottom:1px solid;"> '.$p->position_title.'-'.$p->hepl_recruitment_ref_number.'</p>';
                    }
                }
                else{
                    $btn .= "0";
                }
                    return $btn;
             })

                ->rawColumns(['recruiter','position','rfh_no','cv_per_position','total_cvs','offer_release_status'])
                ->make(true);

        }
        return view('recruiter_daily_report');
    }


    public function get_recruiter_daily_report_filter(Request $request){
        if ($request->ajax()) {

            $data = array(
                'recruiter'=>$request->input('recruit'),
                'from_date' =>$request->input('from_date'),
                'to_date' =>$request->input('to_date'),

            );
            $recruiter = $request->input('recruit');
            $from_date = $request->input('from_date');
            $to_date = $request->input('to_date');

            $team = $request->input('team');

if($recruiter == "" && $team == ""){
                $get_recruiter_details_id = $this->corepo->get_user_recruiter();
}
else if($team != "" && $recruiter == ""){
    $get_recruiter_details_id = $this->corepo->get_team_recruiter( $team );
}
else{
    $get_recruiter_details_id = $this->corepo->get_user_recruiter_id($data);
}


            return Datatables::of($get_recruiter_details_id)
                ->addIndexColumn()

                ->addColumn('recruiter', function($row) {
                    $btn = $row->name;
                    return  $btn;
                })
                ->addColumn('position', function($row) use($from_date,$to_date) {
                    $data =  array(
                            'recruiter' =>$row->empID,
                            'from_date' =>$from_date,
                            'to_date' =>$to_date,
                            'today_date' => date('Y-m-d'),
                        );
                    $get_position  = $this->corepo->get_position_working_filter($data);
                    $btn = "";
                   if($get_position != "[]"){
                   foreach($get_position as $position){

                       $btn .= '<p style="width:110%;border-bottom:1px solid;">'.$position->position_title.'</p>';
                     //  $btn .='<td style="line-height:35px; ">'. $position->position_title.'</td><br>';
                   }
                   }
                   else{
                       $btn .= "0";
                   }

                    return  $btn;
                })
                ->addColumn('rfh_no', function($row) use($from_date,$to_date){
                    $data =  array(
                        'recruiter' =>$row->empID,
                        'from_date' =>$from_date,
                        'to_date' =>$to_date,
                        'today_date' =>date('Y-m-d'),
                    );
                $get_position  = $this->corepo->get_position_working_filter($data);
                $btn = "";
               if($get_position != "[]"){
               foreach($get_position as $position){
                   $btn .='<p style="width:115%;border-bottom:1px solid;">'. $position->hepl_recruitment_ref_number.'</p>';
               }
               }
               else{
                   $btn .= "0";
               }

                return  $btn;
                })
                ->addColumn('cv_per_position', function($row) use($from_date,$to_date){
                    $data =  array(
                        'recruiter' =>$row->empID,
                        'from_date' =>$from_date,
                        'to_date' =>$to_date,
                        'today_date' =>date('Y-m-d'),
                    );
                $get_position  = $this->corepo->get_position_working_filter($data);
                $btn = "";
               if($get_position != "[]"){
               foreach($get_position as $position){
                $data =  array(
                    'recruiter' =>$row->empID,
                    'from_date' =>$from_date,
                    'to_date' =>$to_date,
                    'hepl_rfh_no' => $position->hepl_recruitment_ref_number,
                    'today_date' =>date('Y-m-d'),
                );
                $get_cv_count  = $this->corepo->get_position_cv_count_filter($data);
                   $btn .= '<p style="width:107%;border-bottom:1px solid;">'.$get_cv_count.'</p>';
               }
               }
               else{
                   $btn .= "0";
               }

                 return  $btn;
                })
                ->addColumn('total_cvs', function($row) use($from_date,$to_date){
                    $data =  array(
                        'recruiter' =>$row->empID,
                        'from_date' =>$from_date,
                        'to_date' =>$to_date,
                        'today_date' =>date('Y-m-d'),

                    );
                  $get_total_csv = $this->corepo->get_total_csv_filter($data);
                       $btn = $get_total_csv;
                       return  $btn;
                })
                ->addColumn('offer_release_status', function($row) use($from_date,$to_date){
                    $data =  array(
                        'recruiter' =>$row->empID,
                        'from_date' =>$from_date,
                        'to_date' =>$to_date,
                        'today_date' =>date('Y-m-d'),

                    );
                    $get_offers  = $this->corepo->get_recruiter_offers_filter_id($data);
                    $get_offers_position  = $this->corepo->get_offer_release_position_filter($data);
                    //$btn = $get_offers.'<br>';
                    $btn="";
                    if($get_offers_position !="[]"){
                $count= 0;
                    foreach ($get_offers_position as $p){
                    $count = $count+1;
                        $btn .='<p style="width:100%;border-bottom:1px solid;">'.$p->position_title.'-'.$p->hepl_recruitment_ref_number.'</p>';
                    }
                }
                else{
                    $btn .= "0";
                }
                    return $btn;
             })

                ->rawColumns(['recruiter','position','rfh_no','cv_per_position','total_cvs','offer_release_status'])
                ->make(true);

        }
        return view('recruiter_daily_report');
    }
    public function get_recruiter_list_team(Request $request){
        $team = $request->input('team');

        $get_recruiter_list_team = $this->corepo->get_team_recruiter( $team );

        return $get_recruiter_list_team;
    }

    public function get_sc_count(Request $request){
 $data = array(
     "today_date"=>$request->input('p_date'),
     "from_date"=>$request->input('from_date'),
     "to_date"=>$request->input('to_date'),
 );
 $get_recruitment = $this->corepo->get_recruitment_dt( $data );


 $input_details = array(
    'af_from_date'=>$request->input('from_date'),
    'af_to_date'=>$request->input('to_date'),
    'af_position_title'=>'',
    'af_sub_position_title'=>'',
    'af_location'=>'',
    'af_business'=>'',
    'af_teams'=>'HEPL',
    'af_raisedby'=>'',
    'af_interviewer'=>'',
    'af_billable'=>'',
    'af_function'=>'',
    'af_division'=>'',
    'request_status_1'=>"Closed",
    'request_status_2'=>"On Hold",
);
// get all data
$get_open_status = $this->corepo->get_open_position_details_af( $input_details );









 //$get_open_status = $this->corepo->get_avg_open( $data );
 // calculate no. of. days
 $from = $request->input('from_date');
 $to = $request->input('to_date');
 if($from !="" && $to !="" ){
     $f = strtotime($from);
     $t = strtotime($to);
$to_date =$request->input('to_date');

$time  = strtotime($to_date);
$month = date('m',$time);
$year  = date('Y',$time);
//echo $month."and".$year; die;
    // $difference = $t - $f;
    // $difference_days = floor($difference / 86400); // finding no of days
    // $month = "09";
    // $year = "2022";
    $start = mktime(0, 0, 0, $month, 1, $year);
    // End of month
    $end = mktime(0, 0, 0, $month, date('t', $start), $year);
    // Start week
    $start_week = date('W', $start);
    // End week
    $end_week = date('W', $end);

    if ($end_week < $start_week) { // Month wraps
               //year has 52 weeks
               $weeksInYear = 52;
               //but if leap year, it has 53 weeks
               if($year % 4 == 0) {
                   $weeksInYear = 53;
               }
              $no_of_weeks =  (($weeksInYear + $end_week) - $start_week) + 1;
           }
            else{
                $no_of_weeks = ($end_week - $start_week);
            }

//echo $no_of_weeks."</br>";
    $get_open_status = count($get_open_status);

    $get_avg_open = $get_open_status/$no_of_weeks;
   // echo $get_open_status;
    $get_avg_open = round($get_avg_open);

 }
 else{
    $get_open_status = 0;
    $get_avg_open =  $get_open_status;
 }

 //$get_closed_status = $this->corepo->get_avg_closed( $data );
$no_of_recruiter =   $this->corepo->get_user_recruiter_count();
$avg_recruitment_per_recruiter = $get_recruitment/$no_of_recruiter;
$avg_recruitment_per_recruiter = round($avg_recruitment_per_recruiter);

  $bill = array(
    "today_date"=>$request->input('p_date'),
    "from_date"=>$request->input('from_date'),
    "to_date"=>$request->input('to_date'),
    "billing_status"=>"Billable",
  );
  $input_details = array(
    'af_from_date'=>$request->input('from_date'),
    'af_to_date'=>$request->input('to_date'),
    'af_position_title'=>'',
    'af_sub_position_title'=>'',
    'af_location'=>'',
    'af_business'=>'',
    'af_teams'=>'HEPL',
    'af_raisedby'=>'',
    'af_interviewer'=>'',
    'af_billable'=>'Billable',
    'af_function'=>'',
    'af_division'=>'',
    'request_status_1'=>"Closed",
    'request_status_2'=>"On Hold",
);
// get all data
$total_billable = $this->corepo->get_open_position_details_af( $input_details );
$total_billable = count($total_billable);
 // $total_billable = $this->corepo->get_billable_status( $bill );



  $bill = array(
    "today_date"=>$request->input('p_date'),
    "from_date"=>$request->input('from_date'),
    "to_date"=>$request->input('to_date'),
    "billing_status"=>"Non Billable",
  );

  $input_details = array(
    'af_from_date'=>$request->input('from_date'),
    'af_to_date'=>$request->input('to_date'),
    'af_position_title'=>'',
    'af_sub_position_title'=>'',
    'af_location'=>'',
    'af_business'=>'',
    'af_teams'=>'HEPL',
    'af_raisedby'=>'',
    'af_interviewer'=>'',
    'af_billable'=>'Non Billable',
    'af_function'=>'',
    'af_division'=>'',
    'request_status_1'=>"Closed",
    'request_status_2'=>"On Hold",
);
// get all data
$total_non_billable = $this->corepo->get_open_position_details_af( $input_details );
$total_non_billable = count($total_non_billable);
  //$total_non_billable = $this->corepo->get_billable_status( $bill );

//   $get_max_count_closed = $this->corepo->get_max_count_recruiter( $data );
//   $get_min_count_closed = $this->corepo->get_min_count_recruiter( $data );


  $order = "desc";

  $recruit_dt = $this->corepo->get_user_recruiter_byorder($order);
  foreach ($recruit_dt as $rec){

        $bill = array(
          "today_date"=>$request->input('p_date'),
          "from_date"=>$request->input('from_date'),
          "to_date"=>$request->input('to_date'),
         // "billing_status"=>"Non Billable",
          "recruit_id"=>$rec->empID,
        );
        $closed_dt[] = $this->corepo->get_closure_details_close( $bill );
  }


 // echo json_encode($closed_dt);
  $get_max_count_closed = max($closed_dt);;
  $get_min_count_closed = min($closed_dt);;
  if($from =="" && $to =="" ){
    $total_billable = 0;
    $total_non_billable= 0;
  }
 return response()->json( [
     'get_recruitment' => $get_recruitment,
        'get_avg_open' => $get_avg_open,
        'avg_recruitment_per_recruiter' => $avg_recruitment_per_recruiter,
        'total_op' => $get_open_status,
        'total_billable' => $total_billable,
        'total_non_billable' => $total_non_billable,
        'max_count' => $get_max_count_closed,
        'min_count' => $get_min_count_closed,
        'total_open' => $get_open_status
     ] );
        }

        public function get_closure_details(Request $request){

                $today_date = $request->input('p_date');
                $from_date = $request->input('from_date');
                $to_date = $request->input('to_date');
                $col = $request->input('column');
                $order = $request->input('order');

$recruit_dt = $this->corepo->get_user_recruiter_byorder($order);
foreach ($recruit_dt as $rec){
    $bill = array(
        "today_date"=> $today_date,
        "from_date"=>$from_date,
        "to_date"=>$to_date,
        "billing_status"=>"Billable",
        "recruit_id"=>$rec->empID,
      );
      $billable_dt[] = $this->corepo->get_closure_details( $bill );

      $bill = array(
        "today_date"=> $today_date,
        "from_date"=>$from_date,
        "to_date"=>$to_date,
        "billing_status"=>"Non Billable",
        "recruit_id"=>$rec->empID,
      );
      $non_billable_dt[] = $this->corepo->get_closure_details( $bill );


      $bill = array(
        "today_date"=> $today_date,
        "from_date"=>$from_date,
        "to_date"=>$to_date,
        "billing_status"=>"Non Billable",
        "recruit_id"=>$rec->empID,
      );
      $closed_dt[] = $this->corepo->get_closure_details_close( $bill );
$recruit_name[] = $rec->name;
$recruit_color[] = $rec->color_code;


}

$bill = array(
    "today_date"=> $today_date,
    "from_date"=>$from_date,
    "to_date"=>$to_date,
    "billing_status"=>"Billable",

  );
  $total_billable = $this->corepo->get_billable_status_close( $bill );

  $bill = array(
    "today_date"=> $today_date,
    "from_date"=>$from_date,
    "to_date"=>$to_date,
    "billing_status"=>"Non Billable",

  );
  $total_non_billable = $this->corepo->get_billable_status_close( $bill );






$bill = array(
    "today_date"=> $today_date,
    "from_date"=>$from_date,
    "to_date"=>$to_date,
  );
$total_closed = $this->corepo->get_billable_status_total( $bill );

$advanced_filter = array(
    'af_from_date'=>$from_date,
    'af_to_date'=>$to_date,
    'af_position_title'=>"",
    'af_sub_position_title'=>"",
    'af_critical_position'=>"",
    'af_position_status'=>"Closed",
    'af_closed_by'=>"",
    'af_assigned_status'=>"",
    'af_salary_range'=>"",
    'af_band'=>"",
    'af_location'=>"",
    'af_business'=>"",
    'af_billing_status'=>"",
    'af_function'=>"",
    'af_division'=>"",
    'af_billable'=>"",
    'af_raisedby'=>"",
    'af_approvedby'=>"",
    'af_teams'=>"",
);

$get_reqcruitment_request_result = $this->corepo->get_reqcruitment_request_afilter( $advanced_filter );

 return response()->json( [
        'recruiter' => $recruit_name,
        'billable_dt' => $billable_dt,
        'non_billable_dt' => $non_billable_dt,
        'closed_dt' => $closed_dt,
        'total_billable' =>$total_billable,
        'total_non_billable'=>$total_non_billable,
        'total_closed' => $total_closed,
        'get_reqcruitment_request_result'=>$get_reqcruitment_request_result,
        'recruit_color' =>$recruit_color


     ] );
        }
    public function get_average(Request $req){
        $input_dt= [
                'today_date'=> $req->input('today_date'),
                'from_date'=> $req->input('from_date'),
                'to_date'=> $req->input('to_date')

        ];

            $from_date = $req->input('from_date');
            $to_date = $req->input('to_date');

            $get_total_csv = $this->corepo->get_total_csv_avg( $input_dt );
            $get_total_recruiter = $this->corepo->get_user_recruiter();
            $count =0;
            foreach($get_total_recruiter as $rec){
                $data =  array(
                    'recruiter' =>$rec->empID,
                    'from_date' =>$from_date,
                    'to_date' => $to_date,
                    'from_time' => "01:00:00",
                    'to_time' => "24:00:00",
                    'today_date' => date('Y-m-d'),
                );
                $get_total_csv_rc = $this->corepo->get_time_data_filter( $data );
                   if($get_total_csv_rc > 0){
                    $count = $count+1;
                   }
            }
            if($count >0){
           $average = $get_total_csv/$count;
           $average = number_format($average,1);
            }
            else{
             $average = $count;
            }
            echo ($average);


    }

public function get_nonpo_pending_list(Request $request){
    if ($request->ajax()) {

        // get all data
        $session_user_details = auth()->user();
        $created_by = $session_user_details->empID;

            $input_details = array(
                'leader_status'=>"0",
                'finance_status'=>"0",
                'payroll_status'=>"5",
                'payroll_status_ip'=>"0",
                'offer_rel_status'=>"0",

            );

            $get_candidate_profile_result = $this->payrepo->get_non_po_qc_list( $input_details );


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
            ->addColumn('ageing', function($row) {

               $offer_date = $row->offer_date;

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
                $btn ='';
                $btn .= '<button onclick ="get_oat_age_dt('."'".$row->created_by."'".','."'".$row->hepl_recruitment_ref_number."'".','."'".$row->cdID."'".','."'".$row->candidate_name."'".');"class="btn btn-vr btn-sm btn-smn" type="button">'.$ageing.'</button>';

                return $btn;

                //return $offer_date;
            })
            ->addColumn('revert', function($row) {


                 $btn ='';
                 if($row->payroll_status != 4){
                 $btn .= '<button onclick ="get_payroll_remark('."'".$row->cdID."'".');"class="btn btn-primary btn-sm btn-smn" type="button">Revert</button>';
                 }
                 else{
                    $btn .= '<button onclick ="get_payroll_remark('."'".$row->cdID."'".');"class="btn btn-primary btn-sm btn-smn" type="button" disabled>Revert</button>';

                 }
                 return $btn;

                 //return $offer_date;
             })

            ->addColumn('payroll_status', function($row) {

                if($row->payroll_status == 0){
                    $btn = '<span class="badge bg-purple">Not sent</span>';
                }elseif($row->payroll_status == 1){
                    $btn = '<span class="badge bg-warning">Pending</span>';
                }
                elseif($row->payroll_status == 2){
                    $btn = '<span class="badge bg-vr">Inprogress</span>';
                }
                elseif($row->payroll_status == 3){
                    $btn = '<span class="badge bg-success">Completed</span>';
                }
                else{
                    $btn = '';
                }
                return $btn;
            })
            ->addColumn('finance_status', function($row) {

                if($row->po_type =='po'){
                    if($row->po_finance_status == 0){
                        $btn = '<span class="badge bg-purple">Not sent</span>';
                    }elseif($row->po_finance_status == 1){
                        $btn = '';
                        $btn .= '<div class="dropdown dropdown-color-icon">';
                            $btn .= '<button class="btn btn-vr btn-sm btn-smn dropdown-toggle" type="button" id="dropdownMenuButtonEmoji" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 0.01rem 0.4rem !important;">';
                                $btn .= '<span class="me-50"></span>Inprogress';
                            $btn .= '</button>';

                            $btn .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuButtonEmoji" style="box-shadow: 0 0 30px rgb(0 0 0 / 40%) !important;">';

                                if($row->fn_po_remark !=''){
                                    $btn .= '<a class="dropdown-item" href="#" onclick="show_fn_remark('."'".$row->cdID."'".');" style="border:unset !important;"><i class="bi bi-file-earmark-medical-fill"></i> Remark</a>';
                                    $btn .='<input type="hidden" name="fn_remark_'.$row->cdID.'" id="fn_remark_'.$row->cdID.'" value="'.$row->fn_po_remark.'">';
                                }
                                if($row->fn_po_attach !=''){
                                    $btn .= '<div class="dropdown-divider"></div>';
                                    $btn .= '<a class="dropdown-item" href="../fn_po_attach/'.$row->cdID.'/'.$row->fn_po_attach.'"  style="border:unset !important;" target="_blank"><i class="bi bi-eye-fill"></i> Preview Finance Attach</a>';
                                }

                            $btn .= '</div>';
                        $btn .= '</div>';
                    }
                    elseif($row->po_finance_status == 2){
                        $btn = '';
                        $btn .= '<div class="dropdown dropdown-color-icon">';
                            $btn .= '<button class="btn btn-success btn-sm btn-smn dropdown-toggle" type="button" id="dropdownMenuButtonEmoji" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 0.01rem 0.4rem !important;">';
                                $btn .= '<span class="me-50"></span>Approved';
                            $btn .= '</button>';

                            $btn .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuButtonEmoji" style="box-shadow: 0 0 30px rgb(0 0 0 / 40%) !important;">';

                                if($row->fn_po_remark !=''){
                                    $btn .= '<a class="dropdown-item" href="#" onclick="show_fn_remark('."'".$row->cdID."'".');" style="border:unset !important;"><i class="bi bi-file-earmark-medical-fill"></i> Remark</a>';
                                    $btn .='<input type="hidden" name="fn_remark_'.$row->cdID.'" id="fn_remark_'.$row->cdID.'" value="'.$row->fn_po_remark.'">';
                                    $btn .= '<div class="dropdown-divider"></div>';

                                }
                                if($row->fn_po_attach !=''){
                                    $btn .= '<a class="dropdown-item" href="../fn_po_attach/'.$row->cdID.'/'.$row->fn_po_attach.'"  style="border:unset !important;" target="_blank"><i class="bi bi-eye-fill"></i> Preview Finance Attach</a>';
                                }

                            $btn .= '</div>';
                        $btn .= '</div>';
                    }
                    elseif($row->po_finance_status == 3){

                        $btn = '';

                        $btn .= '<div class="dropdown dropdown-color-icon">';
                            $btn .= '<button class="btn btn-danger btn-sm btn-smn dropdown-toggle" type="button" id="dropdownMenuButtonEmoji" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 0.01rem 0.4rem !important;">';
                                $btn .= '<span class="me-50"></span>Rejected';
                            $btn .= '</button>';

                            $btn .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuButtonEmoji" style="box-shadow: 0 0 30px rgb(0 0 0 / 40%) !important;">';

                                if($row->fn_po_remark !=''){
                                    $btn .= '<a class="dropdown-item" href="#" onclick="show_fn_remark('."'".$row->cdID."'".');" style="border:unset !important;"><i class="bi bi-file-earmark-medical-fill"></i> Remark</a>';
                                    $btn .='<input type="hidden" name="fn_remark_'.$row->cdID.'" id="fn_remark_'.$row->cdID.'" value="'.$row->fn_po_remark.'">';
                                }
                                if($row->fn_po_attach !=''){
                                    $btn .= '<div class="dropdown-divider"></div>';
                                    $btn .= '<a class="dropdown-item" href="../fn_po_attach/'.$row->cdID.'/'.$row->fn_po_attach.'"  style="border:unset !important;" target="_blank"><i class="bi bi-eye-fill"></i> Preview Finance Attach</a>';
                                }

                            $btn .= '</div>';
                        $btn .= '</div>';

                    }
                    else{
                        $btn = '';
                    }
                }
                else{
                    $btn = '';
                }
                return $btn;
            })

            ->addColumn('bhs_status', function($row) {

                if($row->leader_status == 0){
                    $btn = '<span class="badge bg-purple">Not sent</span>';
                }elseif($row->leader_status == 1){
                    $btn = '<span class="badge bg-vr">Inprogress</span>';
                }
                elseif($row->leader_status == 2){
                    $btn = '<span class="badge bg-success">Approved</span>';
                }
                elseif($row->leader_status == 4){
                    $btn = '<span class="badge bg-vr">Resend</span>';
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

            ->addColumn('action',function($row){
                if($row->po_type =='non_po'){
                    if($row->payroll_status == 5){
                        $btn = '<button class="btn-primary table_btn" id="action_btn_npo'.$row->cdID.'" onclick="approver_ld_npo_pop('."'".$row->rfh_no."'".','."'".$row->cdID."'".','."'".$row->po_type."'".','."'".$row->approver_id."'".');">Submit</button>';

                    }else{
                        $btn = '<span class="badge bg-info disabled" disabled>Sent</span>';

                    }
                }else{

                    // if($row->po_letter_filename !='' || $row->leader_status == 3  || $row->po_finance_status ==0 || $row->po_finance_status ==3){
                    //     $btn = '<span class="badge bg-primary pointer" id="action_btn_po'.$row->cdID.'" onclick="approver_ld_po_pop('."'".$row->rfh_no."'".','."'".$row->cdID."'".','."'".$row->po_type."'".','."'".$row->approver_id."'".');">Submit</span>';

                    // }
                    $input_details_cd = array(
                        'cdID' => $row->cdID,
                        'rfh_no' => $row->rfh_no,
                    );

                    $check_po_details_result = $this->payrepo->check_po_details( $input_details_cd );
                    if( $row->leader_status == 4 || $check_po_details_result == 0){
                        $btn = '<span class="badge bg-info disabled" disabled>Submit</span>';
                    }
                     else if(  $row->leader_status == 0 ){
                            $btn = '<span class="badge bg-primary pointer" id="action_btn_po'.$row->cdID.'" onclick="approver_ld_po_pop('."'".$row->rfh_no."'".','."'".$row->cdID."'".','."'".$row->po_type."'".','."'".$row->approver_id."'".');">Submit</span>';

                     }
                    else {
                        $btn = '<span class="badge bg-info disabled" disabled>Sent</span>';

                    }
                    // else{
                    //     $btn = '<span class="badge bg-primary pointer" id="action_btn_po'.$row->cdID.'" onclick="approver_ld_po_pop('."'".$row->rfh_no."'".','."'".$row->cdID."'".','."'".$row->po_type."'".','."'".$row->approver_id."'".');">Submit</span>';
                    // }


                }

                return $btn;

            })
            ->addColumn('offer_letter_preview',function($row){


                    $btn = '<a href="'.asset('public/'.$row->offer_letter_filename).'" target="_blank"><span class="badge bg-primary pointer" id="" title="Preview Offer Letter"><i class="bi bi-eye"></i></span></a>';

                    if($row->leader_status =='0' || $row->leader_status =='3'){
                        $btn .= ' <a href="edit_ctc_qc?cdID='.$row->cdID.'&rfh_no='.$row->rfh_no.'" target="_blank"><span style="margin-top:2px;" class="badge bg-dark pointer" id="" title="Edit Offer Letter"><i class="bi bi-pencil"></i></span></a>';

                    }
                   else{
                        $btn .= ' <span style="margin-top:2px;" class="badge bg-dark disabled" id="" title="Edit Offer Letter"><i class="bi bi-pencil"></i></span>';

                    }


                return $btn;
            })
            ->addColumn('po_type',function($row){

                if($row->po_type =='po'){
                    // $btn = '<span  class="badge bg-success">PO</span>';
                    $btn ='';
                    if($row->po_finance_status ==0 || $row->po_finance_status ==3 || $row->	po_file_status ==0){

                        $encode_rfhno = base64_encode($row->rfh_no);
                        $encode_cdID = base64_encode($row->cdID);

                        $btn .= '<div class="dropdown dropdown-color-icon">';
                            $btn .= '<button class="btn btn-success btn-sm btn-smn dropdown-toggle" type="button" id="dropdownMenuButtonEmoji" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 0.01rem 0.4rem !important;">';
                                $btn .= '<span class="me-50"></span>PO';
                            $btn .= '</button>';

                            $btn .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuButtonEmoji" style="box-shadow: 0 0 30px rgb(0 0 0 / 40%) !important;">';
                                if($row->po_letter_filename !=''){
                                    $btn .= '<a class="dropdown-item" href="add_po_details?rfh_no='.$encode_rfhno.'&cdID='.$encode_cdID.'" style="border:unset !important;"><i class="bi bi-pen-fill"></i> Edit PO Components</a>';
                                    $btn .= '<div class="dropdown-divider"></div>';
                                    $btn .= '<a class="dropdown-item" href="../po_letter/'.$row->cdID.'/'.$row->po_letter_filename.'"  style="border:unset !important;" target="_blank"><i class="bi bi-eye-fill"></i> Preview PO Input</a>';
                                    $btn .= '<div class="dropdown-divider"></div>';

                                $btn .= '<a class="dropdown-item" href="#" style="border:unset !important;" onclick="show_cpo_pop('."'".$row->rfh_no."'".','."'".$row->cdID."'".','."'".$row->leader_status."'".','."'".$row->po_finance_status."'".');"><i class="bi bi-upload"></i> Add Client PO</a>';

                                }else{
                                    $btn .= '<a class="dropdown-item" href="add_po_details?rfh_no='.$encode_rfhno.'&cdID='.$encode_cdID.'" style="border:unset !important;"><i class="bi bi-pen-fill"></i> Add PO Components</a>';


                                }

                                if($row->client_po_attach !=''){
                                    $btn .= '<div class="dropdown-divider"></div>';
                                    $btn .= '<a class="dropdown-item" href="../po_attachments/'.$row->cdID.'/'.$row->client_po_attach.'"  style="border:unset !important;" target="_blank"><i class="bi bi-eye-fill"></i> Preview Client PO </a>';
                                }
                            $btn .= '</div>';
                        $btn .= '</div>';

                    }else{
                        $encode_rfhno = base64_encode($row->rfh_no);
                        $encode_cdID = base64_encode($row->cdID);

                        $btn .= '<div class="dropdown dropdown-color-icon">';
                            $btn .= '<button class="btn btn-success btn-sm btn-smn dropdown-toggle" type="button" id="dropdownMenuButtonEmoji" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 0.01rem 0.4rem !important;">';
                                $btn .= '<span class="me-50"></span>PO';
                            $btn .= '</button>';

                            $btn .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuButtonEmoji" style="box-shadow: 0 0 30px rgb(0 0 0 / 40%) !important;">';
                                if($row->po_letter_filename !=''){
                                    $btn .= '<a class="dropdown-item" href="../po_letter/'.$row->cdID.'/'.$row->po_letter_filename.'"  style="border:unset !important;" target="_blank"><i class="bi bi-eye-fill"></i> Preview PO Input</a>';
                                }
                                if($row->client_po_attach =='' && $row->client_po_number !=''){
                                    $btn .= '<a class="dropdown-item" href="#" style="border:unset !important;" onclick="show_cpo_att('."'".$row->rfh_no."'".','."'".$row->cdID."'".','."'".$row->leader_status."'".','."'".$row->po_finance_status."'".');"><i class="bi bi-upload"></i> Add Client PO</a>';
                                }
                                if($row->client_po_number !=''){
                                    $btn .= '<div class="dropdown-divider"></div>';
                                    $btn .= '<a class="dropdown-item" href="#" style="border:unset !important;" >PO NUMBER: '.$row->client_po_number.'</a>';
                                }
                                if($row->client_po_attach !=''){
                                    $btn .= '<div class="dropdown-divider"></div>';
                                    $btn .= '<a class="dropdown-item" href="../po_attachments/'.$row->cdID.'/'.$row->client_po_attach.'"  style="border:unset !important;" target="_blank"><i class="bi bi-eye-fill"></i> Preview Client PO </a>';
                                }
                            $btn .= '</div>';
                        $btn .= '</div>';
                    }
                }elseif($row->po_type =='non_po'){
                    $btn = '<span  class="badge bg-danger">NonPO</span>';
                }else{
                    $btn='';
                }



                return $btn;
            })

            ->rawColumns(['finance_status','bhs_status','history','c_doc_status','ageing','revert','payroll_status','action','po_type','offer_letter_preview'])
            ->make(true);
}


}


public function send_to_leader_ol(Request $req){

    if($req->input('po_type') =='po'){
        $payroll_status = 2;

    }else{
        $payroll_status = 3;

    }

    $input_details = array(
        'cdID' => $req->input('cdID'),
        'leader_status' => "1",
        'payroll_status' => $payroll_status,
    );

    $process_sendto_leader_result = $this->payrepo->process_orpop_status_ld( $input_details );

    $input_details_rr = array(
        'rfh_no' => $req->input('rfh_no'),
    );

    $get_tblrfh_result = $this->corepo->get_tblrfh_details($input_details_rr);


    $input_details_c = array(
        'cdID' => $req->input('cdID'),
    );
    $get_candidate_details_result = $this->recrepo->get_candidate_details_ed( $input_details_c );

    $details = [
        'candidate_name' => $get_candidate_details_result[0]->candidate_name,
        'candidate_position' => $get_tblrfh_result[0]->position_title,
        'rfh_no' => $get_candidate_details_result[0]->hepl_recruitment_ref_number,
    ];

    $input_details_ad = array(
        'empID' => $req->input('approver'),
    );

    $get_user_result = $this->corepo->get_user_details($input_details_ad);

    $to_email=$get_user_result[0]->email;


    \Mail::send('emails.OfferRatifiedNonPoMail', array('details' => $details), function($message) use($to_email){
        $message->subject('CAREERS@HEPL: OFFER RATIFIED');
        $message->to($to_email);
        $message->cc(['karthik.d@hepl.com','rfh@hepl.com']);
            // if(count($cc_emails) >1){
            //     $message->cc($cc_emails);
            // }
            // if(count($bcc_emails) >1){
            //     $message->bcc($bcc_emails);
            // }
    });


    $response = 'success';
    return response()->json( ['response' => $response] );

}


public function get_approved_nonpo(Request $request){

    if ($request->ajax()) {

        // get all data
        $session_user_details = auth()->user();
        $created_by = $session_user_details->empID;

            $input_details = array(
                'payroll_status'=>"3",
                // 'po_finance_status'=>"2",
                // 'leader_status'=>"2",
            );

            $get_candidate_profile_result = $this->payrepo->get_approved_nonpo_offers( $input_details );


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
                    $btn = '<span class="badge bg-purple">Not sent</span>';
                }elseif($row->payroll_status == 1){
                    $btn = '<span class="badge bg-warning">Pending</span>';
                }
                elseif($row->payroll_status == 2){
                    $btn = '<span class="badge bg-vr">Inprogress</span>';
                }
                elseif($row->payroll_status == 3){
                    $btn = '<span class="badge bg-success">Completed</span>';
                }
               else{
                   $btn='';
               }
                return $btn;
            })
            ->addColumn('finance_status', function($row) {

                if($row->po_type =='po'){
                    if($row->po_finance_status == 0){
                        $btn = '<span class="badge bg-purple">Not sent</span>';
                    }elseif($row->po_finance_status == 1){
                        $btn = '';
                        $btn .= '<div class="dropdown dropdown-color-icon">';
                            $btn .= '<button class="btn btn-vr btn-sm btn-smn dropdown-toggle" type="button" id="dropdownMenuButtonEmoji" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 0.01rem 0.4rem !important;">';
                                $btn .= '<span class="me-50"></span>Inprogress';
                            $btn .= '</button>';

                            $btn .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuButtonEmoji" style="box-shadow: 0 0 30px rgb(0 0 0 / 40%) !important;">';

                                if($row->fn_po_remark !=''){
                                    $btn .= '<a class="dropdown-item" href="#" onclick="show_fn_remark('."'".$row->cdID."'".');" style="border:unset !important;"><i class="bi bi-file-earmark-medical-fill"></i> Remark</a>';
                                    $btn .='<input type="hidden" name="fn_remark_'.$row->cdID.'" id="fn_remark_'.$row->cdID.'" value="'.$row->fn_po_remark.'">';
                                }
                                if($row->fn_po_attach !=''){
                                    $btn .= '<div class="dropdown-divider"></div>';
                                    $btn .= '<a class="dropdown-item" href="../fn_po_attach/'.$row->cdID.'/'.$row->fn_po_attach.'"  style="border:unset !important;" target="_blank"><i class="bi bi-eye-fill"></i> Preview Finance Attach</a>';
                                }

                            $btn .= '</div>';
                        $btn .= '</div>';
                    }
                    elseif($row->po_finance_status == 2){
                        $btn = '';
                        $btn .= '<div class="dropdown dropdown-color-icon">';
                            $btn .= '<button class="btn btn-success btn-sm btn-smn dropdown-toggle" type="button" id="dropdownMenuButtonEmoji" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 0.01rem 0.4rem !important;">';
                                $btn .= '<span class="me-50"></span>Approved';
                            $btn .= '</button>';

                            $btn .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuButtonEmoji" style="box-shadow: 0 0 30px rgb(0 0 0 / 40%) !important;">';

                                if($row->fn_po_remark !=''){
                                    $btn .= '<a class="dropdown-item" href="#" onclick="show_fn_remark('."'".$row->cdID."'".');" style="border:unset !important;"><i class="bi bi-file-earmark-medical-fill"></i> Remark</a>';
                                    $btn .='<input type="hidden" name="fn_remark_'.$row->cdID.'" id="fn_remark_'.$row->cdID.'" value="'.$row->fn_po_remark.'">';
                                    $btn .= '<div class="dropdown-divider"></div>';

                                }
                                if($row->fn_po_attach !=''){
                                    $btn .= '<a class="dropdown-item" href="../fn_po_attach/'.$row->cdID.'/'.$row->fn_po_attach.'"  style="border:unset !important;" target="_blank"><i class="bi bi-eye-fill"></i> Preview Finance Attach</a>';
                                }

                            $btn .= '</div>';
                        $btn .= '</div>';
                    }
                    elseif($row->po_finance_status == 3){

                        $btn = '';

                        $btn .= '<div class="dropdown dropdown-color-icon">';
                            $btn .= '<button class="btn btn-danger btn-sm btn-smn dropdown-toggle" type="button" id="dropdownMenuButtonEmoji" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 0.01rem 0.4rem !important;">';
                                $btn .= '<span class="me-50"></span>Rejected';
                            $btn .= '</button>';

                            $btn .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuButtonEmoji" style="box-shadow: 0 0 30px rgb(0 0 0 / 40%) !important;">';

                                if($row->fn_po_remark !=''){
                                    $btn .= '<a class="dropdown-item" href="#" onclick="show_fn_remark('."'".$row->cdID."'".');" style="border:unset !important;"><i class="bi bi-file-earmark-medical-fill"></i> Remark</a>';
                                    $btn .='<input type="hidden" name="fn_remark_'.$row->cdID.'" id="fn_remark_'.$row->cdID.'" value="'.$row->fn_po_remark.'">';
                                }
                                if($row->fn_po_attach !=''){
                                    $btn .= '<div class="dropdown-divider"></div>';
                                    $btn .= '<a class="dropdown-item" href="../fn_po_attach/'.$row->cdID.'/'.$row->fn_po_attach.'"  style="border:unset !important;" target="_blank"><i class="bi bi-eye-fill"></i> Preview Finance Attach</a>';
                                }

                            $btn .= '</div>';
                        $btn .= '</div>';

                    }
                    else{
                        $btn = '';
                    }
                }
                else{
                    $btn = '';
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
                    // $btn = '<span class="badge bg-success">Approved</span>';
                    $btn = '';
                        $btn .= '<div class="dropdown dropdown-color-icon">';
                            $btn .= '<button class="btn btn-success btn-sm btn-smn dropdown-toggle" type="button" id="dropdownMenuButtonEmoji" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 0.01rem 0.4rem !important;">';
                                $btn .= '<span class="me-50"></span>Approved';
                            $btn .= '</button>';

                            $btn .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuButtonEmoji" style="box-shadow: 0 0 30px rgb(0 0 0 / 40%) !important;">';

                                if($row->ld_reject_remark !=''){
                                    $btn .= '<a class="dropdown-item" href="#" onclick="show_ld_remark('."'".$row->cdID."'".');" style="border:unset !important;"><i class="bi bi-file-earmark-medical-fill"></i> Remark</a>';
                                    $btn .='<input type="hidden" name="ld_reject_remark_'.$row->cdID.'" id="ld_reject_remark_'.$row->cdID.'" value="'.$row->ld_reject_remark.'">';
                                }

                            $btn .= '</div>';
                        $btn .= '</div>';
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
            ->addColumn('action',function($row){

                $btn = '<span class="badge bg-primary" onclick="approver_ld_pop('."'".$row->rfh_no."'".','."'".$row->cdID."'".');">Submit</span>';
                return $btn;
            })
            ->addColumn('offer_letter_preview',function($row){

                    $btn = '<a href="'.asset('public/'.$row->offer_letter_filename).'" target="_blank"><span class="badge bg-primary" id="" title="Preview Offer Letter"><i class="bi bi-eye"></i></span></a>';

                return $btn;
            })

            ->addColumn('po_type',function($row){

                if($row->po_type =='po'){
                    // $btn = '<span  class="badge bg-success">PO</span>';
                    $btn ='';
                    if($row->po_finance_status ==0 || $row->po_finance_status ==3 || $row->	po_file_status ==0){

                        $encode_rfhno = base64_encode($row->rfh_no);
                        $encode_cdID = base64_encode($row->cdID);

                        $btn .= '<div class="dropdown dropdown-color-icon">';
                            $btn .= '<button class="btn btn-success btn-sm btn-smn dropdown-toggle" type="button" id="dropdownMenuButtonEmoji" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 0.01rem 0.4rem !important;">';
                                $btn .= '<span class="me-50"></span>PO';
                            $btn .= '</button>';

                            $btn .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuButtonEmoji" style="box-shadow: 0 0 30px rgb(0 0 0 / 40%) !important;">';
                                if($row->po_letter_filename !=''){
                                    $btn .= '<a class="dropdown-item" href="add_po_details?rfh_no='.$encode_rfhno.'&cdID='.$encode_cdID.'" style="border:unset !important;"><i class="bi bi-pen-fill"></i> Edit PO Components</a>';
                                    $btn .= '<div class="dropdown-divider"></div>';
                                    $btn .= '<a class="dropdown-item" href="../po_letter/'.$row->cdID.'/'.$row->po_letter_filename.'"  style="border:unset !important;" target="_blank"><i class="bi bi-eye-fill"></i> Preview PO Input</a>';
                                }else{
                                    $btn .= '<a class="dropdown-item" href="add_po_details?rfh_no='.$encode_rfhno.'&cdID='.$encode_cdID.'" style="border:unset !important;"><i class="bi bi-pen-fill"></i> Add PO Components</a>';


                                }
                                $btn .= '<div class="dropdown-divider"></div>';

                                $btn .= '<a class="dropdown-item" href="#" style="border:unset !important;" onclick="show_cpo_pop('."'".$row->rfh_no."'".','."'".$row->cdID."'".','."'".$row->leader_status."'".','."'".$row->po_finance_status."'".');"><i class="bi bi-upload"></i> Add Client PO</a>';

                                if($row->client_po_attach !=''){
                                    $btn .= '<div class="dropdown-divider"></div>';
                                    $btn .= '<a class="dropdown-item" href="../po_attachments/'.$row->cdID.'/'.$row->client_po_attach.'"  style="border:unset !important;" target="_blank"><i class="bi bi-eye-fill"></i> Preview Client PO </a>';
                                }
                            $btn .= '</div>';
                        $btn .= '</div>';

                    }else{
                        $encode_rfhno = base64_encode($row->rfh_no);
                        $encode_cdID = base64_encode($row->cdID);

                        $btn .= '<div class="dropdown dropdown-color-icon">';
                            $btn .= '<button class="btn btn-success btn-sm btn-smn dropdown-toggle" type="button" id="dropdownMenuButtonEmoji" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 0.01rem 0.4rem !important;">';
                                $btn .= '<span class="me-50"></span>PO';
                            $btn .= '</button>';

                            $btn .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuButtonEmoji" style="box-shadow: 0 0 30px rgb(0 0 0 / 40%) !important;">';
                                if($row->po_letter_filename !=''){
                                    $btn .= '<a class="dropdown-item" href="../po_letter/'.$row->cdID.'/'.$row->po_letter_filename.'"  style="border:unset !important;" target="_blank"><i class="bi bi-eye-fill"></i> Preview PO Input</a>';
                                }
                                if($row->client_po_attach =='' && $row->client_po_number !=''){
                                    $btn .= '<a class="dropdown-item" href="#" style="border:unset !important;" onclick="show_cpo_att('."'".$row->rfh_no."'".','."'".$row->cdID."'".','."'".$row->leader_status."'".','."'".$row->po_finance_status."'".');"><i class="bi bi-upload"></i> Add Client PO</a>';
                                }
                                if( $row->client_po_number !=''){
                                    $btn .= '<div class="dropdown-divider"></div>';
                                    $btn .= '<a class="dropdown-item" href="#" style="border:unset !important;" "> PO NUMBER: '.$row->client_po_number.'</a>';
                                }
                                if($row->client_po_attach !=''){
                                    $btn .= '<div class="dropdown-divider"></div>';
                                    $btn .= '<a class="dropdown-item" href="../po_attachments/'.$row->cdID.'/'.$row->client_po_attach.'"  style="border:unset !important;" target="_blank"><i class="bi bi-eye-fill"></i> Preview Client PO </a>';
                                }
                            $btn .= '</div>';
                        $btn .= '</div>';

                    }
                }elseif($row->po_type =='non_po'){
                    $btn = '<span  class="badge bg-danger">NonPO</span>';
                }else{
                    $btn='';
                }



                return $btn;
            })
            ->rawColumns(['leader_status','finance_status','history','c_doc_status','payroll_status','action','offer_letter_preview','po_type'])
            ->make(true);

    }
}

public function payroll_revert_update(Request $req){
    $input_details = array(
        'cdID' => $req->input('can_id'),
        'payroll_remark' => $req->input('oat_remark'),
        'payroll_status' => "4"
    );
    $ageing_dt = $this->payrepo->update_po_finance_status($input_details);

    $response = 'success';
    return response()->json( ['response' => $response] );
}
public function edit_ctc_oat(){
    return view('payroll/edit_ctc_oat');
}

public function get_ageing_report(Request $req){
$team = $req->input('team');
if($team == ''){
    $team = 'HEPL';
}
        $data =[
        'billable_status' => 'Billable',
        'input_team' => $team
        ];

    $open_billable = $this->corepo->ageging_billable_nonbillable_total( $data );

    $i = 0;
    $len = count($open_billable);
    if($len == 0){
        $position_ageing[] = ['0,0,0'];
    }
    foreach ($open_billable as $value){
       $open_date = date("Y-m-d", strtotime($value->created_at));

        $start = new DateTime($open_date);
        $today = date('Y-m-d');
        $end = new DateTime($today);
        $days = $start->diff($end, true)->days;
// echo $days;
// echo '<br>';
$sundays = intval($days / 7) + ($start->format('N') + $days % 7 >= 7);


$position_ageing[] = (int)$days - (int)$sundays;

        $i++;
    }

    $data =[
        'billable_status' => 'Non Billable',
        'input_team' => $team
        ];

    $closed_billable = $this->corepo->ageging_billable_nonbillable_total( $data );

    $h = 0;
    $lenh = count($closed_billable);
    if($lenh == 0){
        $position_ageing_closed[] = ['0,0,0'];
    }
    foreach ($closed_billable as $values){
       $close_date = date("Y-m-d", strtotime($values->created_at));

        $start_c = new DateTime($close_date);
        $today_c = date('Y-m-d');
        $end_c = new DateTime($today_c);
        $days_c = $start_c->diff($end_c, true)->days;
// echo $days;
// echo '<br>';
$sundays_c = intval($days_c / 7) + ($start_c->format('N') + $days_c % 7 >= 7);


$position_ageing_closed[] = (int)$days_c - (int)$sundays_c;

        $h++;
    }

    $data =[
        'billable_status' => '',
        'input_team' => $team
        ];

    $total_billable = $this->corepo->ageging_billable_nonbillable_total( $data );

//     $hc = 0;
//     $lenhc = count($total_billable);
//   if($lenhc == 0){

//   }

//     foreach ($total_billable as $values){
//        $close_date = date("Y-m-d", strtotime($values->created_at));

//         $start_c = new DateTime($close_date);
//         $today_c = date('Y-m-d');
//         $end_c = new DateTime($today_c);
//         $days_c = $start_c->diff($end_c, true)->days;
// // echo $days;
// // echo '<br>';
// $sundays_c = intval($days_c / 7) + ($start_c->format('N') + $days_c % 7 >= 7);


// $position_ageing_total[] = (int)$days_c - (int)$sundays_c;

//         $h++;
//     }

    return response()->json( [
        'position_ageing' => $position_ageing,
        'position_ageing_closed' => $position_ageing_closed,
        'position_ageing_total' => $total_billable,
    ] );
    $table ="<td>test</td>";
    return $table;
}
}// end class

