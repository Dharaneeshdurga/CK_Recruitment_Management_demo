<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ICoordinatorRepository;
use App\Repositories\ICandidateRepository;
use App\Repositories\IRecruiterRepository;
use File;
use DB;

use App\Models\Candidate_details;
use App\Models\Candidate_followup_details;
use Illuminate\Support\Facades\Http;
class DocumentCollection extends Controller
{
    public function __construct(ICoordinatorRepository $corepo,ICandidateRepository $canrepo,IRecruiterRepository $recrepo)
    {
        $this->corepo = $corepo;
        $this->canrepo = $canrepo;
        $this->recrepo = $recrepo;

    }

    public function candidate_dc()
    {


        // exit;
        return view('candidate_login/candidate_dc');
        // return view('candidate_login/candidate_dc',[
        //     'c_basic_details' => $c_basic_details,
        //     'c_edu_details' => $get_candidate_edu_details_result,
        // ]);
    }

    public function get_candidate_details_exist(Request $request){

        $cdID = base64_decode($request->cdID);

        $input_details = array(
            'cdID'=>$cdID,
        );

        $get_candidate_details_result = $this->corepo->get_candidate_details_ed( $input_details );

        $get_candidate_edu_details_result = $this->corepo->get_candidate_edu_details( $input_details );

        $get_candidate_exp_details_result = $this->corepo->get_candidate_exp_details( $input_details );

        $get_candidate_benefits_details_result = $this->corepo->get_candidate_benefits_details( $input_details );

        return response()->json( [
            'c_basic_details' => $get_candidate_details_result,
            'c_edu_details' => $get_candidate_edu_details_result,
            'c_exp_details' => $get_candidate_exp_details_result,
            'c_benefits_details' => $get_candidate_benefits_details_result,
        ] );


    }
    public function candidate_basic_doc_upload(Request $request){
        $candidate_id = $request->candidate_id;

        $path = public_path().'/candidate_doc/'.$candidate_id;
        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);

        $input_details_cd = array();
        $input_details_cd['cdID'] = $candidate_id;
        $input_details_cd['candidate_mobile'] = $request->post('candidate_contactno');
        $input_details_cd['gender'] = $request->post('candidate_gender');
        $input_details_cd['middle_name'] = $request->post('middle_name');
        $input_details_cd['last_name'] = $request->post('last_name');
        $input_details_cd['dob'] = $request->post('dob');
        $input_details_cd['age'] = $request->post('age');
        $input_details_cd['marital_status'] = $request->post('marital_status');
        $input_details_cd['blood_group'] = $request->post('blood_gr');
        // upload proof of identity
        $proof_of_identity = $request->proof_of_identity;
        $input_details_cd['proof_of_identity'] = $proof_of_identity;

        if($files = $request->file('poi_file')){

            $poi_filename = "poi_file".time().'.'.$files->getClientOriginalExtension();
            $files->move($path, $poi_filename);
            $input_details_cd['poi_filename'] = $poi_filename;

        }else{
            $poi_filename='';
            // echo "no proof of poi_file";
        }

        // upload proof of address
        $proof_of_address = $request->proof_of_address;
        $input_details_cd['proof_of_address'] = $proof_of_address;

        if($files = $request->file('poa_file')){
            $poa_filename = "poa_file".time().'.'.$files->getClientOriginalExtension();
            $files->move($path, $poa_filename);
            $input_details_cd['poa_filename'] = $poa_filename;

        }else{
            $poa_filename='';
            // echo "no proof of poa_file";
        }

        $put_candidate_details_result = $this->canrepo->update_candidate_details( $input_details_cd );

        // $response = Http::post('http://jsonplaceholder.typicode.com/posts', [
        //     'title' => 'This is test from ItSolutionStuff.com',
        //     'body' => 'This is test from ItSolutionStuff.com as body',
        // ]);





        // dd($response->successful());



