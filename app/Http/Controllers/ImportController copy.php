<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\UsersExport;
use App\Exports\ScoreExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Repositories\ICoordinatorRepository;
use App\Repositories\IRecruiterRepository;
use PDF;
use File;

class ImportController extends Controller
{

    /**
     * @return \Illuminate\Support\Collection
     */

    public function __construct(ICoordinatorRepository $corepo,IRecruiterRepository $recrepo)
    {
        $this->corepo = $corepo;
        $this->recrepo = $recrepo;

        $this->middleware('backend_coordinator');
    }

    public function export($rfh_no)
    {

        $input_details = array(
            'rfh_no' => $rfh_no,
        );

        $get_tblrfh_result = $this
            ->corepo
            ->get_tblrfh_details($input_details);

        if (count($get_tblrfh_result) != 0)
        {
            $export_array = [
            // [
            //     'label' => "RFH No",
            //     'rolls_option' => $get_tblrfh_result[0]->res_id,
            // ],
            // [
            //     'label' => "Request for Hire",
            //     'rolls_option' => $get_tblrfh_result[0]->rolls_option,
            // ],
            // [
            //     'label' => "Requested By",
            //     'name' => $get_tblrfh_result[0]->name,
            // ],
            // [
            //     'label' => "Mobile number",
            //     'mobile' => $get_tblrfh_result[0]->mobile,
            // ],
            // [
            //     'label' => "Email",
            //     'email' => $get_tblrfh_result[0]->email,
            // ],
            // [
            //     'label' => "Approved by",
            //     'approved_by' => $get_tblrfh_result[0]->approved_by,
            // ],
            // [
            //     'label' => "Ticket Number",
            //     'ticket_number' => $get_tblrfh_result[0]->ticket_number,
            // ],
            ['label' => "Please select your option for hiring the roles", 'rolls_option1' => "1.Activity Outsourcing to HEPL\n2.Manpower Outsourcing to HEPL\n3. On Rolls of your company", 'rolls_option' => $get_tblrfh_result[0]->rolls_option, ], ['label' => "Requested By:", 'name' => "Name:" . $get_tblrfh_result[0]->name, 'mobile' => "Ph:" . $get_tblrfh_result[0]->mobile, ], ['label' => "Position Title:" . $get_tblrfh_result[0]->position_title, 'location' => "Location: (WFH or On Site):" . $get_tblrfh_result[0]->location, 'function' => "Function:" . $get_tblrfh_result[0]->function, ], ['label' => "Business:" . $get_tblrfh_result[0]->business, 'division' => "Division:" . $get_tblrfh_result[0]->division, 'approved_by' => "Approved by:" . $get_tblrfh_result[0]->approved_by, ], ['label' => "Position reports to:" . $get_tblrfh_result[0]->position_reports, 'no_of_positions' => "No. of Positions:" . $get_tblrfh_result[0]->no_of_positions, 'band' => "Band:" . $get_tblrfh_result[0]->band, ], ['label' => "JD / Roles & Responsibilities", 'jd_roles' => $get_tblrfh_result[0]->jd_roles, ], ['label' => "Qualification", 'qualification' => $get_tblrfh_result[0]->qualification, ], ['label' => "Essential Skill sets", 'essential_skill' => $get_tblrfh_result[0]->essential_skill, ], ['label' => "Good to have Skill sets (if any):", 'good_skill' => $get_tblrfh_result[0]->good_skill, ], ['label' => "Experience (in yrs)", 'experience' => $get_tblrfh_result[0]->experience, ], ['label' => "Maximum CTC(Per Month)", 'salary_range' => $get_tblrfh_result[0]->salary_range, ], ['label' => "Any other specific consideration", 'any_specific' => $get_tblrfh_result[0]->any_specific, ],
            // [
            //     'label' => "Location preferred",
            //     'location_preferred' => $get_tblrfh_result[0]->location_preferred,
            // ],
            ];

        }
        $file_name = "RFH_" . $rfh_no . ".xlsx";
        return Excel::download(new UsersExport($export_array) , $file_name);
    }

