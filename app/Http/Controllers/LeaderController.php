<?php

namespace App\Http\Controllers;
use App\Repositories\ILeaderRepository;
use App\Repositories\IPayrollRepository;
use App\Repositories\ICoordinatorRepository;
use App\Repositories\IRecruiterRepository;
use App\Models\Candidate_followup_details;
use Illuminate\Http\Request;
use DataTables;
use PDF;
use File;
use DB;
use App\Models\Podetails;

class LeaderController extends Controller
{

    public function __construct(ICoordinatorRepository $corepo,ILeaderRepository $ldrepo,IPayrollRepository $payrepo,IRecruiterRepository $recrepo)
    {
        $this->ldrepo = $ldrepo;
        $this->payrepo = $payrepo;
        $this->corepo = $corepo;
        $this->recrepo = $recrepo;

        $this->middleware('backend_coordinator');
    }
    public function ol_leader_verify(Request $request){

        if ($request->ajax()) {

            // get all data
            $session_user_details = auth()->user();
            $created_by = $session_user_details->empID;

                $input_details = array(
                    'approver'=>$created_by,
                    'leader_status'=>"1",
                );

                $get_candidate_profile_result = $this->ldrepo->get_candidate_profile_ld( $input_details );


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
                    }else{
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
                ->addColumn('po_type',function($row){

                    if($row->po_type =='po'){
                        // $btn = '<span  class="badge bg-success">PO</span>';
                        $btn ='';
                        // if($row->po_finance_status ==0 || $row->po_finance_status ==3 || $row->	po_file_status ==0){

                            $encode_rfhno = base64_encode($row->rfh_no);
                            $encode_cdID = base64_encode($row->cdID);

                            $btn .= '<div class="dropdown dropdown-color-icon">';
                                $btn .= '<button class="btn btn-success btn-sm btn-smn dropdown-toggle" type="button" id="dropdownMenuButtonEmoji" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 0.01rem 0.4rem !important;">';
                                    $btn .= '<span class="me-50"></span>PO';
                                $btn .= '</button>';

                                $btn .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuButtonEmoji" style="box-shadow: 0 0 30px rgb(0 0 0 / 40%) !important;">';
                                    if($row->po_letter_filename !=''){
                                        $btn .= '<a class="dropdown-item" href="edit_po_details_bh?rfh_no='.$encode_rfhno.'&cdID='.$encode_cdID.'" style="border:unset !important;"><i class="bi bi-pen-fill"></i> Edit PO Components</a>';
                                        $btn .= '<div class="dropdown-divider"></div>';
                                        $btn .= '<a class="dropdown-item" href="../po_letter/'.$row->cdID.'/'.$row->po_letter_filename.'"  style="border:unset !important;" target="_blank"><i class="bi bi-eye-fill"></i> Preview PO Input</a>';
                                    }
                                    if($row->client_po_number !=''){
                                        $btn .= '<div class="dropdown-divider"></div>';
                                        $btn .= '<a class="dropdown-item" href="#" style="border:unset !important;" "> PO NUMBER: '.$row->client_po_number.'</a>';
                                    }
                                    if($row->client_po_attach !=''){
                                        $btn .= '<div class="dropdown-divider"></div>';
                                        $btn .= '<a class="dropdown-item" href="../po_attachments/'.$row->cdID.'/'.$row->client_po_attach.'"  style="border:unset !important;" target="_blank"><i class="bi bi-eye-fill"></i> Preview Client PO </a>';
                                    }
                                $btn .= '</div>';
                            $btn .= '</div>';

                        // }
                        // else{
                        //     $btn .= ' <span class="badge bg-dark disabled"><i class="bi bi-pencil"></i></span>';
                        //     // $btn .= ' <span class="badge bg-secondary   "><i class="bi bi-eye" onclick="view_po_pop('."'".$row->rfh_no."'".','."'".$row->cdID."'".');"></i></span>';

                        // }
                    }elseif($row->po_type =='non_po'){
                        $btn = '<span  class="badge bg-danger">NonPO</span>';
                    }else{
                        $btn='';
                    }



                    return $btn;
                })
                ->addColumn('ageing',function($row){
                    $input_details_cd = array(
                        'cdID' => $row->cdID,
                        'rfh_no' => $row->rfh_no,
                    );

                    $check_po_details_result = $this->payrepo->check_po_details( $input_details_cd );
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
                ->addColumn('action',function($row){

                    if($row->po_type =='non_po'){
                        $btn = '<span class="badge bg-primary" onclick="action_nonpo_popbtn('."'".$row->rfh_no."'".','."'".$row->cdID."'".','."'".$row->po_file_status."'".');"><i class="bi bi-pencil"></i> </span>';

                    }else{
                      //  $btn = '<span class="badge bg-primary" onclick="action_popbtn('."'".$row->rfh_no."'".','."'".$row->cdID."'".','."'".$row->po_file_status."'".');"><i class="bi bi-pencil"></i> </span>';
                        $btn = '<span class="badge bg-primary pointer" id="action_btn_po'.$row->cdID.'" onclick="approver_to_finance('."'".$row->rfh_no."'".','."'".$row->cdID."'".','."'".$row->po_type."'".');">Approve</span>';

                    }

                    return $btn;
                })
                // ->addColumn('action',function($row){
                //     $btn = '<div class="form-check">';
                //     $btn .= '<input type="radio" class="form-check-input" name="ld_status_'.$row->cdID.'" id="ld_status1_'.$row->cdID.'" value="2"><label class="form-check-label" for="ld_status1_'.$row->cdID.'">Approve</label>';
                //     $btn .='</div>';
                //     $btn .= '<div class="form-check">';
                //     $btn .= '<input type="radio" class="form-check-input" name="ld_status_'.$row->cdID.'" id="ld_status2_'.$row->cdID.'" value="3"><label class="form-check-label" for="ld_status2_'.$row->cdID.'">Reject</label>';
                //     $btn .='</div>';
                //     $btn .= '<button class="table_btn btn-primary" style="margin-top:5px;" id="ld_action_btn_'.$row->cdID.'" onclick="approver_ld_pop('."'".$row->rfh_no."'".','."'".$row->cdID."'".','."'".$row->po_type."'".');">Submit</button>';
                //     return $btn;
                // })
                ->addColumn('offer_letter_preview',function($row){


                        $btn = '<a href="'.asset('public/'.$row->offer_letter_filename).'" target="_blank"><span class="badge bg-primary" id="" title="Preview Offer Letter"><i class="bi bi-eye"></i></span></a>';

                        // $btn .= ' <a href="edit_ctc_oat?cdID='.$row->cdID.'&rfh_no='.$row->rfh_no.'" target="_blank"><span style="margin-top:2px;" class="badge bg-dark" id="" title="Edit Offer Letter"><i class="bi bi-pencil"></i></span><a>';


                    return $btn;
                })
                ->addColumn('candidate_details',function($row){
                    $btn = '<a href="cd_preview_bh?cdID='.$row->cdID.'" target="_blank"><span class="badge bg-primary" id="btnAssign" title="Candidate Profile"><i class="bi bi-person-lines-fill"></i></span></a>';
                    return $btn;
                })
                ->rawColumns(['finance_status','po_type','history','c_doc_status','payroll_status','ageing','action','candidate_details','offer_letter_preview'])
                ->make(true);

        }
        return view('leader/ol_leader_verify');

    }


    public function edit_po_details_bh(){
        return view('leader/edit_po_details_bh');
    }

    public function get_po_components_bh(Request $req){

        // get po default values
        $get_po_default_values_result = $this->payrepo->get_po_default_values();

        // get existing po details for this candidate
        $input_details_cd = array(
            'cdID' => base64_decode($req->input('cdID')),
        );
        $get_po_details_result = $this->payrepo->get_po_details($input_details_cd);

        // get rfh info
        $input_details = array(
            'rfh_no' => base64_decode($req->input('rfh_no')),
        );
        $get_rfh_details_result = $this->corepo->get_recruitment_edit_details($input_details);

        // get ctc details
        $get_ctc_details_result = $this->payrepo->get_ctc_calculation($input_details_cd);

        // get candidate_details
        $get_candidate_details_result = $this->corepo->get_candidate_details_ed($input_details_cd);

        return response()->json( [
            'po_details_result' => $get_po_details_result,
            'po_default_values' => $get_po_default_values_result,
            'rfh_form_details' => $get_rfh_details_result,
            'ctc_details' => $get_ctc_details_result,
            'candidate_details' => $get_candidate_details_result,
        ] );

    }

    public function submit_po_process_bh(Request $req){

        $get_assigned_to = auth()->user();
        $created_by = $get_assigned_to->empID;


        $po_details_val = $req->input( 'po_details' );
        $po_description_val = $req->input( 'po_description' );
        $po_amount_val = $req->input( 'po_amount' );
        $po_amount_month = $req->input( 'po_amount_month' );
       // $po_reg_fee = $req->input( 'po_reg_fee' );
        $po_remark = $req->input( 'remark' );

        $po_details_json = json_encode($po_details_val);
        $po_description_json = json_encode($po_description_val);
        $po_amount_json = json_encode($po_amount_val);
        $po_amount_month_json = json_encode($po_amount_month);
        $po_remark_json = json_encode($po_remark);

        $rfh_no = base64_decode($req->input('rfh_no'));
        $cdID = base64_decode($req->input('cdID'));

        $data = array(
            'cdID' => $cdID,
            'rfh_no' => $rfh_no,
            'po_detail' => $po_details_json,
            'po_description' => $po_description_json,
            'po_amount' => $po_amount_json,
            'created_by' => $created_by,
        );


        // check record already exits

        $path = public_path().'/po_letter/'.$cdID;

        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);

        $input_details_cd = array(
            'cdID' => $cdID,
            'rfh_no' => $rfh_no,
        );

        $check_po_details_result = $this->payrepo->check_po_details( $input_details_cd );

        if($check_po_details_result ==0){

            $submit_po_process_result = $this->payrepo->submit_po_process($data);

        }else{
            if (\File::exists($path)) \File::deleteDirectory($path);

            $update_po_process_result = $this->payrepo->update_po_process_oat( $data );

        }

        $pdf_data =array();
      //  $pdf_data['po_reg_fee'] = $po_reg_fee;
        $sno = 1;
        for ($i=0; $i < count($po_details_val); $i++) {

            $pdf_data[$i]['sno'] = $sno;
            $pdf_data[$i]['po_details'] = $po_details_val[$i];
            $pdf_data[$i]['po_description'] = $po_description_val[$i];
            if(count($po_remark) == count($po_details_val)){
            $pdf_data[$i]['remark'] = $po_remark[$i];
            }
            else{
                $pdf_data[$i]['remark'] = "null";
            }

            if($po_amount_val[$i] !='no_val'){
                $pdf_data[$i]['po_amount'] = $this->moneyFormatIndia($po_amount_val[$i]);
            }
            else{
                $pdf_data[$i]['po_amount'] = $po_amount_val[$i];

            }
            if($po_amount_month[$i] !='' && $po_amount_month[$i] !='Nan'){
                $pdf_data[$i]['po_amount_month'] = $this->moneyFormatIndia($po_amount_month[$i]);
            }
            else{
                $pdf_data[$i]['po_amount_month'] = $po_amount_month[$i];

            }
            $sno++;
        }

        $data = array (
            'json' => $pdf_data
        );

        $pdf = PDF::loadView('payroll/po_letter_pdf', $data);

        $path = public_path().'/po_letter/'.$cdID;
        if (\File::exists($path)) \File::deleteDirectory($path);

        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);

        $fileName = $rfh_no.'_'.$cdID. '.' . 'pdf';

        $pdf->save($path . '/' . $fileName);

        $input_details_pf = array(
            'cdID' => $cdID,
            'rfh_no' => $rfh_no,
            'po_letter_filename' => $fileName,
            'payroll_status' => 2,
        );

        $update_poletter_result = $this->payrepo->update_poletter_fn( $input_details_pf );

// SEND MAIL TO FINANCE


        // update candidate detail tbl


        // send maill to finance code
    //     $input_details_ad = array(
    //         'empID' => '900072',
    //     );

    //     $get_user_result = $this->corepo->get_user_details($input_details_ad);

    //     $to_email=$get_user_result[0]->email;

    //     $input_details_cin = array(
    //         'cdID' =>$cdID,
    //     );
    //     $get_candidate_details_result = $this->recrepo->get_candidate_details_ed( $input_details_cin );
    //  //  print_r($input_details_cin);
    //     $input_details_rr = array(
    //         'rfh_no' => $rfh_no,
    //     );

    //     $get_tblrfh_result = $this->corepo->get_tblrfh_details($input_details_rr);
    //    // print_r( $get_candidate_details_result);

    //     $details = [
    //         'candidate_name' => $get_candidate_details_result[0]->candidate_name,
    //         'candidate_position' => $get_tblrfh_result[0]->position_title,
    //         'rfh_no' => $get_candidate_details_result[0]->hepl_recruitment_ref_number,
    //     ];

    //     // \Mail::send('emails.OfferRatifiedPoMail', array('details' => $details), function($message) use($to_email, $cc_emails, $bcc_emails){
    //         \Mail::send('emails.OfferRatifiedPoMail', array('details' => $details), function($message) use($to_email){
    //         $message->subject('CAREERS@HEPL: OFFER RATIFIED ');
    //         $message->to($to_email);

    //     });

    //    // offer release followup entry
    //     $created_by = $get_assigned_to->empID;

    //     $or_followup_input = array(
    //         'cdID' => $cdID,
    //         'rfh_no' => $rfh_no,
    //         'hepl_recruitment_ref_number' => $get_candidate_details_result[0]->hepl_recruitment_ref_number,
    //         'description' => "RFH is Ratified and submitted to Finance for Verification",
    //         'created_by' => $created_by,
    //     );
    //     $put_or_followup_result = $this->recrepo->offer_release_followup_entry( $or_followup_input );


        return response()->json( [
            'response' => 'success',
          //  'redirect_url' => 'CK_recruitment_management_new/public/po_letter/'.$cdID.'/'.$fileName,
          'redirect_url' => '../po_letter/'.$cdID.'/'.$fileName,
          'cdID' => $cdID,
            'rfh_no' => $rfh_no
            ] );
        // return 'po_letter/'.$cdID.'/'.$fileName;

        // $response = 'success';
        // return response()->json( ['response' => $response] );
    }
    public function submit_oat_process_bh(Request $req){

        $get_assigned_to = auth()->user();
        $created_by = $get_assigned_to->empID;


        $po_details_val = $req->input( 'po_details' );
        $po_description_val = $req->input( 'po_description' );
        $po_amount_val = $req->input( 'po_amount' );
        $remark = $req->input( 'remark' );
      //  $po_remark = $req->input( 'po_remark' );
      $po_amount_month = $req->input( 'po_amount_month' );
$po_amount_month_json = json_encode($po_amount_month);
        $remark_json = json_encode($remark);
       // $po_remark_json = json_encode($po_remark);
        $po_details_json = json_encode($po_details_val);
        $po_description_json = json_encode($po_description_val);
        $po_amount_json = json_encode($po_amount_val);
        $po_remark = $req->input( 'remark' );
        $po_remark_json = json_encode($po_remark);
        $rfh_no = base64_decode($req->input('rfh_no'));
        $cdID = base64_decode($req->input('cdID'));

        $data = array(
            'cdID' => $cdID,
            'rfh_no' => $rfh_no,
            'po_detail' => $po_details_json,
            'po_description' => $po_description_json,
            'po_amount' => $po_amount_json,
            'remark' => $remark_json,
           // 'po_remark' => $po_remark_json,
            'created_by' => $created_by,
        );


        // check record already exits

        $path = public_path().'/po_letter/'.$cdID;

        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);

