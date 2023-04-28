<?php

namespace App\Http\Controllers;
use App\Repositories\IPayrollRepository; 
use App\Repositories\ICoordinatorRepository; 
use App\Repositories\IRecruiterRepository; 

use Illuminate\Http\Request;
use DataTables;
use PDF;
use File;
use DB;
use App\Models\Podetails;

class PayrollController extends Controller
{
    public function __construct(IPayrollRepository $payrepo,ICoordinatorRepository $corepo,IRecruiterRepository $recrepo)
    {
        $this->payrepo = $payrepo;
        $this->corepo = $corepo;
        $this->recrepo = $recrepo;
        $this->middleware('payroll');
    }

    public function ol_payroll_verify(Request $request){

        if ($request->ajax()) {
           
            // get all data
            $session_user_details = auth()->user();
            $created_by = $session_user_details->empID;
                
                $input_details = array(
                    'leader_status'=>"2",
                    'finance_status'=>"2",
                    'payroll_status'=>"3",
                    'payroll_status_ip'=>"0",
                    'offer_rel_status'=>"0",
                    
                );
    
                $get_candidate_profile_result = $this->payrepo->get_candidate_profile_ps( $input_details );
    
            
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
                        if($row->payroll_status == 1){
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
                         else if($row->po_letter_filename !='' || $row->leader_status == 3  || $row->po_finance_status ==0 || $row->po_finance_status ==3){
                                $btn = '<span class="badge bg-primary pointer" id="action_btn_po'.$row->cdID.'" onclick="approver_ld_po_pop('."'".$row->rfh_no."'".','."'".$row->cdID."'".','."'".$row->po_type."'".','."'".$row->approver_id."'".');">Submit</span>';

                         }
                        else{
                            $btn = '<span class="badge bg-info disabled" disabled>Sent</span>';

                        }

                       
                    }

                    return $btn;

                })
                ->addColumn('offer_letter_preview',function($row){

                    
                        $btn = '<a href="../'.$row->offer_letter_filename.'" target="_blank"><span class="badge bg-primary pointer" id="" title="Preview Offer Letter"><i class="bi bi-eye"></i></span></a>';
                        
                        // if($row->leader_status =='0' || $row->leader_status =='3'){
                        //     $btn .= ' <a href="edit_ctc_oat?cdID='.$row->cdID.'&rfh_no='.$row->rfh_no.'" target="_blank"><span style="margin-top:2px;" class="badge bg-dark pointer" id="" title="Edit Offer Letter"><i class="bi bi-pencil"></i></span></a>';

                        // }else{
                        //     $btn .= ' <span style="margin-top:2px;" class="badge bg-dark disabled" id="" title="Edit Offer Letter"><i class="bi bi-pencil"></i></span>';

                        // }

                  
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
        
        return view('payroll/ol_payroll_verify');

    }

    public function add_po_details(){
        return view('payroll/add_po_details');
    }
    
    public function candidate_follow_up_history_oat(Request $req){
        
        $input_details = array(
            'cdID'=>$req->input('cdID'),
        );

        $cfu_history_result = $this->payrepo->candidate_follow_up_history( $input_details );

         // get position details
         $input_details_pd = array(
            'hepl_recruitment_ref_number'=>$req->input('hepl_recruitment_ref_number'),
            'assigned_to'=>$req->input('created_by'),
        );

        $cfu_history_pd_result = $this->payrepo->candidate_follow_up_history_pd( $input_details_pd );

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
    
    public function edit_ctc_oat(){
        return view('payroll/edit_ctc_oat');
    }

    public function get_ctc_edit_oat(Request $req){

        $input_details = array(
            'cdID'=>$req->input('cdID'),
        );
        $ctc_calc_result = $this->payrepo->get_ctc_calculation( $input_details );

        return $ctc_calc_result;
    }

    public function update_ctc_edit(Request $req){

        $sc_basic_pm = $req->input('basic_pm');
        $sc_basic_pa = $req->input('basic_pa');
        $sc_hra_pm = $req->input('hra_pm');
        $sc_hra_pa = $req->input('hra_pa');
        $sc_medical_allowance_pm = $req->input('medi_al_pm');
        $sc_medical_allowance_pa = $req->input('medi_al_pa');
        $sc_conveyance_expence_pm = $req->input('conv_pm');
        $sc_conveyance_expence_pa = $req->input('conv_pa');
        $sc_special_allowance_pm = $req->input('spl_al_pm');
        $sc_special_allowance_pa = $req->input('spl_al_pa');
        $sc_monthly_gross_pm_a = $req->input('comp_a_pm');
        $sc_monthly_gross_pa_a = $req->input('comp_a_pa');

        $emp_pf_cont_pm = $req->input('ec_pf_pm');
        $emp_pf_cont_pa = $req->input('ec_pf_pa');
        $emp_esi_cont_pm = $req->input('ec_esi_pm');
        $emp_esi_cont_pa = $req->input('ec_esi_pa');
        $sub_total_b_pm = $req->input('sub_totalb_pm');
        $sub_total_b_pa = $req->input('sub_totalb_pa');

        $gratity_pm = $req->input('st_bonus_pm');
        $gratity_pa = $req->input('st_bonus_pa');
        $bonus_pm = $req->input('st_bonus_pm');
        $bonus_pa = $req->input('st_bonus_pa');
        $sub_total_c_pm = $req->input('sub_totalc_pm');
        $sub_total_c_pa = $req->input('sub_totalc_pa');
        $abc_ctc_pm = $req->input('abc_pm');
        $abc_ctc_pa = $req->input('abc_pa');
        $netpay = $req->input('net_pay');
        
        $user_details = auth()->user();
        $modified_by = $user_details->empID;

        $or_recruiter_name = $user_details->name;
        $or_recruiter_email = $user_details->email;
        $or_recruiter_mobile_no = $user_details->mobile_no;


        $ctc_calculation_data =[
            'cdID' => $req->input('cdID'), 
            'basic_pm' => $sc_basic_pm, 
            'basic_pa' => $sc_basic_pa, 
            'hra_pm' => $sc_hra_pm, 
            'hra_pa' => $sc_hra_pa, 
            'medi_al_pm' => $sc_medical_allowance_pm, 
            'medi_al_pa' => $sc_medical_allowance_pa, 
            'conv_pm' => $sc_conveyance_expence_pm, 
            'conv_pa' => $sc_conveyance_expence_pa, 
            'spl_al_pm' => $sc_special_allowance_pm, 
            'spl_al_pa' => $sc_special_allowance_pa, 
            'comp_a_pm' => $sc_monthly_gross_pm_a, 
            'comp_a_pa' => $sc_monthly_gross_pa_a, 
            'ec_pf_pm' => $emp_pf_cont_pm, 
            'ec_pf_pa' => $emp_pf_cont_pa, 
            'ec_esi_pm' => $emp_esi_cont_pm, 
            'ec_esi_pa' => $emp_esi_cont_pa, 
            'sub_totalb_pm' => $sub_total_b_pm, 
            'sub_totalb_pa' => $sub_total_b_pa, 
            'gratuity_pm' => $gratity_pm, 
            'gratuity_pa' => $gratity_pa, 
            'st_bonus_pm' => $bonus_pm, 
            'st_bonus_pa' => $bonus_pa, 
            'sub_totalc_pm' => $sub_total_c_pm, 
            'sub_totalc_pa' => $sub_total_c_pa, 
            'abc_pm' => $abc_ctc_pm, 
            'abc_pa' => $abc_ctc_pa, 
            'net_pay' => $netpay, 
            // 'created_by' => $created_by, 
            'modified_by' => $modified_by, 

        ];
        
        // check record already exits

        $path = public_path().'/offer_letter/'.$req->input('cdID');
        if (\File::exists($path)) \File::deleteDirectory($path);

        $update_ctc_calc_result = $this->recrepo->update_ctc_calc( $ctc_calculation_data );
        $logo_path = public_path('assets/images/logo/logo_bk.jpg');

        $amount_in_words = $this->convert_number($req->input('abc_pa'));
        $cur_date = date('d-m-Y');
        $accept_end_date = date('Y-m-d', strtotime($cur_date) + (24 * 3600 * 7));
        $input_details_rr = array(
            'rfh_no' => $req->input('rfh_no'),
        );

        $get_tblrfh_result = $this->corepo->get_tblrfh_details($input_details_rr);
        

        $input_details_cd = array(
            'cdID' => $req->input('cdID'),
        );

        $get_candidate_details_result = $this->corepo->get_candidate_details_ed($input_details_cd);
        

        // get buddy info
        $input_details_bi = array(
            'buddy_id' => $get_candidate_details_result[0]->welcome_buddy,
        );

        $get_buddy_result = $this
            ->corepo
            ->get_buddy_details($input_details_bi);

        $or_buddy_name = $get_buddy_result[0]->name;
        $or_buddy_email = $get_buddy_result[0]->email;
        $or_buddy_mobile_no = $get_buddy_result[0]->mobile_no;


        $data = [
            'date' => date('d-m-Y') , 
            'logo_path' => $logo_path, 
            'amount_in_words' => $amount_in_words, 
            'position_title' => $get_tblrfh_result[0]->position_title, 
            'join_date' => date("d-m-Y", strtotime($get_candidate_details_result[0]->or_doj)),
            'rfh_no' => $get_candidate_details_result[0]->hepl_recruitment_ref_number, 
            'closed_salary' => $this->moneyFormatIndia($abc_ctc_pa), 
            'candidate_name' => $get_candidate_details_result[0]->candidate_name, 
            'location' => $get_tblrfh_result[0]->location, 
            'business' => $get_tblrfh_result[0]->business,
            'function' => $get_tblrfh_result[0]->function, 
            'band_title' => $get_tblrfh_result[0]->band_title,
            'sc_basic_pm' => $this->moneyFormatIndia($sc_basic_pm) , 
            'sc_basic_pa' => $this->moneyFormatIndia($sc_basic_pa) , 
            'sc_hra_pm' => $this->moneyFormatIndia($sc_hra_pm) , 
            'sc_hra_pa' => $this->moneyFormatIndia($sc_hra_pa) , 
            'sc_conveyance_expence_pm' => $this->moneyFormatIndia($sc_conveyance_expence_pm) , 
            'sc_conveyance_expence_pa' => $this->moneyFormatIndia($sc_conveyance_expence_pa) , 
            'sc_medical_allowance_pm' => $this->moneyFormatIndia($sc_medical_allowance_pm) , 
            'sc_medical_allowance_pa' => $this->moneyFormatIndia($sc_medical_allowance_pa) , 
            'sc_special_allowance_pm' => $this->moneyFormatIndia($sc_special_allowance_pm) , 
            'sc_special_allowance_pa' => $this->moneyFormatIndia($sc_special_allowance_pa) , 
            'sc_monthly_gross_pm' => $this->moneyFormatIndia($sc_monthly_gross_pm_a) , 
            'sc_monthly_gross_pa' => $this->moneyFormatIndia($sc_monthly_gross_pa_a) , 
            'emp_pf_cont_pm' => $this->moneyFormatIndia($emp_pf_cont_pm) , 
            'emp_pf_cont_pa' => $this->moneyFormatIndia($emp_pf_cont_pa) , 
            'emp_esi_cont_pm' => $this->moneyFormatIndia($emp_esi_cont_pm), 
            'emp_esi_cont_pa' => $this->moneyFormatIndia($emp_esi_cont_pa), 
            'sub_total_b_pm' => $this->moneyFormatIndia($sub_total_b_pm) , 
            'sub_total_b_pa' => $this->moneyFormatIndia($sub_total_b_pa) , 
            'bonus_pm' => $this->moneyFormatIndia($bonus_pm) , 
            'bonus_pa' => $this->moneyFormatIndia($bonus_pa) , 
            'gratity_pm' => $this->moneyFormatIndia($gratity_pm) , 
            'gratity_pa' => $this->moneyFormatIndia($gratity_pa) , 
            'sub_total_c_pm' => $this->moneyFormatIndia($sub_total_c_pm) , 
            'sub_total_c_pa' => $this->moneyFormatIndia($sub_total_c_pa) , 
            'abc_ctc_pm' => $this->moneyFormatIndia($abc_ctc_pm) , 
            'abc_ctc_pa' => $this->moneyFormatIndia($abc_ctc_pa) , 
            'netpay' => $this->moneyFormatIndia($netpay) , 
            'or_recruiter_name' => $get_candidate_details_result[0]->or_recruiter_name, 
            'or_recruiter_email' => $get_candidate_details_result[0]->or_recruiter_email, 
            'or_recruiter_mobile_no' => $get_candidate_details_result[0]->or_recruiter_mobile_no, 
            'or_buddy_name' => $or_buddy_name, 
            'or_buddy_email' => $or_buddy_email, 
            'or_buddy_mobile_no' => $or_buddy_mobile_no, 
            'accept_end_date' => $accept_end_date,
            'department' => $get_candidate_details_result[0]->or_department,

        ];

        $pdf = PDF::loadView('offer_letter_pdf', $data);
        $path = public_path().'/offer_letter/'.$req->input('cdID');
        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);

        $fileName = time() . '.' . 'pdf';
        $pdf->save($path . '/' . $fileName);

        $offer_letter_filename = 'offer_letter/'.$req->input('cdID').'/'.$fileName;

        $input_details_cd =[
            'cdID' => $req->input('cdID'),
            'payrol_verify_type' => 'Edit Verified',
            'closed_salary_pa' => $abc_ctc_pa,
            'offer_letter_filename' => $offer_letter_filename,
        ];

        $update_candidate_details_result = $this->payrepo->update_candidate_details($input_details_cd);
        
        $response = 'success';
        return response()->json( ['response' => $response] );
    }

    function convert_number($amount)
    {
        $amount_after_decimal = round($amount - ($num = floor($amount)) , 2) * 100;
        // Check if there is any number after decimal
        $amt_hundred = null;
        $count_length = strlen($num);
        $x = 0;
        $string = array();
        $change_words = array(
            0 => '',
            1 => 'One',
            2 => 'Two',
            3 => 'Three',
            4 => 'Four',
            5 => 'Five',
            6 => 'Six',
            7 => 'Seven',
            8 => 'Eight',
            9 => 'Nine',
            10 => 'Ten',
            11 => 'Eleven',
            12 => 'Twelve',
            13 => 'Thirteen',
            14 => 'Fourteen',
            15 => 'Fifteen',
            16 => 'Sixteen',
            17 => 'Seventeen',
            18 => 'Eighteen',
            19 => 'Nineteen',
            20 => 'Twenty',
            30 => 'Thirty',
            40 => 'Forty',
            50 => 'Fifty',
            60 => 'Sixty',
            70 => 'Seventy',
            80 => 'Eighty',
            90 => 'Ninety'
        );
        $here_digits = array(
            '',
            'Hundred',
            'Thousand',
            'Lakh',
            'Crore'
        );
        while ($x < $count_length)
        {
            $get_divider = ($x == 2) ? 10 : 100;
            $amount = floor($num % $get_divider);
            $num = floor($num / $get_divider);
            $x += $get_divider == 10 ? 1 : 2;
            if ($amount)
            {
                $add_plural = (($counter = count($string)) && $amount > 9) ? 's' : null;
                $amt_hundred = ($counter == 1 && $string[0]) ? ' and ' : null;
                
                $string[] = ($amount < 21) ? $change_words[$amount] . ' ' . $here_digits[$counter] . $add_plural . ' ' . $amt_hundred : $change_words[floor($amount / 10) * 10] . ' ' . $change_words[$amount % 10] . ' ' . $here_digits[$counter] . $add_plural . ' ' . $amt_hundred;
            }
            else $string[] = null;
        }
        $implode_to_Rupees = implode('', array_reverse($string));
        $get_paise = ($amount_after_decimal > 0) ? "And " . ($change_words[$amount_after_decimal / 10] . " " . $change_words[$amount_after_decimal % 10]) . ' Paise' : '';
        return ($implode_to_Rupees ? $implode_to_Rupees . 'Rupees ' : '') . $get_paise;
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
            $message->cc(['karthikdavid@hemas.in','rfh@hemas.in']);
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

    public function get_cor_oat_ao(Request $request){

        if ($request->ajax()) {
           
            // get all data
            $session_user_details = auth()->user();
            $created_by = $session_user_details->empID;
                
                $input_details = array(
                    'payroll_status'=>"3",
                    // 'po_finance_status'=>"2",
                    // 'leader_status'=>"2",
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
                    
                        $btn = '<a href="../'.$row->offer_letter_filename.'" target="_blank"><span class="badge bg-primary" id="" title="Preview Offer Letter"><i class="bi bi-eye"></i></span></a>';
                  
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

    public function send_po_finance(Request $req){
        
        $get_assigned_to = auth()->user();
       
        // update candidate detail tbl
        $input_details_cd = array(
            'cdID' => $req->input('cdID'),
            'po_finance_status' => 1,
            'leader_status' => 2,
        );

        $update_cd_result = $this->payrepo->update_po_finance_status($input_details_cd);

        // send maill to finance code
        $input_details_ad = array(
            'empID' => 'FT001',
        );

        $get_user_result = $this->corepo->get_user_details($input_details_ad);
        
        $to_email=$get_user_result[0]->email;

        $input_details_c = array(
            'cdID' => $req->input('cdID'),
        );
        $get_candidate_details_result = $this->recrepo->get_candidate_details_ed( $input_details_c );
        $input_details_rr = array(
            'rfh_no' => $req->input('rfh_no'),
        );

        $get_tblrfh_result = $this->corepo->get_tblrfh_details($input_details_rr);
        
        
        $details = [
            'candidate_name' => $get_candidate_details_result[0]->candidate_name,
            'candidate_position' => $get_tblrfh_result[0]->position_title,
            'rfh_no' => $get_candidate_details_result[0]->hepl_recruitment_ref_number,
        ];

        // \Mail::send('emails.OfferRatifiedPoMail', array('details' => $details), function($message) use($to_email, $cc_emails, $bcc_emails){
            \Mail::send('emails.OfferRatifiedPoMail', array('details' => $details), function($message) use($to_email){
            $message->subject('CAREERS@HEPL: OFFER RATIFIED ');
            $message->to($to_email);
            $message->cc(['karthikdavid@hemas.in','rfh@hemas.in']);
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
    public function send_po_buisness_head(Request $req){
        
        $get_assigned_to = auth()->user();
       
        // update candidate detail tbl
        $input_details_cd = array(
            'cdID' => $req->input('cdID'),
            'leader_status' => 1,
        );

        $update_cd_result = $this->payrepo->update_po_finance_status($input_details_cd);

        // send maill to finance code
        $input_details_ad = array(
            'empID' => '1013712',
        );

        $get_user_result = $this->corepo->get_user_details($input_details_ad);
        
        $to_email=$get_user_result[0]->email;
 // $cc1="karthikdavid@hemas.in";
        // $cc2="rfh@hemas.in";
   
        $input_details_c = array(
            'cdID' => $req->input('cdID'),
        );
        $get_candidate_details_result = $this->recrepo->get_candidate_details_ed( $input_details_c );
        $input_details_rr = array(
            'rfh_no' => $req->input('rfh_no'),
        );

        $get_tblrfh_result = $this->corepo->get_tblrfh_details($input_details_rr);
        
        
        $details = [
            'candidate_name' => $get_candidate_details_result[0]->candidate_name,
            'candidate_position' => $get_tblrfh_result[0]->position_title,
            'rfh_no' => $get_candidate_details_result[0]->hepl_recruitment_ref_number,
        ];
        $cc1="durgadevi.r@hemas.in";
        $cc2="rfh@hemas.in";
        // \Mail::send('emails.OfferRatifiedPoMail', array('details' => $details), function($message) use($to_email, $cc_emails, $bcc_emails){
            \Mail::send('emails.OfferRatifiedPoMail', array('details' => $details), function($message) use($to_email){
            $message->subject('CAREERS@HEPL: OFFER RATIFIED');
            $message->to($to_email);
            $message->cc(['karthikdavid@hemas.in','rfh@hemas.in']);
           // $message->cc(['durgadevi.r@hemas.in','rfh@hemas.in']);
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
            'description' => "RFH is Ratified and submitted to Buisness Head for Verification",
            'created_by' => $created_by,
        );
        $put_or_followup_result = $this->recrepo->offer_release_followup_entry( $or_followup_input );


        $response = 'success';
        return response()->json( ['response' => $response] );
    }

    
    public function submit_po_process(Request $req){
        
        $get_assigned_to = auth()->user();
        $created_by = $get_assigned_to->empID;

        
        $po_details_val = $req->input( 'po_details' );
        $po_description_val = $req->input( 'po_description' );
        $po_amount_val = $req->input( 'po_amount' );

        $po_details_json = json_encode($po_details_val);
        $po_description_json = json_encode($po_description_val);
        $po_amount_json = json_encode($po_amount_val);

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
            $update_po_process_result = $this->payrepo->update_po_process( $data );

        }

        $pdf_data =array();
        $sno = 1;
        for ($i=0; $i < count($po_details_val); $i++) { 

            $pdf_data[$i]['sno'] = $sno;
            $pdf_data[$i]['po_details'] = $po_details_val[$i];
            $pdf_data[$i]['po_description'] = $po_description_val[$i];

            if($po_amount_val[$i] !='no_val'){
                $pdf_data[$i]['po_amount'] = $this->moneyFormatIndia($po_amount_val[$i]);
            }
            else{
                $pdf_data[$i]['po_amount'] = $po_amount_val[$i];
                
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
// mail to finance

        return response()->json( [
            'response' => 'success',
            'redirect_url' => 'CK_recruitment_management_new/public/po_letter/'.$cdID.'/'.$fileName
            ] );

        // return 'po_letter/'.$cdID.'/'.$fileName;

        // $response = 'success';
        // return response()->json( ['response' => $response] );
    }
   
    public function get_po_components(Request $req){
        
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

    public function upload_clientpo(Request $req){

        if($req->input('leader_status') ==2 && $req->input('leader_status') == 2){
            $payroll_status = 3;
        }else{
            $payroll_status = 2;

        }
        $client_po_no = $req->input('client_po_no');
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
               
                
            );
            $update_cd_result = $this->payrepo->update_po_finance_status($input_details_cd);


            $response = 'success';
            return response()->json( ['response' => $response] );
        }
        else if($client_po_no !== ""){
            $input_details_cd = array(
                'cdID' => $req->input('cpo_cdID'),
                'po_file_status' => 1,
                'client_po_update_date' => $client_po_update_date,
                'payroll_status' => $payroll_status,
                'client_po_number' => $client_po_no,
               
                
            );
            $update_cd_result = $this->payrepo->update_po_finance_status($input_details_cd);


            $response = 'success';
            return response()->json( ['response' => $response] ); 
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
}