    public function process_offer_letter_release(Request $req)
    {

        $input_details_rr = array(
            'rfh_no' => $req->input('rfh_no') ,
        );

        $get_tblrfh_result = $this->corepo->get_tblrfh_details($input_details_rr);

        $input_details_cd = array(
            'cdID' => $req->input('cdID'),
        );

        $get_candidate_details_result = $this->corepo->get_candidate_details_ed($input_details_cd);


        $get_cs_pa = $req->input('or_closed_salary');

        if($req->input('get_emp_mode') =='HEPL' && $req->input('esi_type') =='WITHOUT ESI'){

            $sc_basic_pa = round($get_cs_pa * 0.4);  // basic per annum

            if($sc_basic_pa < 121728) {
                $sc_basic_pa =  121728;
            }

            $sc_basic_pm = round($sc_basic_pa / 12); // basic per month



           // $sc_hra_pa = round($sc_basic_pa / 2); //old cal
            $sc_hra_pa = round($sc_basic_pa * 0.5); // house rent per annum
            $sc_hra_pm = round($sc_hra_pa / 12); //house rent per month

            // $sc_medical_allowance_pa =  15000;
            // $sc_medical_allowance_pm = round($sc_medical_allowance_pa / 12);

            $sc_medical_allowance_pm =  1250;// medical allowance per month
             $sc_medical_allowance_pa = round($sc_medical_allowance_pm * 12); // medical allowance per annum



            // $sc_conveyance_expence_pa = 19200;
            // $sc_conveyance_expence_pm = round($sc_conveyance_expence_pa / 12);

            $sc_conveyance_expence_pm = 1600; // Conveyance allowance per month
             $sc_conveyance_expence_pa = round($sc_conveyance_expence_pm * 12); // Conveyance allowance per annum



            //  $sc_special_allowance_pa = 0;
            // $sc_special_allowance_pm = 3729;

            //Special allowance


                $sc_special_allowance_pa = 67325;
                 $sc_special_allowance_pm = round($sc_special_allowance_pa / 12);

//MONTHY COMPONENTS[A]
            $sc_monthly_gross_pm_a = round($sc_basic_pm + $sc_hra_pm + $sc_conveyance_expence_pm + $sc_special_allowance_pm + $sc_medical_allowance_pm);
            $sc_monthly_gross_pa_a = round($sc_basic_pa + $sc_hra_pa + $sc_conveyance_expence_pa + $sc_special_allowance_pa + $sc_medical_allowance_pa);

            $get_pf_eligible_amt = $sc_basic_pm + $sc_conveyance_expence_pm + $sc_medical_allowance_pm + $sc_special_allowance_pm;

            if ($get_pf_eligible_amt <= 15000)
            {
                $emp_pf_cont_pa = round($get_pf_eligible_amt *12 * 0.12);
                $emp_pf_cont_pm = round($emp_pf_cont_pa / 12);

            }
            else
            {
                $emp_pf_cont_pa = round(15000 * 12 * 0.12);
                $emp_pf_cont_pm = round($emp_pf_cont_pa / 12);

            }

            // if ($sc_monthly_gross_pm_a <= 21000)
            // {
            //     $get_emp_esi_cont_pm = round($sc_monthly_gross_pm_a * 0.0325);
            //     $emp_esi_cont_pm = round($sc_monthly_gross_pm_a * 0.0325);
            //     $emp_esi_cont_pa = round($get_emp_esi_cont_pm * 12);

            //     $sub_total_b_pm = $emp_pf_cont_pm + $emp_esi_cont_pm;
            //     $sub_total_b_pa = $emp_pf_cont_pa + $emp_esi_cont_pa;
            //     $get_sub_total_b_pa = $emp_pf_cont_pa + $emp_esi_cont_pa;

            // }
            // else
            // {
                $get_emp_esi_cont_pm = 0;

                $emp_esi_cont_pm = 0;
                $emp_esi_cont_pa = 0;

                $sub_total_b_pm = $emp_pf_cont_pm;
                $sub_total_b_pa = $emp_pf_cont_pa;

          //  }

            $gratity_pa = round((15 / 26) * $sc_basic_pm);
            $gratity_pm = round($gratity_pa / 12);

            // $bonus_pa = "9820";10144
            // $bonus_pm = round($bonus_pa / 12);

             $bonus_pa = "10144";
             $bonus_pm = round($bonus_pa / 12);

            $sub_total_c_pm = round($gratity_pm + $bonus_pm);
            $sub_total_c_pa = round($gratity_pa + $bonus_pa);

            $abc_ctc_pm = round($sc_monthly_gross_pm_a + $sub_total_b_pm + $sub_total_c_pm);
            $abc_ctc_pa = round($sc_monthly_gross_pa_a + $sub_total_b_pa + $sub_total_c_pa);


            if ($abc_ctc_pa != $get_cs_pa)
            {
                $get_sa_dif = $get_cs_pa - $abc_ctc_pa;
              //  $sc_special_allowance_pa = $get_sa_dif;
              $sc_special_allowance_modify_pa = $sc_special_allowance_pa + ($get_sa_dif);
                $sc_monthly_gross_pa_a = round($sc_basic_pa + $sc_hra_pa + $sc_conveyance_expence_pa + $sc_special_allowance_modify_pa + $sc_medical_allowance_pa);

                $abc_ctc_pa = round($sc_monthly_gross_pa_a + $sub_total_b_pa + $sub_total_c_pa);
               // $sc_special_allowance_pm = round($sc_special_allowance_pa / 12);
                $sc_special_allowance_modify_pm = round($sc_special_allowance_modify_pa / 12);

                $get_pf_eligible_amt = $sc_basic_pm + $sc_conveyance_expence_pm + $sc_medical_allowance_pm + $sc_special_allowance_modify_pm;

                $sc_special_allowance_pa =  $sc_special_allowance_modify_pa;
                $sc_special_allowance_pm = $sc_special_allowance_modify_pm;

                if ($get_pf_eligible_amt <= 15000)
                {
                    $emp_pf_cont_pa = round($get_pf_eligible_amt *12* 0.12);
                    $emp_pf_cont_pm = round($emp_pf_cont_pa / 12);

                }
                else
                {

                    $emp_pf_cont_pa = round(15000 *12 * 0.12);
                    $emp_pf_cont_pm = round($emp_pf_cont_pa / 12);

                }

                $sc_monthly_gross_pm_a = round($sc_basic_pm + $sc_hra_pm + $sc_conveyance_expence_pm + $sc_special_allowance_pm + $sc_medical_allowance_pm);

                // repeat the process
                // if ($sc_monthly_gross_pm_a <= 21000)
                // {
                //     $emp_esi_cont_pm = round($sc_monthly_gross_pm_a * 0.0325);
                //     $emp_esi_cont_pa = round($get_emp_esi_cont_pm * 12);

                //     $sub_total_b_pm = $emp_pf_cont_pm + $emp_esi_cont_pm;
                //     $sub_total_b_pa = $get_sub_total_b_pa;

                // }
                // else
                // {
                    $emp_esi_cont_pm = 0;
                    $emp_esi_cont_pa = 0;

                    $sub_total_b_pm = $emp_pf_cont_pm;
                    $sub_total_b_pa = $emp_pf_cont_pa;

               // }

                $abc_ctc_pm = round($sc_monthly_gross_pm_a + $sub_total_b_pm + $sub_total_c_pm);
                $abc_ctc_pa = round($sc_monthly_gross_pa_a + $sub_total_b_pa + $sub_total_c_pa);

            }

            // if ($emp_esi_cont_pm >= 1)
            // {
            //     $n23 = ($sc_monthly_gross_pm_a * 0.0075);
            // }
            // else
           // {
                $n23 = 0;
          //  }

            $n25 = (($sc_monthly_gross_pm_a - $emp_pf_cont_pm) - $n23);

            $n26 = ($n25 - 208);

            $netpay = round($n26);

            $amount_in_words = $this->convert_number($req->input('or_closed_salary'));

            $cur_date = date('d-m-Y');
            $accept_end_date = date('F d, Y', strtotime($cur_date) + (24 * 3600 * 3));

            $session_user_details = auth()->user();
            $or_recruiter_name = $session_user_details->name;
            $or_recruiter_email = $session_user_details->email;
            $or_recruiter_mobile_no = $session_user_details->mobile_no;

            // get buddy info
            $input_details_bi = array(
                'buddy_id' => $req->input('welcome_buddy_id') ,
            );

            $get_buddy_result = $this
                ->corepo
                ->get_buddy_details($input_details_bi);

            $or_buddy_name = $get_buddy_result[0]->name;
            $or_buddy_email = $get_buddy_result[0]->email;
            $or_buddy_mobile_no = $get_buddy_result[0]->mobile_no;

            $closed_salary_tb = $this->moneyFormatIndia($req->input('or_closed_salary'));

            $logo_path = public_path('assets/images/logo/logo_bk.jpg');

            $user_details = auth()->user();
            $created_by = $user_details->empID;

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
                'created_by' => $created_by,
                'modified_by' => $created_by,

            ];

            // check record already exits

            $path = public_path().'/offer_letter/'.$req->input('cdID');
            File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);