        $input_details_cd = array(
            'cdID' => $cdID,
            'rfh_no' => $rfh_no,
        );

        $check_po_details_result = $this->payrepo->check_po_details( $input_details_cd );

        if($check_po_details_result ==0){

            $submit_po_process_result = $this->payrepo->submit_po_process($data);

        }else{


            if (\File::exists($path)) \File::deleteDirectory($path);
          //  $update_po_process_remark = $this->payrepo->update_po_process_remark( $data );
            $update_po_process_result = $this->payrepo->update_po_process( $data );

        }

        $pdf_data =array();
        $sno = 1;
        for ($i=0; $i < count($po_details_val); $i++) {

            $pdf_data[$i]['sno'] = $sno;
            $pdf_data[$i]['po_details'] = $po_details_val[$i];
            $pdf_data[$i]['po_description'] = $po_description_val[$i];
            if(count($po_remark) == count($po_details_val)){
                $pdf_data[$i]['remark'] = $po_remark[$i];
                }
                else{
                    $pdf_data[$i]['remark'] = "null";
                }
            if($po_amount_val[$i] !='no_val'){
                $pdf_data[$i]['po_amount'] = $this->moneyFormatIndia($po_amount_val[$i]);
            }
            else{
                $pdf_data[$i]['po_amount'] = $po_amount_val[$i];

            }
            if($po_amount_month[$i] !='' && $po_amount_month[$i] !='Nan'){
                $pdf_data[$i]['po_amount_month'] = $this->moneyFormatIndia($po_amount_month[$i]);
            }
            else{
                $pdf_data[$i]['po_amount_month'] = $po_amount_month[$i];

            }
            $sno++;
        }

