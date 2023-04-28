<?php

namespace App\Http\Controllers;
use App\Repositories\ICoordinatorRepository;
use App\Repositories\IPayrollRepository;
use App\Repositories\IRecruiterRepository;
use App\Repositories\IFinanceRepository;

use Illuminate\Http\Request;
use DataTables;
use DateTime;
use DB;
use Mail;
use File;

class PoteamController extends Controller
{
    public function __construct(IFinanceRepository $fnrepo,IPayrollRepository $payrepo,ICoordinatorRepository $corepo,IRecruiterRepository $recrepo)
    {
        $this->corepo = $corepo;
        $this->payrepo = $payrepo;
        $this->recrepo = $recrepo;
        $this->fnrepo = $fnrepo;

        $this->middleware('po_team');
    }

    public function offers_poteam(Request $request){

        if ($request->ajax()) {

            // get all data
            $session_user_details = auth()->user();
            $created_by = $session_user_details->empID;

                $input_details = array(
                    'client_type'=>"Internal Po",
                );

                $get_candidate_profile_result = $this->fnrepo->get_pending_client_request( $input_details );


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
                            if($row->fn_po_attach !=''){
                                $btn .= '<div class="dropdown-divider"></div>';
                                $btn .= '<a class="dropdown-item" href="../fn_po_attach/'.$row->cdID.'/'.$row->fn_po_attach.'"  style="border:unset !important;" target="_blank"><i class="bi bi-eye-fill"></i> Preview Finance PO </a>';
                            }
                            if( $row->client_po_number !=''){
                                $btn .= '<div class="dropdown-divider"></div>';
                                $btn .= '<a class="dropdown-item" href="#" style="border:unset !important;" "> PO NUMBER: '.$row->client_po_number.'</a>';
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
                ->addColumn('po_action',function($row){

                   // if($row->po_finance_status ==0 || $row->po_finance_status ==3){
                               // $btn = '<span class="badge bg-success" onclick="po_popbtn('."'".$row->rfh_no."'".','."'".$row->cdID."'".');"><i class="bi bi-pencil"></i></span>';

                          //  }else{
                                $btn = '<span class="badge bg-dark"><i class="bi bi-pencil" onclick="show_cpo_pop('."'".$row->rfh_no."'".','."'".$row->cdID."'".','."'".$row->leader_status."'".','."'".$row->po_finance_status."'".');"></i></span>';

                          //  }


                    return $btn;
                })
                ->rawColumns(['history','c_doc_status','payroll_status','offer_letter_preview','po_type','ageing','po_action'])
                ->make(true);

        }
        return view('po_team/po_team');

    }


    public function process_fn_postatus(Request $req){
        $get_assigned_to = auth()->user();
        $created_by = $get_assigned_to->empID;

        $input_details_rr = array(
            'rfh_no' => $req->input('po_rfh_no'),
        );

        $get_tblrfh_result = $this->corepo->get_tblrfh_details($input_details_rr);


        $input_details_c = array(
            'cdID' => $req->input('po_cdID'),
        );
        $get_candidate_details_result = $this->recrepo->get_candidate_details_ed( $input_details_c );

        $details = [
            'candidate_name' => $get_candidate_details_result[0]->candidate_name,
            'candidate_position' => $get_tblrfh_result[0]->position_title,
            'rfh_no' => $get_candidate_details_result[0]->hepl_recruitment_ref_number,
            'footer_from' => 'FINANCE SPECIALIST',
        ];


        if($req->input('fn_status') =='2'){

            if($req->hasFile('fn_attach')){
                $path = public_path().'/fn_po_attach/'.$req->input('po_cdID');

                File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);

                $foo = File::extension($req->file('fn_attach')->getClientOriginalName());

                $fn_po_attach = $req->input('po_rfh_no').'_'.$req->input('po_cdID').'.'.$foo;

                $image = $req->file('fn_attach');

                if (\File::exists($path)) \File::deleteDirectory($path);

                $image->move($path, $fn_po_attach);

                $leader_status = 2;

                $input_details_cd = array(
                    'cdID' => $req->input('po_cdID'),
                    'po_finance_status' => $req->input('fn_status'),
                    'leader_status' => $leader_status,
                    'fn_po_attach' => $fn_po_attach,
                );

            }
            else{

                $leader_status = 2;

                $input_details_cd = array(
                    'cdID' => $req->input('po_cdID'),
                    'po_finance_status' => $req->input('fn_status'),
                    'leader_status' => $leader_status,
                );

            }


            $update_cd_result = $this->fnrepo->update_finance_status_cd($input_details_cd);

            $input_details_ad = array(
                'empID' => $req->input('approver'),
            );

            $get_user_result = $this->corepo->get_user_details($input_details_ad);

            $to_email=$get_user_result[0]->email;


            $input_details_ad = array(
                'empID' => '900093',
            );

            $get_oat_result = $this->corepo->get_user_details($input_details_ad);
            $oat_email=$get_oat_result[0]->email;
//echo $oat_email;
           // $message->cc(['durgadevi.r@hemas.in','rfh@hemas.in']);
            \Mail::send('emails.FinancePoVerified', array('details' => $details), function($message) use($to_email,$oat_email){
                $message->subject('CAREERS@HEPL: PURCHASE ORDER VERIFIED');
                $message->to($to_email);
                //$message->bcc($oat_email);
                $message->cc(['karthik.d@hepl.com','rfh@hepl.com',$oat_email]);
                    // if(count($cc_emails) >1){
                    //     $message->cc($cc_emails);
                    // }
                    // if(count($bcc_emails) >1){
                    //     $message->bcc($bcc_emails);
                    // }
            });


            $or_followup_input = array(
                'cdID' => $req->input('po_cdID'),
                'rfh_no' => $req->input('po_rfh_no'),
                'hepl_recruitment_ref_number' => $get_candidate_details_result[0]->hepl_recruitment_ref_number,
                'description' => "Purchase Order Verified",
                'created_by' => $created_by,
            );
            $put_or_followup_result = $this->recrepo->offer_release_followup_entry( $or_followup_input );


        }else{
            if($req->hasFile('fn_attach')){

                $path = public_path().'/fn_po_attach/'.$req->input('po_cdID');

                File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);

                $foo = File::extension($req->file('fn_attach')->getClientOriginalName());

                $fn_po_attach = $req->input('po_rfh_no').'_'.$req->input('po_cdID').'.'.$foo;

                $image = $req->file('fn_attach');

                if (\File::exists($path)) \File::deleteDirectory($path);

                $image->move($path, $fn_po_attach);

                $leader_status = 2;

                $input_details_cd = array(
                    'cdID' => $req->input('po_cdID'),
                    'po_finance_status' => $req->input('fn_status'),
                    'leader_status' => $leader_status,
                    'fn_po_remark' => $req->input('fn_remark'),
                    'fn_po_attach' => $fn_po_attach,
                );
            }
            else{
                $fn_po_attach ='';

                $leader_status = 2;

                $input_details_cd = array(
                    'cdID' => $req->input('po_cdID'),
                    'po_finance_status' => $req->input('fn_status'),
                    'leader_status' => $leader_status,
                    'fn_po_remark' => $req->input('fn_remark'),
                );
            }



            $update_cd_result = $this->fnrepo->update_finance_status_cd($input_details_cd);

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
                'cdID' => $req->input('po_cdID'),
                'rfh_no' => $req->input('po_rfh_no'),
                'hepl_recruitment_ref_number' => $get_candidate_details_result[0]->hepl_recruitment_ref_number,
                'description' => "Purchase Order Returned",
                'created_by' => $created_by,
            );
            $put_or_followup_result = $this->recrepo->offer_release_followup_entry( $or_followup_input );


        }

        $response = 'success';
        return response()->json( ['response' => $response] );
    }

    public function get_approved_po_fin(Request $request){
        if ($request->ajax()) {

            // get all data
            $session_user_details = auth()->user();
            $created_by = $session_user_details->empID;

            $input_details = array(
                'client_type'=>"Internal Po",
            );

                $get_candidate_profile_result = $this->fnrepo->get_approved_client_request( $input_details );


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

                ->addColumn('offer_letter_preview',function($row){


                        $btn = '<a href="'.asset('public/'.$row->offer_letter_filename).'" target="_blank"><span class="badge bg-primary" id="" title="Preview Offer Letter"><i class="bi bi-eye"></i></span></a>';

                    return $btn;
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
                            if($row->po_file_status == '1'){
                                $btn .= '<div class="dropdown-divider"></div>';
                               // $btn .= '<a class="dropdown-item" href="#" style="border:unset !important;" >PO NUMBER: '.$row->client_po_number.'</a>';
                               $btn .= '<a class="dropdown-item" href="#" style="border:unset !important;" onclick="view_client_po('."'".$row->client_po_attach."'".','."'".$row->client_po_number."'".','."'".$row->client_po_value."'".','."'".$row->client_po_validity."'".','."'".$row->cdID."'".');"><i class="bi bi-upload"></i> View Client PO</a>';

                            }
                            // if($row->client_po_attach !=''){
                            //     $btn .= '<div class="dropdown-divider"></div>';
                            //     $btn .= '<a class="dropdown-item" href="../po_attachments/'.$row->cdID.'/'.$row->client_po_attach.'"  style="border:unset !important;" target="_blank"><i class="bi bi-eye-fill"></i> Preview Client PO </a>';
                            // }
                            // if( $row->client_po_number !=''){
                            //     $btn .= '<div class="dropdown-divider"></div>';
                            //     $btn .= '<a class="dropdown-item" href="#" style="border:unset !important;" "> PO NUMBER: '.$row->client_po_number.'</a>';
                            // }
                            // if($row->fn_po_attach !=''){
                            //     $btn .= '<div class="dropdown-divider"></div>';
                            //     $btn .= '<a class="dropdown-item" href="../fn_po_attach/'.$row->cdID.'/'.$row->fn_po_attach.'"  style="border:unset !important;" target="_blank"><i class="bi bi-eye-fill"></i> Preview Client PO </a>';
                            // }

                        $btn .= '</div>';
                    $btn .= '</div>';

                    }elseif($row->po_type =='non_po'){
                        $btn = '<span class="badge bg-danger">NonPO</span>';
                    }else{
                        $btn='';
                    }

                    return $btn;
                })
                ->rawColumns(['leader_status','finance_status','history','c_doc_status','payroll_status','action','offer_letter_preview','po_type'])
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
    public function upload_clientpo(Request $req){

        if($req->input('leader_status') ==2 && $req->input('leader_status') == 2){
            $payroll_status = 3;
        }else{
            $payroll_status = 3;

        }
        $client_po_no = $req->input('client_po_no');
        $client_po_value = $req->input('client_po_value');
        $client_po_validity = $req->input('client_po_vality');
        $client_po_update_date = $req->input('update_date');
        if($req->hasFile('client_po')){

            $path = public_path().'/po_attachments/'.$req->input('cpo_cdID');

            File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);

            $foo = File::extension($req->file('client_po')->getClientOriginalName());

            $po_attach = $req->input('cpo_rfh_no').'_'.$req->input('cpo_cdID').'.'.$foo;

            $image = $req->file('client_po');

            if (\File::exists($path)) \File::deleteDirectory($path);

            $image->move($path, $po_attach);

            $input_details_cd = array(
                'cdID' => $req->input('cpo_cdID'),
                'po_file_status' => 1,
                'client_po_attach' => $po_attach,
                'payroll_status' => $payroll_status,
                'client_po_update_date' => $client_po_update_date,
                'client_po_number' => $client_po_no,
                'client_po_value' => $client_po_value,
                'client_po_validity' => $client_po_validity,


            );
            $update_cd_result = $this->payrepo->update_po_finance_status($input_details_cd);



        }
        // else if($client_po_no !== ""){
        //     $input_details_cd = array(
        //         'cdID' => $req->input('cpo_cdID'),
        //         'po_file_status' => 1,
        //         'client_po_update_date' => $client_po_update_date,
        //         'payroll_status' => $payroll_status,
        //         'client_po_number' => $client_po_no,


        //     );
        //     $update_cd_result = $this->payrepo->update_po_finance_status($input_details_cd);
        // }
            $cdID=$req->input('cpo_cdID');
        $user_row = $this->recrepo->get_candidate_row( $cdID );
        $recruiter_id=$user_row[0]->created_by;

        $input_details_ad = array(
            'empID' => $recruiter_id,
        );
        $get_user_result = $this->corepo->get_user_details($input_details_ad);
      //  print_r($input_details_ad);
        //$recruit_email=$get_user_result[0]->email;
        $recruit_email = "durga.r@hepl.com";
        $get_position_result = $this->recrepo->get_recr_req($user_row[0]->hepl_recruitment_ref_number);


            $details = [
                'candidate_name' => $user_row[0]->candidate_name,
                'candidate_position' => $get_position_result[0]->position_title,
                'rfh_no' => $user_row[0]->rfh_no,
            ];
            \Mail::send('emails.ClientPoMail', array('details' => $details), function($message) use($recruit_email){
                $message->subject('CAREERS@HEPL: OFFER RATIFIED ');
                $message->to($recruit_email);
                $message->cc(['karthik.d@hepl.com','rfh@hepl.com']);
            });





            $response = 'success';
            return response()->json( ['response' => $response] );


    }
}