        return "success";

    }

    public function candidate_edu_document(Request $request){

        $candidate_id = $request->candidate_id;
        $rfh_no = $request->rfh_no;
        $hepl_recruitment_ref_number = $request->hepl_recruitment_ref_number;

        $path = public_path().'/candidate_doc/'.$candidate_id;
        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);

        // upload education details

            foreach ($request->post('degree') as $key => $files) {

                $get_edu_start_details = explode("-",$request->post('edu_start_month')[$key]);

                $get_edu_end_details = explode("-",$request->post('edu_end_month')[$key]);

                $c_edu_row_id =  $request->post('c_edu_row_id')[$key];
                $degree =  $request->post('degree')[$key];
                $university =  $request->post('university')[$key];

                $edu_start_month = $get_edu_start_details[0];
                $edu_start_year = $get_edu_start_details[1];
                $edu_end_month = $get_edu_end_details[0];
                $edu_end_year = $get_edu_end_details[1];

                if(!empty($request->file('edu_certificate')[$key])){

                    $files = $request->file('edu_certificate')[$key];
                    $edu_certificate = "edu_certificate".uniqid().'.'.$files->getClientOriginalExtension();
                    $files->move($path, $edu_certificate);

                    $input_details_edu = array(
                        'id'=>$c_edu_row_id,
                        'cdID'=>$candidate_id,
                        'rfh_no'=>$rfh_no,
                        'hepl_recruitment_ref_number'=>$hepl_recruitment_ref_number,
                        'degree'=>$degree,
                        'university'=>$university,
                        'edu_start_month'=>$edu_start_month,
                        'edu_start_year'=>$edu_start_year,
                        'edu_end_month'=>$edu_end_month,
                        'edu_end_year'=>$edu_end_year,
                        'edu_certificate'=>$edu_certificate,
                        'created_on'=>date('Y-m-d'),
                    );
                    if($c_edu_row_id ==''){

                        $put_candidate_edu_details_result = $this->canrepo->put_candidate_education( $input_details_edu );

                    }else{

                        $input_details_edu = array(
                            'id'=>$c_edu_row_id,
                            'rfh_no'=>$rfh_no,
                            'hepl_recruitment_ref_number'=>$hepl_recruitment_ref_number,
                            'degree'=>$degree,
                            'university'=>$university,
                            'edu_start_month'=>$edu_start_month,
                            'edu_start_year'=>$edu_start_year,
                            'edu_end_month'=>$edu_end_month,
                            'edu_end_year'=>$edu_end_year,
                            'edu_certificate'=>$edu_certificate,
                        );
                        $update_candidate_edu_details_result = $this->canrepo->update_candidate_education( $input_details_edu );

                    }
                }else{

                    $input_details_edu = array(
                        'id'=>$c_edu_row_id,
                        'rfh_no'=>$rfh_no,
                        'hepl_recruitment_ref_number'=>$hepl_recruitment_ref_number,
                        'degree'=>$degree,
                        'university'=>$university,
                        'edu_start_month'=>$edu_start_month,
                        'edu_start_year'=>$edu_start_year,
                        'edu_end_month'=>$edu_end_month,
                        'edu_end_year'=>$edu_end_year,
                    );
                    $update_candidate_edu_details_result = $this->canrepo->update_candidate_education( $input_details_edu );

                }

            }

            return "success";

    }
    public function remove_education_fields_exist(Request $request){
        $input_details_edu = array(
            'id'=>$request->post('id'),
        );
        $remove_education_fields_exist_res = $this->canrepo->remove_education_fields_exist( $input_details_edu );
        return "success";

    }

    public function candidate_exp_document(Request $request){
        $candidate_id = $request->candidate_id;
        $rfh_no = $request->rfh_no;
        $hepl_recruitment_ref_number = $request->hepl_recruitment_ref_number;

        $path = public_path().'/candidate_doc/'.$candidate_id;
        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);

        // upload experience details

            foreach ($request->post('job_title') as $key => $files) {

                $get_exp_start_details = explode("-",$request->post('exp_start_month')[$key]);

                $get_exp_end_details = explode("-",$request->post('exp_end_month')[$key]);

                $c_exp_row_id =  $request->post('c_exp_row_id')[$key];
                $job_title =  $request->post('job_title')[$key];
                $company_name =  $request->post('company_name')[$key];

                $exp_start_month = $get_exp_start_details[0];
                $exp_start_year = $get_exp_start_details[1];
                $exp_end_month = $get_exp_end_details[0];
                $exp_end_year = $get_exp_end_details[1];

                if(!empty($request->file('exp_certificate')[$key])){

                    $files = $request->file('exp_certificate')[$key];
                    $exp_certificate = "exp_certificate".uniqid().'.'.$files->getClientOriginalExtension();
                    $files->move($path, $exp_certificate);

                    $input_details_exp = array(
                        'cdID'=>$candidate_id,
                        'rfh_no'=>$rfh_no,
                        'hepl_recruitment_ref_number'=>$hepl_recruitment_ref_number,
                        'job_title'=>$job_title,
                        'company_name'=>$company_name,
                        'exp_start_month'=>$exp_start_month,
                        'exp_start_year'=>$exp_start_year,
                        'exp_end_month'=>$exp_end_month,
                        'exp_end_year'=>$exp_end_year,
                        'certificate'=>$exp_certificate,
                    );
                    if($c_exp_row_id ==''){

                        $put_candidate_exp_details_result = $this->canrepo->put_candidate_experience( $input_details_exp );

                    }else{

                        $input_details_exp = array(
                            'id'=>$c_exp_row_id,
                            'rfh_no'=>$rfh_no,
                            'hepl_recruitment_ref_number'=>$hepl_recruitment_ref_number,
                            'job_title'=>$job_title,
                            'company_name'=>$company_name,
                            'exp_start_month'=>$exp_start_month,
                            'exp_start_year'=>$exp_start_year,
                            'exp_end_month'=>$exp_end_month,
                            'exp_end_year'=>$exp_end_year,
                            'certificate'=>$exp_certificate,
                        );
                        $update_candidate_exp_details_result = $this->canrepo->update_candidate_experience( $input_details_exp );

                    }
                }else{

                    $input_details_exp = array(
                        'id'=>$c_exp_row_id,
                        'rfh_no'=>$rfh_no,
                        'hepl_recruitment_ref_number'=>$hepl_recruitment_ref_number,
                        'job_title'=>$job_title,
                        'company_name'=>$company_name,
                        'exp_start_month'=>$exp_start_month,
                        'exp_start_year'=>$exp_start_year,
                        'exp_end_month'=>$exp_end_month,
                        'exp_end_year'=>$exp_end_year,
                    );
                    $update_candidate_exp_details_result = $this->canrepo->update_candidate_experience( $input_details_exp );

                }

            }

            return "success";

    }

    public function remove_experience_fields_exist(Request $request){
        $input_details_exp = array(
            'id'=>$request->post('id'),
        );
        $remove_experience_fields_exist_res = $this->canrepo->remove_experience_fields_exist( $input_details_exp );
        return "success";

    }

    public function candidate_benefit_document(Request $request){
        $candidate_id = $request->candidate_id;
        $rfh_no = $request->rfh_no;
        $hepl_recruitment_ref_number = $request->hepl_recruitment_ref_number;

        $path = public_path().'/candidate_doc/'.$candidate_id;
        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);

        // upload compensation & benefits received
            foreach ($request->post('doc_type') as $key => $files) {

                $doc_type =  $request->post('doc_type')[$key];
                $c_benefits_row_id =  $request->post('c_benefits_row_id')[$key];

                if(!empty($request->file('doc_type_file')[$key])){

                    $files = $request->file('doc_type_file')[$key];

                    $doc_filename = "doc_type_file".uniqid().'.'.$files->getClientOriginalExtension();
                    $files->move($path, $doc_filename);

                    if($c_benefits_row_id ==''){
                        $input_details_cbd = array(
                            'cdID'=>$candidate_id,
                            'rfh_no'=>$rfh_no,
                            'hepl_recruitment_ref_number'=>$hepl_recruitment_ref_number,
                            'doc_type'=>$doc_type,
                            'doc_filename'=>$doc_filename,
                            'created_on'=>date('Y-m-d'),

                        );
                        $put_candidate_benefits_result = $this->canrepo->put_candidate_benefits( $input_details_cbd );

                    }else{

                        $input_details_cbd = array(
                            'id'=>$c_benefits_row_id,
                            'rfh_no'=>$rfh_no,
                            'hepl_recruitment_ref_number'=>$hepl_recruitment_ref_number,
                            'doc_type'=>$doc_type,
                            'doc_filename'=>$doc_filename,
                        );
                        $update_candidate_benefits_result = $this->canrepo->update_candidate_benefits( $input_details_cbd );

                    }
                }else{

                    $input_details_cbd = array(
                        'id'=>$c_benefits_row_id,
                        'doc_type'=>$doc_type,
                        'rfh_no'=>$rfh_no,
                        'hepl_recruitment_ref_number'=>$hepl_recruitment_ref_number,
                    );
                    $update_candidate_benefits_result = $this->canrepo->update_candidate_benefits( $input_details_cbd );

                }

            }

        return "success";

    }

    public function remove_compensation_fields_exist(Request $request){
        $input_details_comp = array(
            'id'=>$request->post('id'),
        );
        $remove_compensation_fields_exist_res = $this->canrepo->remove_compensation_fields_exist( $input_details_comp );
        return "success";

    }

    public function candidate_proof_document(Request $request){
        $candidate_id = $request->candidate_id;
        $rfh_no = $request->rfh_no;
        $hepl_recruitment_ref_number = $request->hepl_recruitment_ref_number;

        $path = public_path().'/candidate_doc/'.$candidate_id;
        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);

        $input_details_cd = array();
        $input_details_cd['cdID'] = $candidate_id;
        // $input_details_cd['rfh_no'] = $rfh_no;
        // $input_details_cd['hepl_recruitment_ref_number'] = $hepl_recruitment_ref_number;

        // upload proof of bank account
        if(!empty($files = $request->file('proof_of_ba'))){

            $proof_of_ba = "proof_of_ba".time().'.'.$files->getClientOriginalExtension();
            $files->move($path, $proof_of_ba);
            $input_details_cd['proof_of_bankacc'] = $proof_of_ba;

        }
        // upload proof of vaccination
        if(!empty($files = $request->file('proof_of_vaccine'))){

            $proof_of_vaccine = "proof_of_vaccine".time().'.'.$files->getClientOriginalExtension();
            $files->move($path, $proof_of_vaccine);
            $input_details_cd['proof_of_vaccination'] = $proof_of_vaccine;

        }
        // upload proof of bg
        if(!empty($files = $request->file('proof_of_bg'))){

            $proof_of_bg = "proof_of_bg".time().'.'.$files->getClientOriginalExtension();
            $files->move($path, $proof_of_bg);
        $input_details_cd['proof_of_bg'] = $proof_of_bg;

        }
        // upload proof of relieving
        if(!empty($files = $request->file('proof_of_relieving'))){

            $proof_of_relieving = "proof_of_relieving".time().'.'.$files->getClientOriginalExtension();
            $files->move($path, $proof_of_relieving);
        $input_details_cd['proof_of_relieving'] = $proof_of_relieving;

        }
        // upload proof of dob
        if(!empty($files = $request->file('proof_of_dob'))){

            $proof_of_dob = "proof_of_dob".time().'.'.$files->getClientOriginalExtension();
            $files->move($path, $proof_of_dob);
            $input_details_cd['proof_of_dob'] = $proof_of_dob;

        }
        // upload proof of tax_proof
        if(!empty($files = $request->file('tax_proof'))){

            $tax_proof = "tax_proof".time().'.'.$files->getClientOriginalExtension();
            $files->move($path, $tax_proof);
            $input_details_cd['tax_entity_proof'] = $tax_proof;

        }

        $input_details_cd['c_doc_upload_status'] = 1;

        $put_candidate_details_result = $this->canrepo->update_candidate_details( $input_details_cd );

        // offer release followup entry
        $or_followup_input = array(
            'cdID' => $candidate_id,
            'rfh_no' => $rfh_no,
            'hepl_recruitment_ref_number' => $hepl_recruitment_ref_number,
            'description' => "Documents uploaded by candidate",
            'created_by' => $candidate_id,
        );
        $put_or_followup_result = $this->canrepo->offer_release_followup_entry( $or_followup_input );


        // send mail to recruiter
        $user_row = $this->recrepo->get_candidate_row( $candidate_id );

        $recruiter_id=$request->input('created_by');

        $input_details_ad = array(
            'empID' => $recruiter_id,
        );
        $get_user_result = $this->corepo->get_user_details($input_details_ad);
      //  print_r($input_details_ad);
        $get_position_result = $this->recrepo->get_recr_req($user_row[0]->hepl_recruitment_ref_number);
        $to_email=$get_user_result[0]->email;


        $details = [
            'candidate_name' => $user_row[0]->candidate_name,
            'candidate_position' => $get_position_result[0]->position_title,
            'candidate_rfh_no' => $user_row[0]->rfh_no,

        ];

		\Mail::to($to_email)
        ->cc(['karthik.d@hepl.com','rfh@hepl.com'])
        ->send(new \App\Mail\NotifyRecruiterDocUploadedMail($details));
        //$to_email=$get_candidate_details_result[0]->candidate_email;


        // $get_assigned_to = auth()->user();
        // $created_by = $get_assigned_to->empID;
        // $to_email_recruiter = $get_assigned_to->email;
        // $get_title = "CAREERS@HEPL:  CANDIDATE DOCUMENT UPLOAD";
        // $get_body1 = " Mr / Ms ".$get_candidate_details_result[0]->candidate_name;
        // $get_body2 = "SuccessFully Upload the Documents";
        // $details = [
        //             'title' => $get_title,
        //             'body1' => $get_body1,
        //             'body2' => $get_body2,

        // ];
        // \Mail::to($to_email_recruiter)
        // ->cc(['durgadevi.r@hemas.in','rfh@hemas.in'])
        // ->send(new \App\Mail\MyTestMail($details));

        return "success";

    }
    public function candidate_document_upload(Request $request){
        // print_r($request->input());
        // print_r($request->file());
        // exit();
        $candidate_id = $request->candidate_id;

        $path = public_path().'/candidate_doc/'.$candidate_id;
        File::isDirectory($path) or File::makeDirectory($path, 0777, true, true);

        // upload proof of bank account
        if($files = $request->file('proof_of_ba')){

            $proof_of_ba = "proof_of_ba".time().'.'.$files->getClientOriginalExtension();
            $files->move($path, $proof_of_ba);

        }else{
            $proof_of_ba='';
            // echo "no proof of bank account empty";
        }

        // upload proof of vaccination
        if($files = $request->file('proof_of_vaccine')){

            $proof_of_vaccine = "proof_of_vaccine".time().'.'.$files->getClientOriginalExtension();
            $files->move($path, $proof_of_vaccine);

        }else{
            $proof_of_vaccine='';
            // echo "no proof of vaccination";
        }

        // upload proof of bg
        if($files = $request->file('proof_of_bg')){

            $proof_of_bg = "proof_of_bg".time().'.'.$files->getClientOriginalExtension();
            $files->move($path, $proof_of_bg);

        }else{
            $proof_of_bg='';
            // echo "no proof of bg";
        }

        // upload proof of relieving
        if($files = $request->file('proof_of_relieving')){

            $proof_of_relieving = "proof_of_relieving".time().'.'.$files->getClientOriginalExtension();
            $files->move($path, $proof_of_relieving);

        }else{
            $proof_of_relieving='';
            // echo "no proof of relieving";
        }

        // upload proof of dob
        if($files = $request->file('proof_of_dob')){

            $proof_of_dob = "proof_of_dob".time().'.'.$files->getClientOriginalExtension();
            $files->move($path, $proof_of_dob);

        }else{
            $proof_of_dob='';
            // echo "no proof of dob";
        }

        // upload proof of tax_proof
        if($files = $request->file('tax_proof')){

            $tax_proof = "tax_proof".time().'.'.$files->getClientOriginalExtension();
            $files->move($path, $tax_proof);

        }else{
            $tax_proof='';
            // echo "no proof of tax_proof";
        }

        // upload proof of identity
        $proof_of_identity = $request->proof_of_identity;

        if($files = $request->file('poi_file')){

            $poi_filename = "poi_file".time().'.'.$files->getClientOriginalExtension();
            $files->move($path, $poi_filename);

        }else{
            $poi_filename='';
            // echo "no proof of poi_file";
        }

        // upload proof of address
        $proof_of_address = $request->proof_of_address;

        if($files = $request->file('poa_file')){
            $poa_filename = "poa_file".time().'.'.$files->getClientOriginalExtension();
            $files->move($path, $poa_filename);

        }else{
            $poa_filename='';
            // echo "no proof of poa_file";
        }

        $input_details_cd = array(
            'cdID'=>$candidate_id,
            'proof_of_identity'=>$proof_of_identity,
            'poi_filename'=>$poi_filename,
            'proof_of_address'=>$proof_of_address,
            'poa_filename'=>$poa_filename,
            'tax_entity_proof'=>$tax_proof,
            'proof_of_relieving'=>$proof_of_relieving,
            'proof_of_vaccination'=>$proof_of_vaccine,
            'proof_of_dob'=>$proof_of_dob,
            'proof_of_bg'=>$proof_of_bg,
            'proof_of_bankacc'=>$proof_of_ba,
            'candidate_mobile'=>$request->candidate_contactno,
        );
        // print_r($input_details_cd);
        $put_candidate_details_result = $this->canrepo->update_candidate_details( $input_details_cd );


        // upload compensation & benefits received
        if(!empty($request->file('doc_type_file'))){
            foreach ($request->file('doc_type_file') as $key => $files) {

                $doc_type =  $request->post('doc_type')[$key];

                $doc_filename = "doc_type_file".uniqid().'.'.$files->getClientOriginalExtension();
                $files->move($path, $doc_filename);

                $input_details_cbd = array(
                    'cdID'=>$candidate_id,
                    'doc_type'=>$doc_type,
                    'doc_filename'=>$doc_filename,
                    'created_on'=>date('Y-m-d'),

                );
                $put_candidate_benefits_result = $this->canrepo->put_candidate_benefits( $input_details_cbd );


            }

        }else{
            $doc_filename='';
            // echo "no proof of benefits";
        }


        // upload experience details
        if(!empty($request->file('exp_certificate'))){

            foreach ($request->file('exp_certificate') as $key => $files) {

                $job_title =  $request->post('job_title')[$key];
                $company_name =  $request->post('company_name')[$key];
                $exp_start_month =  $request->post('exp_start_month')[$key];
                $exp_start_year =  $request->post('exp_start_year')[$key];
                $exp_end_month =  $request->post('exp_end_month')[$key];
                $exp_end_year =  $request->post('exp_end_year')[$key];
                $exp_certificate = "exp_certificate".uniqid().'.'.$files->getClientOriginalExtension();
                $files->move($path, $exp_certificate);

                $input_details_exp = array(
                    'cdID'=>$candidate_id,
                    'job_title'=>$job_title,
                    'company_name'=>$company_name,
                    'exp_start_month'=>$exp_start_month,
                    'exp_start_year'=>$exp_start_year,
                    'exp_end_month'=>$exp_end_month,
                    'exp_end_year'=>$exp_end_year,
                    'certificate'=>$exp_certificate,
                );

                $put_candidate_exp_details_result = $this->canrepo->put_candidate_experience( $input_details_exp );

            }

        }else{
            $exp_certificate='';
            // echo "no exp_certificate";
        }

        // upload education details
        if(!empty($request->file('edu_certificate'))){

            foreach ($request->file('edu_certificate') as $key => $files) {

                $degree =  $request->post('degree')[$key];
                $university =  $request->post('university')[$key];
                $edu_start_month =  $request->post('edu_start_month')[$key];
                $edu_start_year =  $request->post('edu_start_year')[$key];
                $edu_end_month =  $request->post('edu_end_month')[$key];
                $edu_end_year =  $request->post('edu_end_year')[$key];
                $edu_certificate = "edu_certificate".uniqid().'.'.$files->getClientOriginalExtension();
                $files->move($path, $edu_certificate);

                $input_details_edu = array(
                    'cdID'=>$candidate_id,
                    'degree'=>$degree,
                    'university'=>$university,
                    'edu_start_month'=>$edu_start_month,
                    'edu_start_year'=>$edu_start_year,
                    'edu_end_month'=>$edu_end_month,
                    'edu_end_year'=>$edu_end_year,
                    'edu_certificate'=>$edu_certificate,
                    'created_on'=>date('Y-m-d'),
                );

                $put_candidate_edu_details_result = $this->canrepo->put_candidate_education( $input_details_edu );

            }

        }else{
            $edu_certificate='';
            // echo "no edu_certificate";
        }

        return "success";
    }

    public function offer_response_candidate(Request $request){

        $candidate_email = $request->input('candidate_email');

        $cdID=$request->input('cdID');
        $user_row = $this->recrepo->get_candidate_row( $cdID );

        $to_email=$candidate_email;

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

		\Mail::to($to_email)->send(new \App\Mail\CandidateMail($details));

        $recruiter_id=$request->input('created_by');

        $input_details_ad = array(
            'empID' => $recruiter_id,
        );
        $get_user_result = $this->corepo->get_user_details($input_details_ad);
      //  print_r($input_details_ad);
        $recruit_email=$get_user_result[0]->email;

        $get_position_result = $this->recrepo->get_recr_req($user_row[0]->hepl_recruitment_ref_number);

        $get_title1 = "ProHire - Confirmation";
        $get_body6 = "Dear All";
        $get_body7 = "The offer letter for candidate: Mr/Ms".$user_row[0]->candidate_name.",";
        $get_body8 = "For the position of:".$get_position_result[0]->position_title.",";
        $get_body9 = "with respect to RFH NO:".$user_row[0]->rfh_no." has been RATIFIED as enclosed" ;
        $get_body10 ="The offer is ACCEPTED by the Candidate";
        $details = [
            'title' => $get_title1,
            'body1' => $get_body6,
            'body2' => $get_body7,
            'body3' => $get_body8,
            'body4' => $get_body9,
            'body5' => $get_body10
        ];

        \Mail::to($recruit_email)
        ->cc(['karthik.d@hepl.com','rfh@hepl.com'])
        ->send(new \App\Mail\CandidateMail($details));

        $credentials = array(
            'action_for_the_day_status'=>"Offer Accepted",
            'hepl_recruitment_ref_number'=>$request->input('hepl_recruitment_ref_number'),
            'closed_date'=> date('Y-m-d'),
            'request_status'=>"Closed",
            'closed_by'=>$request->input('created_by')
        );

		$update_position_status_result = $this->recrepo->update_position_status_closed( $credentials );

        $input_details = array(
            'action_for_the_day_status'=>"Offer Accepted",
            'hepl_recruitment_ref_number'=>$request->input('hepl_recruitment_ref_number'),
            'cdID'=>$request->input('cdID'),
            'candidate_email'=>$candidate_email,
			'offer_rel_status' => $request->input('cd_or_status'),
        );

        $process_default_status_result = $this->recrepo->process_default_status_cd( $input_details );

       //inserted into preonboarding status
       $insert_details = array(
        'empID'=>$request->input('cdID'),
        'username'=>$user_row[0]->candidate_name,
        'passcode'=>bcrypt('123456'),
        'email'=>$candidate_email,
        'role_type' => "candidate",
        'pre_onboarding' => "1",
        'active' => "1",
        'Induction_mail' => "0",
        'Buddy_mail' => "0",
    );

    $insert_preonboarding_can_details = $this->recrepo->customusers_entry( $insert_details );

        // update candidate follow up

        $cfdID = 'CFD'.( ( DB::table( 'candidate_followup_details' )->max( 'id' ) )+1 );

        $candidate_followup_details = array(
            'cfdID'=>$cfdID,
            'cdID'=>$request->input('cdID'),
            'rfh_no'=>$request->input('rfh_no'),
            'follow_up_status'=>"Offer Accepted",
            'created_on'=>date('Y-m-d'),
            'created_by'=>$request->input('created_by')
        );
        Candidate_followup_details::create($candidate_followup_details);

        $response = 'Updated';
        return response()->json( ['response' => $response] );
    }

    public function offer_reject_update(Request $request){
        $credentials = array(
            'cdID'=>$request->input('cdID'),
            'rfh_no'=>$request->input('rfh_no'),
            'remark'=>$request->input('remark'),
            'profile_status'=>"Offer Rejected",
            'created_on'=>date('Y-m-d'),
            'created_by'=>$request->input('created_by')
        );
        $reject_status_update = $this->recrepo->update_reject_status( $credentials );
        $response = 'Updated';

        $cdID=$request->input('cdID');
        $user_row = $this->recrepo->get_candidate_row( $cdID );
        $recruiter_id=$request->input('created_by');

        $input_details_ad = array(
            'empID' => $recruiter_id,
        );
        $get_user_result = $this->corepo->get_user_details($input_details_ad);
      //  print_r($input_details_ad);
        $recruit_email=$get_user_result[0]->email;

        $get_position_result = $this->recrepo->get_recr_req($user_row[0]->hepl_recruitment_ref_number);


        $get_title1 = "ProHire - Confirmation";
        $get_body6 = "Dear All";
        $get_body7 = "The offer letter for candidate: Mr/Ms".$user_row[0]->candidate_name.",";
        $get_body8 = "For the position of:".$get_position_result[0]->position_title.",";
        $get_body9 = "with respect to RFH NO:".$user_row[0]->rfh_no." has been RATIFIED as enclosed" ;
        $get_body10 ="The offer is REJECTED by the Candidate";
        $details = [
            'title' => $get_title1,
            'body1' => $get_body6,
            'body2' => $get_body7,
            'body3' => $get_body8,
            'body4' => $get_body9,
            'body5' => $get_body10
        ];

        \Mail::to($recruit_email)
        ->cc(['karthik.d@hepl.com','rfh@hepl.com'])
        ->send(new \App\Mail\CandidateMail($details));

        return response()->json( ['response' => $response] );
    }
    public function test(Request $request){
    //     $data= [
    //         "test"=>'kjdsfhds',
    //         "test4" =>"lkxcjv"
    //     ];
    //    // return view("test")->with($data);
    $input_details_cd = array();
    $input_details_cd['candidate_mobile'] = $request->post('candidate_contactno');
    $input_details_cd['gender'] = $request->post('candidate_gender');
    $input_details_cd['middle_name'] = $request->post('middle_name');
    $input_details_cd['last_name'] = $request->post('last_name');
    $input_details_cd['dob'] = $request->post('dob');
    $input_details_cd['age'] = $request->post('age');
    $input_details_cd['marital_status'] = $request->post('marital_status');
    $input_details_cd['blood_group'] = $request->post('blood_gr');
       echo json_encode($input_details_cd);
    }
}