        $data = array (
            'json' => $pdf_data
        );

        $pdf = PDF::loadView('payroll/po_letter_pdf', $data);

        $path = public_path().'/po_letter/'.$cdID;
        if (\File::exists($path)) \File::deleteDirectory($path);

        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);

        $fileName = $rfh_no.'_'.$cdID. '.' . 'pdf';

        $pdf->save($path . '/' . $fileName);

        $input_details_pf = array(
            'cdID' => $cdID,
            'rfh_no' => $rfh_no,
            'po_letter_filename' => $fileName,
            'payroll_status' => 2,
        );

        $update_poletter_result = $this->payrepo->update_poletter_fn( $input_details_pf );


        return response()->json( [
            'response' => 'success',
            //'redirect_url' => 'CK_recruitment_management_new/public/po_letter/'.$cdID.'/'.$fileName,
            'redirect_url' => '../po_letter/'.$cdID.'/'.$fileName,

            'cdID' => $cdID,
            'rfh_no' => $rfh_no
            ] );

        // return 'po_letter/'.$cdID.'/'.$fileName;

        // $response = 'success';
        // return response()->json( ['response' => $response] );
    }
    function moneyFormatIndia($num)
    {
        $explrestunits = "";
        if (strlen($num) > 3)
        {
            $lastthree = substr($num, strlen($num) - 3, strlen($num));
            $restunits = substr($num, 0, strlen($num) - 3); // extracts the last three digits
            $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
            $expunit = str_split($restunits, 2);
            for ($i = 0;$i < sizeof($expunit);$i++)
            {
                // creates each of the 2's group and adds a comma to the end
                if ($i == 0)
                {
                    $explrestunits .= (int)$expunit[$i] . ","; // if is first value , convert into integer

                }
                else
                {
                    $explrestunits .= $expunit[$i] . ",";
                }
            }
            $thecash = $explrestunits . $lastthree;
        }
        else
        {
            $thecash = $num;
        }
        return $thecash; // writes the final format where $currency is the currency symbol.

    }