            $check_ctc_calc_result = $this->recrepo->check_ctc_calc( $input_details_cd );

            if($check_ctc_calc_result ==0){

                $insert_ctc_calc_result = $this->recrepo->insert_ctc_calc( $ctc_calculation_data );

            }else{
                if (\File::exists($path)) \File::deleteDirectory($path);

                $update_ctc_calc_result = $this->recrepo->update_ctc_calc( $ctc_calculation_data );

            }

            $data = [
                'date' => date('d-m-Y') ,
                'logo_path' => $logo_path,
                'amount_in_words' => $amount_in_words,
                'position_title' => $get_tblrfh_result[0]->position_title,
                'join_date' => date("d-m-Y", strtotime($req->input('or_doj'))) ,
                'rfh_no' => $get_candidate_details_result[0]->hepl_recruitment_ref_number,
                'closed_salary' => $closed_salary_tb,
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
                'or_recruiter_name' => $or_recruiter_name,
                'or_recruiter_email' => $or_recruiter_email,
                'or_recruiter_mobile_no' => $or_recruiter_mobile_no,
                'or_buddy_name' => $or_buddy_name,
                'or_buddy_email' => $or_buddy_email,
                'or_buddy_mobile_no' => $or_buddy_mobile_no,
                'accept_end_date' => $accept_end_date,
                'department' => $req->input('or_department'),

            ];