public function send_oat_mail(Request $req){
    // SEND MAIL TO OAT

    $get_assigned_to = auth()->user();
    $created_by = $get_assigned_to->empID;

        // update candidate detail tbl
        $input_details_fin = array(
            'cdID' => $req->input('cdID'),
            'po_finance_status' => 0,
            'leader_status' => 4,

        );

        $update_cd_result = $this->payrepo->update_po_finance_status($input_details_fin);

        // send maill to OAT code
        $input_details_ad = array(
            'empID' => '900093',
        );

        $get_user_result = $this->corepo->get_user_details($input_details_ad);

        $to_email=$get_user_result[0]->email;

        $input_details_cin = array(
            'cdID' =>$req->input('cdID'),
        );
        $get_candidate_details_result = $this->recrepo->get_candidate_details_ed( $input_details_cin );
     //  print_r($input_details_cin);
        $input_details_rr = array(
            'rfh_no' => $req->input('rfh_no'),
        );

        $get_tblrfh_result = $this->corepo->get_tblrfh_details($input_details_rr);
       // print_r( $get_candidate_details_result);

        $details = [
            'candidate_name' => $get_candidate_details_result[0]->candidate_name,
            'candidate_position' => $get_tblrfh_result[0]->position_title,
            'rfh_no' => $get_candidate_details_result[0]->hepl_recruitment_ref_number,
            'message' => '>PURCHASE ORDER for the aforesaid RFH is RATIFIED and Resend to OAT for MODIFICATION.',
        ];

        // \Mail::send('emails.OfferRatifiedPoMail', array('details' => $details), function($message) use($to_email, $cc_emails, $bcc_emails){
            \Mail::send('emails.OfferRatifiedPoMail', array('details' => $details), function($message) use($to_email){
            $message->subject('CAREERS@HEPL: RESEND PO DETAILS ');
            $message->to($to_email);
            $message->cc(['karthik.d@hepl.com','rfh@hepl.com']);
           // $message->cc(['durgadevi.r@hemas.in','rfh@hemas.in']);

        });

       // offer release followup entry
       $created_by = $get_assigned_to->empID;

        $or_followup_input = array(
            'cdID' => $req->input('cdID'),
            'rfh_no' => $req->input('rfh_no'),
            'hepl_recruitment_ref_number' => $get_candidate_details_result[0]->hepl_recruitment_ref_number,
            'description' => "RFH is Ratified and  Resend to OAT for Modification by BH",
            'created_by' => $created_by,
        );
        $put_or_followup_result = $this->recrepo->offer_release_followup_entry( $or_followup_input );

        $response = 'success';
        return response()->json( ['response' => $response] );
    }
  public function get_candidate_details(Request $req){
    $cdID = base64_decode($req->input('cid'));
    $input_details_cin = array(
        'cdID' =>$cdID,
    );
    $get_candidate_details_result = $this->recrepo->get_candidate_details_ed( $input_details_cin );
  //print_r( $get_candidate_details_result[0]->leader_status);
    return response()->json( ['response' => $get_candidate_details_result[0]->leader_status] );
}
public function send_po_finance_l(Request $req){

        $get_assigned_to = auth()->user();

        // update candidate detail tbl
        $input_details_cd = array(
            'cdID' => $req->input('cdID'),
            'po_finance_status' => 1,
            'leader_status' => 2,
        );
        // $input_details_fin = array(
        //     'cdID' => $cdID,
        //     'po_finance_status' => 1,
        //     'leader_status' => 2

        // );

        // $update_cd_result = $this->payrepo->update_po_finance_status($input_details_fin);
        $update_cd_result = $this->payrepo->update_po_finance_status($input_details_cd);

        // send maill to finance code
        $input_details_ad = array(
            'empID' => '900072',
        );

        $get_user_result = $this->corepo->get_user_details($input_details_ad);

        $to_email=$get_user_result[0]->email;

        $input_details_c = array(
            'cdID' => $req->input('cdID'),
        );
        $get_candidate_details_result = $this->recrepo->get_candidate_details_ed( $input_details_c );
     // print_r( $input_details_c);
        $input_details_rr = array(
            'rfh_no' => $req->input('rfh_no'),
        );

        $get_tblrfh_result = $this->corepo->get_tblrfh_details($input_details_rr);


        $details = [
            'candidate_name' => $get_candidate_details_result[0]->candidate_name,
            'candidate_position' => $get_tblrfh_result[0]->position_title,
            'rfh_no' => $get_candidate_details_result[0]->hepl_recruitment_ref_number,
            'message' => 'PURCHASE ORDER for the aforesaid RFH is RATIFIED and submitted to Finance for VERIFICATION.',
        ];
  $input_details_ad = array(
        'empID' => '900093',
    );

    $get_oat_result = $this->corepo->get_user_details($input_details_ad);
    $oat_email=$get_oat_result[0]->email;
        // \Mail::send('emails.OfferRatifiedPoMail', array('details' => $details), function($message) use($to_email, $cc_emails, $bcc_emails){
            \Mail::send('emails.OfferRatifiedPoMail', array('details' => $details), function($message) use($to_email,$oat_email){
            $message->subject('CAREERS@HEPL: OFFER RATIFIED ');
            $message->to($to_email);
            $message->cc(['karthik.d@hepl.com','rfh@hepl.com',$oat_email]);
          //  $message->cc(['durgadevi.r@hemas.in','rfh@hemas.in']);
            // if(count($cc_emails) >1){
            //     $message->cc($cc_emails);
            // }
            // if(count($bcc_emails) >1){
            //     $message->bcc($bcc_emails);
            // }
        });

        // offer release followup entry
        $created_by = $get_assigned_to->empID;

        $or_followup_input = array(
            'cdID' => $req->input('cdID'),
            'rfh_no' => $req->input('rfh_no'),
            'hepl_recruitment_ref_number' => $get_candidate_details_result[0]->hepl_recruitment_ref_number,
            'description' => "RFH is Ratified and submitted to Finance for Verification",
            'created_by' => $created_by,
        );
        $put_or_followup_result = $this->recrepo->offer_release_followup_entry( $or_followup_input );


        $response = 'success';
        return response()->json( ['response' => $response] );
    }

    public function process_ld_approval(Request $req){
        $get_assigned_to = auth()->user();
        $created_by = $get_assigned_to->empID;

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
            'footer_from' => 'BUSINESS HEAD',
        ];

        if($req->input('po_type') =='po'){

            if($req->input('ld_status') == 2){
                // approved
                if($req->input('po_file_status') ==1){
                    $payroll_status = 3;
                }else{
                    $payroll_status = 2;
                }
                $input_details_cd =[
                    'cdID' => $req->input('cdID'),
                    'rfh_no' => $req->input('rfh_no'),
                    'leader_status' =>$req->input('ld_status'),
                    'payroll_status' =>$payroll_status,
                ];

                $update_approval_process_result = $this->ldrepo->update_approval_process($input_details_cd);

                $input_details_ad = array(
                    'empID' => '900093',
                );

                $get_user_result = $this->corepo->get_user_details($input_details_ad);

                $to_email=$get_user_result[0]->email;

                \Mail::send('emails.BhOfferApproved', array('details' => $details), function($message) use($to_email){
                    $message->subject('CAREERS@HEPL: OFFER APPROVED');
                    $message->to($to_email);
                    $message->cc(['karthik.d@hepl.com','rfh@hepl.com']);
                        // if(count($cc_emails) >1){
                        //     $message->cc($cc_emails);
                        // }
                        // if(count($bcc_emails) >1){
                        //     $message->bcc($bcc_emails);
                        // }
                });

                $or_followup_input = array(
                    'cdID' => $req->input('cdID'),
                    'rfh_no' => $req->input('rfh_no'),
                    'hepl_recruitment_ref_number' => $get_candidate_details_result[0]->hepl_recruitment_ref_number,
                    'description' => "Offer Approved",
                    'created_by' => $created_by,
                );
                $put_or_followup_result = $this->recrepo->offer_release_followup_entry( $or_followup_input );

            }else{

                // rejected
                if($req->input('ld_reject_type') =='offer_letter_reject'){
                    $input_details_cd =[
                        'cdID' => $req->input('cdID'),
                        'rfh_no' => $req->input('rfh_no'),
                        'ld_reject_type' => $req->input('ld_reject_type'),
                        'ld_reject_remark' => $req->input('ld_remark'),
                        // 'po_finance_status' =>0,
                        'leader_status' =>$req->input('ld_status'),
                        'payroll_status' =>0,
                    ];

                    $update_approval_process_result = $this->ldrepo->update_approval_process($input_details_cd);

                    $input_details_ad = array(
                        'empID' => $get_candidate_details_result[0]->created_by,
                    );

                    $get_user_result = $this->corepo->get_user_details($input_details_ad);

                    $to_email=$get_user_result[0]->email;

                    \Mail::send('emails.BhOfferReturned', array('details' => $details), function($message) use($to_email){
                        $message->subject('CAREERS@HEPL: OFFER RETURNED');
                        $message->to($to_email);
                        $message->cc(['karthik.d@hepl.com','rfh@hepl.com']);
                            // if(count($cc_emails) >1){
                            //     $message->cc($cc_emails);
                            // }
                            // if(count($bcc_emails) >1){
                            //     $message->bcc($bcc_emails);
                            // }
                    });

                    $or_followup_input = array(
                        'cdID' => $req->input('cdID'),
                        'rfh_no' => $req->input('rfh_no'),
                        'hepl_recruitment_ref_number' => $get_candidate_details_result[0]->hepl_recruitment_ref_number,
                        'description' => "Offer Returned for CTC",
                        'created_by' => $created_by,
                    );
                    $put_or_followup_result = $this->recrepo->offer_release_followup_entry( $or_followup_input );


                }elseif($req->input('ld_reject_type') =='po_reject'){
                    $input_details_cd =[
                        'cdID' => $req->input('cdID'),
                        'rfh_no' => $req->input('rfh_no'),
                        'leader_status' =>$req->input('ld_status'),
                        'ld_reject_type' => $req->input('ld_reject_type'),
                        'ld_reject_remark' => $req->input('ld_remark'),
                        // 'po_finance_status' =>0,
                        'payroll_status' =>1,

                    ];

                    $update_approval_process_result = $this->ldrepo->update_approval_process($input_details_cd);

                    $input_details_ad = array(
                        'empID' => '900093',
                    );

                    $get_user_result = $this->corepo->get_user_details($input_details_ad);

                    $to_email=$get_user_result[0]->email;

                    \Mail::send('emails.FinancePoReturned', array('details' => $details), function($message) use($to_email){
                        $message->subject('CAREERS@HEPL: PURCHASE ORDER RETURNED');
                        $message->to($to_email);
                        $message->cc(['karthik.d@hepl.com','rfh@hepl.com']);
                            // if(count($cc_emails) >1){
                            //     $message->cc($cc_emails);
                            // }
                            // if(count($bcc_emails) >1){
                            //     $message->bcc($bcc_emails);
                            // }
                    });

                    $or_followup_input = array(
                        'cdID' => $req->input('cdID'),
                        'rfh_no' => $req->input('rfh_no'),
                        'hepl_recruitment_ref_number' => $get_candidate_details_result[0]->hepl_recruitment_ref_number,
                        'description' => "Offer Returned for PO",
                        'created_by' => $created_by,
                    );
                    $put_or_followup_result = $this->recrepo->offer_release_followup_entry( $or_followup_input );


                }


            }

        }else{
            if($req->input('ld_status') == 2){
                $input_details_cd =[
                    'cdID' => $req->input('cdID'),
                    'rfh_no' => $req->input('rfh_no'),
                    'leader_status' =>$req->input('ld_status'),
                    'payroll_status' =>3,
                ];

                $update_approval_process_result = $this->ldrepo->update_approval_process($input_details_cd);

                $input_details_ad = array(
                    'empID' => '',
                );
/// offer release process for non po /////////////////////////////////////////////////////////////////////////////////////

$cdID = $req->input('cdID');
//echo $cdID;
$input_details_c = array(
    'cdID'=>$cdID,
);

   // update status in candidate details table
   $input_details_cd = array(
    'cdID'=>$cdID,
    'status'=>"Offer Released",
    'offer_rel_status'=>"1"

);

$cd_status_update = $this->recrepo->cd_status_update_ors( $input_details_cd );

$get_candidate_details_result = $this->recrepo->get_candidate_details_ed( $input_details_c );

// update candidate follow up
$cfdID = 'CFD'.( ( DB::table( 'candidate_followup_details' )->max( 'id' ) )+1 );

$candidate_followup_details = array(
    'cfdID'=>$cfdID,
    'cdID'=>$cdID,
    'rfh_no'=>$get_candidate_details_result[0]->hepl_recruitment_ref_number,
    'follow_up_status'=>"Offer Released",
    'created_on'=>date('Y-m-d'),
    'created_by'=>$created_by
);
Candidate_followup_details::create($candidate_followup_details);



$input_details_rr = array(
    'rfh_no' => $get_candidate_details_result[0]->rfh_no,
);
$get_tblrfh_result = $this->corepo->get_recruitment_edit_details($input_details_rr);
$doc_upload_link =  " http://127.0.0.1:8080";
//$doc_upload_link =  "http://216.48.177.166/budgie/public/index.php/";

//$doc_upload_link =  "http://216.48.177.166/budgie/public/index.php/";
$to_email=$get_candidate_details_result[0]->candidate_email;

$get_title = "CAREERS@HEPL:  OFFER LETTER";
$get_body1 = "Dear Mr / Ms ".$get_candidate_details_result[0]->candidate_name;
$get_body2 ='CONGRATULATIONS !  We are delighted to offer you the position of '.$get_tblrfh_result[0]->position_title.' in the Band '.$get_tblrfh_result[0]->band;
$get_body3 ='You are expected to join us on or before '.$get_candidate_details_result[0]->or_doj.' This offer automatically stands cancelled in the event of you not joining on the said date. ';
$get_body4 ='Please reach out to our Talent Acquisition Specialist for any clarifications related to the offer. ';
$get_body5 ='We wish you a very rewarding career with us. ';
$get_body6 =$doc_upload_link;
$get_body7 ="Use your Login id and updated password to sign in(already you got in document collection mail)";
$details = [
            'title' => $get_title,
            'body1' => $get_body1,
            'body2' => $get_body2,
            'body3' => $get_body3,
            'body4' => $get_body4,
            'body5' => $get_body5,
            'body6' => $get_body6,
            'body7' => $get_body7,
];
   // ->cc(['durgadevi.r@hemas.in','rfh@hemas.in'])
\Mail::to($to_email)
->cc(['karthik.d@hepl.com','rfh@hepl.com'])
->send(new \App\Mail\MyTestMail($details));



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                // $get_user_result = $this->corepo->get_user_details($input_details_ad);

                // $to_email=$get_user_result[0]->email;

                // \Mail::send('emails.BhOfferApproved', array('details' => $details), function($message) use($to_email){
                //     $message->subject('CAREERS@HEPL: OFFER APPROVED');
                //     $message->to($to_email);
                //     $message->cc(['karthikdavid@hemas.in','rfh@hemas.in']);
                //         // if(count($cc_emails) >1){
                //         //     $message->cc($cc_emails);
                //         // }
                //         // if(count($bcc_emails) >1){
                //         //     $message->bcc($bcc_emails);
                //         // }
                // });

                $or_followup_input = array(
                    'cdID' => $req->input('cdID'),
                    'rfh_no' => $req->input('rfh_no'),
                    'hepl_recruitment_ref_number' => $get_candidate_details_result[0]->hepl_recruitment_ref_number,
                    'description' => "Offer Approved",
                    'created_by' => $created_by,
                );
                $put_or_followup_result = $this->recrepo->offer_release_followup_entry( $or_followup_input );

            }else{
                $input_details_cd =[
                    'cdID' => $req->input('cdID'),
                    'rfh_no' => $req->input('rfh_no'),
                    'ld_reject_type' => $req->input('ld_reject_type'),
                    'ld_reject_remark' => $req->input('ld_remark'),
                    // 'po_finance_status' =>0,
                    'leader_status' =>$req->input('ld_status'),
                    'payroll_status' =>0,
                ];

                $update_approval_process_result = $this->ldrepo->update_approval_process($input_details_cd);

                $input_details_ad = array(
                    'empID' => $get_candidate_details_result[0]->created_by,
                );

                $get_user_result = $this->corepo->get_user_details($input_details_ad);

                $to_email=$get_user_result[0]->email;

                \Mail::send('emails.BhOfferReturned', array('details' => $details), function($message) use($to_email){
                    $message->subject('CAREERS@HEPL: OFFER RETURNED');
                    $message->to($to_email);
                    $message->cc(['karthik.d@hepl.com','rfh@hepl.com']);
                        // if(count($cc_emails) >1){
                        //     $message->cc($cc_emails);
                        // }
                        // if(count($bcc_emails) >1){
                        //     $message->bcc($bcc_emails);
                        // }
                });

                $or_followup_input = array(
                    'cdID' => $req->input('cdID'),
                    'rfh_no' => $req->input('rfh_no'),
                    'hepl_recruitment_ref_number' => $get_candidate_details_result[0]->hepl_recruitment_ref_number,
                    'description' => "Offer Returned for CTC",
                    'created_by' => $created_by,
                );
                $put_or_followup_result = $this->recrepo->offer_release_followup_entry( $or_followup_input );


            }
        }


        $response = 'success';
        return response()->json( ['response' => $response] );

    }

    public function get_cor_ld_ao(Request $request){
        if ($request->ajax()) {

            // get all data ///
            $session_user_details = auth()->user();
            $created_by = $session_user_details->empID;

                $input_details = array(
                    'created_by'=>$created_by,
                    'leader_status'=>"2",
                   // 'payroll_status'=>"2",

                );

                $get_candidate_profile_result = $this->payrepo->get_approved_offers( $input_details );


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


                ->addColumn('offer_letter_preview',function($row){


                        $btn = '<a href="'.asset('public/'.$row->offer_letter_filename).'" target="_blank"><span class="badge bg-primary" id="" title="Preview Offer Letter"><i class="bi bi-eye"></i></span></a>';

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
                ->addColumn('po_type',function($row){

                    if($row->po_type =='po'){
                        // $btn = '<span  class="badge bg-success">PO</span>';
                        $btn ='';
                        // if($row->po_finance_status ==0 || $row->po_finance_status ==3 || $row->	po_file_status ==0){

                            $encode_rfhno = base64_encode($row->rfh_no);
                            $encode_cdID = base64_encode($row->cdID);

                            $btn .= '<div class="dropdown dropdown-color-icon">';
                                $btn .= '<button class="btn btn-success btn-sm btn-smn dropdown-toggle" type="button" id="dropdownMenuButtonEmoji" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 0.01rem 0.4rem !important;">';
                                    $btn .= '<span class="me-50"></span>PO';
                                $btn .= '</button>';

                                $btn .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuButtonEmoji" style="box-shadow: 0 0 30px rgb(0 0 0 / 40%) !important;">';
                                    if($row->po_letter_filename !=''){
                                        $btn .= '<a class="dropdown-item" href="edit_po_details_bh?rfh_no='.$encode_rfhno.'&cdID='.$encode_cdID.'" style="border:unset !important;"><i class="bi bi-pen-fill"></i> Edit PO Components</a>';
                                        $btn .= '<div class="dropdown-divider"></div>';
                                        $btn .= '<a class="dropdown-item" href="../po_letter/'.$row->cdID.'/'.$row->po_letter_filename.'"  style="border:unset !important;" target="_blank"><i class="bi bi-eye-fill"></i> Preview PO Input</a>';
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

                        // }
                        // else{
                        //     $btn .= ' <span class="badge bg-dark disabled"><i class="bi bi-pencil"></i></span>';
                        //     // $btn .= ' <span class="badge bg-secondary   "><i class="bi bi-eye" onclick="view_po_pop('."'".$row->rfh_no."'".','."'".$row->cdID."'".');"></i></span>';

                        // }
                    }elseif($row->po_type =='non_po'){
                        $btn = '<span  class="badge bg-danger">NonPO</span>';
                    }else{
                        $btn='';
                    }



                    return $btn;
                })
                ->rawColumns(['finance_status','history','c_doc_status','payroll_status','offer_letter_preview','po_type'])
                ->make(true);

        }
    }

    public function get_po_details_ld(Request $req){
        $input_details_cd = array(
            'cdID' => $req->input('cdID'),
        );

        $get_po_details_result = $this->payrepo->get_po_details($input_details_cd);

        return $get_po_details_result;
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
    public function cd_preview_bh(){
        return view('leader/cd_preview');

    }
    public function get_candidate_preview_details(Request $req){

        $input_details = array(
            'cdID'=>$req->input('cdID'),
        );


        $get_candidate_details_result = $this->recrepo->get_candidate_details_ed( $input_details );

        $get_candidate_edu_result = $this->recrepo->get_candidate_edu_details( $input_details );

        $get_candidate_exp_result = $this->recrepo->get_candidate_exp_details( $input_details );

        $get_candidate_benefits_result = $this->recrepo->get_candidate_benefits_details( $input_details );
        $get_remark_detail = $this->recrepo->get_offer_release_details( $input_details );

        return response()->json( [
            'candidate_basic_details' => $get_candidate_details_result,
            'candidate_education' => $get_candidate_edu_result,
            'candidate_experience' => $get_candidate_exp_result,
            'candidate_benefits' => $get_candidate_benefits_result,
            'remark' => $get_remark_detail,

            ] );

    }

    public function update_cdoc_status(Request $req){

        $input_details = array(
            'cdID'=>$req->input('cdID'),
            'c_doc_status'=>$req->input('c_doc_status'),
            'c_doc_remark'=>$req->input('c_doc_remark'),
        );

        $process_cdoc_status_result = $this->recrepo->process_cdoc_status( $input_details );

        $user_row = $this->recrepo->get_candidate_row( $req->input('cdID') );

        $to_email = $user_row[0]->candidate_email;
        if($req->input('c_doc_status') =='Not Verified'){
            $details = [
                'candidate_name' => $user_row[0]->candidate_name,
                'remark' => $req->input('c_doc_remark'),
            ];

            \Mail::to($to_email)->send(new \App\Mail\NotifyCandidateDocNotVerifiedMail($details));
        }else{

            $details = [
                'candidate_name' => $user_row[0]->candidate_name,
            ];

            \Mail::to($to_email)->send(new \App\Mail\NotifyCandidateDocVerifiedMail($details));
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
    public function get_candidate_for_budgie(Request $req){
        $input_details_c = array(
            'cdID' => $req->input('can_id'),
        );
        $get_candidate_details_result = $this->recrepo->get_candidate_details_ed( $input_details_c );

        $rfh = array(
            'rfh_no' => $get_candidate_details_result[0]->rfh_no,
        );
        $get_candidate_rfh = $this->recrepo->get_candidate_rfh( $rfh );

        return response()->json( ['response' => 'success','candidate' => $get_candidate_details_result,'rfh' => $get_candidate_rfh] );

    }
}