            $pdf = PDF::loadView('offer_letter_pdf', $data);
            // $path = public_path('offer_letter/');

            $path = public_path().'/offer_letter/'.$req->input('cdID');
            File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);


            $fileName = time() . '.' . 'pdf';
            $pdf->save($path . '/' . $fileName);



        //    return '../offer_letter/'.$req->input('cdID').'/'.$fileName;
        return response()->json( [
            'path' => '../offer_letter/'.$req->input('cdID').'/'.$fileName,
            'filename' => $fileName
            ]
        );
        }

        else{
            $closed_salary_tb = $this->moneyFormatIndia($req->input('or_closed_salary'));

            $logo_path = public_path('assets/images/logo/logo_bk.jpg');
            $amount_in_words = $this->convert_number($req->input('or_closed_salary'));

            $data = [
                'date' => date('d-m-Y') ,
                'logo_path' => $logo_path,
                'amount_in_words' => $amount_in_words,
                'position_title' => $get_tblrfh_result[0]->position_title,
                'join_date' => date("d-m-Y", strtotime($req->input('or_doj'))) ,
                'rfh_no' => $get_candidate_details_result[0]->hepl_recruitment_ref_number,
                'closed_salary' => $closed_salary_tb,
                'candidate_name' => $get_candidate_details_result[0]->candidate_name,
                'department' => $req->input('or_department'),
            ];

            $pdf = PDF::loadView('offer_letter_naps_pdf', $data);
            // $path = public_path('offer_letter/');

            $path = public_path().'/offer_letter/'.$req->input('cdID');
            File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);


            $fileName = time() . '.' . 'pdf';
            $pdf->save($path . '/' . $fileName);

            return '../offer_letter/'.$req->input('cdID').'/'.$fileName;
        }

    }
    public function submit_esi_form(Request $req){

        $input_details_cd = array(
            'cdID' => $req->input('cdID'),
        );
        $user_details = auth()->user();
        $created_by = $user_details->empID;
        $ctc_calculation_data =[
            'cdID' => $req->input('cdID'),
            'basic_pm' =>$req->input('basic_pm'),
            'basic_pa' => $req->input('basic_pa'),
            'hra_pm' => $req->input('hra_pm'),
            'hra_pa' => $req->input('hra_pa'),
            'medi_al_pm' => $req->input('medi_al_pm'),
            'medi_al_pa' => $req->input('medi_al_pa'),
            'conv_pm' => $req->input('conv_pm'),
            'conv_pa' => $req->input('conv_pa'),
            'spl_al_pm' => $req->input('spl_al_pm'),
            'spl_al_pa' => $req->input('spl_al_pa'),
            'comp_a_pm' => $req->input('comp_a_pm'),
            'comp_a_pa' =>$req->input('comp_a_pa'),
            'ec_pf_pm' => $req->input('ec_pf_pm'),
            'ec_pf_pa' => $req->input('ec_pf_pa'),
            'ec_esi_pm' => $req->input('ec_esi_pm'),
            'ec_esi_pa' => $req->input('ec_esi_pa'),
            'sub_totalb_pm' =>$req->input('sub_totalb_pm'),
            'sub_totalb_pa' => $req->input('sub_totalb_pa'),
            'gratuity_pm' => $req->input('gratuity_pm'),
            'gratuity_pa' => $req->input('gratuity_pa'),
            'st_bonus_pm' => $req->input('st_bonus_pm'),
            'st_bonus_pa' =>$req->input('st_bonus_pa'),
            'sub_totalc_pm' => $req->input('sub_totalc_pm'),
            'sub_totalc_pa' =>$req->input('sub_totalc_pa'),
            'abc_pm' => $req->input('abc_pm'),
            'abc_pa' => $req->input('abc_pa'),
            'net_pay' =>  $req->input('net_pay'),
            'created_by' => $created_by,
            'modified_by' => $created_by,

        ];

        // check record already exits

        $path = public_path().'/offer_letter/'.$req->input('cdID');
        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);

        $check_ctc_calc_result = $this->recrepo->check_ctc_calc( $input_details_cd );

        if($check_ctc_calc_result ==0){

            $insert_ctc_calc_result = $this->recrepo->insert_ctc_calc( $ctc_calculation_data );

        }else{
            if (\File::exists($path)) \File::deleteDirectory($path);

            $update_ctc_calc_result = $this->recrepo->update_ctc_calc( $ctc_calculation_data );

        }
        // with esi calculation

        $input_details_rr = array(
            'rfh_no' => $req->input('rfh_no') ,
        );

        $get_tblrfh_result = $this->corepo->get_tblrfh_details($input_details_rr);

        $input_details_cd = array(
            'cdID' => $req->input('cdID'),
        );

        $get_candidate_details_result = $this->corepo->get_candidate_details_ed($input_details_cd);
        $amount_in_words = $this->convert_number($req->input('or_closed_salary'));
        $logo_path = public_path('assets/images/logo/logo_bk.jpg');
        $closed_salary_tb = $this->moneyFormatIndia($req->input('or_closed_salary'));
        $session_user_details = auth()->user();
        $or_recruiter_name = $session_user_details->name;
        $or_recruiter_email = $session_user_details->email;
        $or_recruiter_mobile_no = $session_user_details->mobile_no;

        $input_details_bi = array(
            'buddy_id' => $req->input('welcome_buddy_id') ,
        );
        $get_buddy_result = $this
                ->corepo
                ->get_buddy_details($input_details_bi);

            $or_buddy_name = $get_buddy_result[0]->name;
            $or_buddy_email = $get_buddy_result[0]->email;
            $or_buddy_mobile_no = $get_buddy_result[0]->mobile_no;
            $cur_date = date('d-m-Y');
            $accept_end_date = date('F d, Y', strtotime($cur_date) + (24 * 3600 * 3));
            $sc_basic_pm = $req->input('basic_pm');
           // $sc_basic_pa = $sc_basic_pm * 12;
        $data = [
            'date' => date('d-m-Y') ,
            'logo_path' => $logo_path,
            'amount_in_words' => $amount_in_words,
            'position_title' => $get_tblrfh_result[0]->position_title,
            'join_date' => date("d-m-Y", strtotime($req->input('or_doj'))) ,
            'rfh_no' => $get_candidate_details_result[0]->hepl_recruitment_ref_number,
            'closed_salary' => $closed_salary_tb,
            'candidate_name' => $get_candidate_details_result[0]->candidate_name,
            'location' => $get_tblrfh_result[0]->location,
            'business' => $get_tblrfh_result[0]->business,
            'function' => $get_tblrfh_result[0]->function,
            'band_title' => $get_tblrfh_result[0]->band_title,
            'sc_basic_pm' => $this->moneyFormatIndia($req->input('basic_pm')) ,
            'sc_basic_pa' => $this->moneyFormatIndia($req->input('basic_pa')) ,
            'sc_hra_pm' => $this->moneyFormatIndia( $req->input('hra_pm')) ,
            'sc_hra_pa' => $this->moneyFormatIndia( $req->input('hra_pa')) ,
            'sc_conveyance_expence_pm' => $this->moneyFormatIndia($req->input('conv_pm')) ,
            'sc_conveyance_expence_pa' => $this->moneyFormatIndia($req->input('conv_pa')) ,
            'sc_medical_allowance_pm' => $this->moneyFormatIndia($req->input('medi_al_pm')) ,
            'sc_medical_allowance_pa' => $this->moneyFormatIndia($req->input('medi_al_pa')) ,
            'sc_special_allowance_pm' => $this->moneyFormatIndia($req->input('spl_al_pm')) ,
            'sc_special_allowance_pa' => $this->moneyFormatIndia($req->input('spl_al_pa')) ,
            'sc_monthly_gross_pm' => $this->moneyFormatIndia($req->input('comp_a_pm')) ,
            'sc_monthly_gross_pa' => $this->moneyFormatIndia($req->input('comp_a_pa')) ,
            'emp_pf_cont_pm' => $this->moneyFormatIndia($req->input('ec_pf_pm')) ,
            'emp_pf_cont_pa' => $this->moneyFormatIndia($req->input('ec_pf_pa')) ,
            'emp_esi_cont_pm' => $this->moneyFormatIndia($req->input('ec_esi_pm')),
            'emp_esi_cont_pa' => $this->moneyFormatIndia($req->input('ec_esi_pa')),
            'sub_total_b_pm' => $this->moneyFormatIndia($req->input('sub_totalb_pm')) ,
            'sub_total_b_pa' => $this->moneyFormatIndia($req->input('sub_totalb_pa')) ,
            'bonus_pm' => $this->moneyFormatIndia($req->input('st_bonus_pm')) ,
            'bonus_pa' => $this->moneyFormatIndia($req->input('st_bonus_pa')) ,
            'gratity_pm' => $this->moneyFormatIndia($req->input('gratuity_pm')) ,
            'gratity_pa' => $this->moneyFormatIndia($req->input('gratuity_pa')) ,
            'sub_total_c_pm' => $this->moneyFormatIndia($req->input('sub_totalc_pm')) ,
            'sub_total_c_pa' => $this->moneyFormatIndia($req->input('sub_totalc_pa')) ,
            'abc_ctc_pm' => $this->moneyFormatIndia($req->input('abc_pm')) ,
            'abc_ctc_pa' => $this->moneyFormatIndia($req->input('abc_pa')) ,
            'netpay' => $this->moneyFormatIndia($req->input('net_pay')) ,
            'or_recruiter_name' => $or_recruiter_name,
            'or_recruiter_email' => $or_recruiter_email,
            'or_recruiter_mobile_no' => $or_recruiter_mobile_no,
            'or_buddy_name' => $or_buddy_name,
            'or_buddy_email' => $or_buddy_email,
            'or_buddy_mobile_no' => $or_buddy_mobile_no,
            'accept_end_date' => $accept_end_date,
            'department' => $req->input('or_department'),

        ];
        $pdf = PDF::loadView('offer_letter_pdf', $data);
        // $path = public_path('offer_letter/');

        $path = public_path().'/offer_letter/'.$req->input('cdID');
        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);


        $fileName = time() . '.' . 'pdf';
        $pdf->save($path . '/' . $fileName);



        return '../offer_letter/'.$req->input('cdID').'/'.$fileName;
       $response  ="success";
       //return $response;

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
                $add_plural = (($counter = count($string)) && $amount > 1) ? 's' : null;
                $amt_hundred = ($counter == 1 && $string[0]) ? ' and ' : null;

                $string[] = ($amount < 21) ? $change_words[$amount] . ' ' . $here_digits[$counter] . $add_plural . ' ' . $amt_hundred : $change_words[floor($amount / 10) * 10] . ' ' . $change_words[$amount % 10] . ' ' . $here_digits[$counter] . $add_plural . ' ' . $amt_hundred;
            }
            else $string[] = null;
        }
        $implode_to_Rupees = implode('', array_reverse($string));
        $get_paise = ($amount_after_decimal > 0) ? "And " . ($change_words[$amount_after_decimal / 10] . " " . $change_words[$amount_after_decimal % 10]) . ' Paise' : '';
        return ($implode_to_Rupees ? $implode_to_Rupees . 'Indian Rupees ' : '') . $get_paise;
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
    public function scorecard_export()
    {
        $get_recruiter_details_id = $this->corepo->get_user_recruiter();
        $export_array = [];
       foreach($get_recruiter_details_id as $val){
            $data = [

            'S.No.' =>'1',
            'Recruiter'=>$val->name,
            'Position'=>'1',
            'Interviews'=>'1',
            'Offers'=>'1',
            '10am'=>'1',
            '11am'=>'1',
            '12pm'=>'1',
            '1pm'=>'1',
            '3pm'=>'1',
            '4pm'=>'1',
            '5pm'=>'1',
            '6pm'=>'1',
            'Total Cvs'=>'1',
                ];


       }
       $export_array = [$data];
       // $file_name = "RFH_" . $rfh_no . ".xlsx";
       $file_name = "Score Card .xlsx";
        return Excel::download(new ScoreExport($export_array) , $file_name);
    }
}

