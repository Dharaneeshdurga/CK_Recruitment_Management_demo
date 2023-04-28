<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\ICardRepository; 

class PhcardController extends Controller
{
    public function __construct(ICardRepository $crepo)
    {
        $this->crepo = $crepo;

        $this->middleware('backend_coordinator');
    }
    
    public function prohire_card()
    {
        return view('prohire_card');
    }

    public function get_cv_count_details(Request $request){

        $get_from_date = $request->input('get_from_date');
        $get_to_date = $request->input('get_to_date');
        $af_recruiter = $request->input('af_recruiter');
        $af_teams = $request->input('af_teams');
        $af_division = $request->input('af_division');
        $af_raisedby = $request->input('af_raisedby');
        $af_billable = $request->input('af_billable');

        $input_details = array(
            'get_from_date'=>$get_from_date,
            'get_to_date'=>$get_to_date,
            'af_recruiter'=>$af_recruiter,
            'af_teams'=>$af_teams,
            'af_division'=>$af_division,
            'af_raisedby'=>$af_raisedby,
            'af_billable'=>$af_billable,
        );

        // print_r($input_details);
        $get_cv_count_details_res = $this->crepo->get_cv_count_details( $input_details );


        return response()->json( ['response' => $get_cv_count_details_res] );


    }

    public function get_cpcv_count_details(Request $request){
        $get_from_date = $request->input('get_from_date');
        $get_to_date = $request->input('get_to_date');
        $af_recruiter = $request->input('af_recruiter');
        $af_teams = $request->input('af_teams');
        $af_division = $request->input('af_division');
        $af_raisedby = $request->input('af_raisedby');
        $af_billable = $request->input('af_billable');

        $input_details = array(
            'af_from_date'=>$get_from_date,
            'af_to_date'=>$get_to_date,
            'af_recruiter'=>$af_recruiter,
            'af_teams'=>$af_teams,
            'af_division'=>$af_division,
            'af_raisedby'=>$af_raisedby,
            'af_billable'=>$af_billable,
        );

        $get_cpcv_count_details_res = $this->crepo->get_cpcv_count_details( $input_details );

        if(count($get_cpcv_count_details_res) !=0){

            for ($i=0; $i < count($get_cpcv_count_details_res); $i++) { 
                $recruiter_count[] = $get_cpcv_count_details_res[$i]->closed_by_name;
            }
            
            // print_r($recruiter_count);
            // echo "<pre>";
            if(count($recruiter_count) !=0){
                $get_res = array_count_values($recruiter_count);
                $array_recruiter = array_keys($get_res);
                $array_cpcv = array_values($get_res);
            }
            
            // print_r($array_recruiter);
            // print_r($array_cpcv);

            return response()->json( [
                'array_recruiter' => $array_recruiter,
                'array_cpcv' => $array_cpcv
                ] );
        }
        
    }

    public function get_stagesof_recruitment(Request $request){
        $hepl_recruitment_ref_number = $request->input('hepl_recruitment_ref_number');
        // echo $hepl_recruitment_ref_number;
        // echo "<br>";
        $rfh_no = $request->input('rfh_no');
        // echo $rfh_no;
        // echo "<br>";
        $input_details = array(
            'hepl_recruitment_ref_number'=>$hepl_recruitment_ref_number,
            'rfh_no'=>$rfh_no,
        );

        // get position title 
        $get_position_title_res = $this->crepo->get_position_title( $input_details );
        if(count($get_position_title_res) !=0){
            $position_title = $get_position_title_res[0]->position_title;
        }else{
            $position_title = '';
        }
        // Date of RFH
        $get_date_of_rfh_res = $this->crepo->get_date_of_rfh( $input_details );
        // echo $get_date_of_rfh_res[0]->open_date;
        // echo "<br>";

        //Date of Profile Screened 
        $get_dops_cd_res = $this->crepo->get_dops_cd( $input_details );
        // print_r($get_dops_cd_res);
        $get_cdid =array();
        if(count($get_dops_cd_res) !=0){
            foreach ($get_dops_cd_res as $key => $value) {
                $get_cdid[] = $value->cdID;
            }
        }
        // print_r($get_cdid);
        // echo "<br>";
        // echo "'" . implode("','", $get_dops_cd_res) . "'";
        $input_details_ps = array(
            'cdID'=>$get_cdid,
        );

        // check if candidate not show or offer rejected for this hepl ref number
        $check_candidate_not_show_res = $this->crepo->check_candidate_not_show( $input_details_ps );
        // print_r($check_candidate_not_show_res);
        if(count($check_candidate_not_show_res) == 0){

            // $current_open_date = date("d-m-Y", strtotime($get_date_of_rfh_res[0]->open_date));
            $current_open_date = "";

            $get_date_of_profile_screened_res = $this->crepo->get_date_of_profile_screened( $input_details_ps );
            
            if(count($get_date_of_rfh_res) !=0 && count($get_date_of_profile_screened_res) !=0){
                
                $date1=date_create($get_date_of_rfh_res[0]->open_date);
                $date2=date_create($get_date_of_profile_screened_res[0]->created_on);
                $diff=date_diff($date1,$date2);
                $stage_one = $diff->format("%a days");
                // echo "stage_one - ".$stage_one;

                // Last Interviewed date
                $get_date_of_last_interviewdate_res = $this->crepo->get_date_of_last_interviewdate( $input_details_ps );
                // print_r($get_date_of_last_interviewdate_res);
                
                if(count($get_date_of_profile_screened_res) !=0 && count($get_date_of_last_interviewdate_res) !=0){
                    
                    $date1=date_create($get_date_of_profile_screened_res[0]->created_on);
                    $date2=date_create($get_date_of_last_interviewdate_res[0]->created_on);

                    $diff=date_diff($date1,$date2);
                    $stage_two = $diff->format("%a days");
                    // echo "stage_two - ".$stage_two;

                    // Offer Released Date
                    $get_date_of_offer_releaseddate_res = $this->crepo->get_date_of_offer_releaseddate( $input_details_ps );
                    
                    if(count($get_date_of_last_interviewdate_res) !=0 && count($get_date_of_offer_releaseddate_res) !=0){
                        
                        $date1=date_create($get_date_of_last_interviewdate_res[0]->created_on);
                        $date2=date_create($get_date_of_offer_releaseddate_res[0]->created_on);

                        $diff=date_diff($date1,$date2);
                        $stage_three = $diff->format("%a days");
                        // echo "stage_three - ".$stage_three;

                        // Offer Accepted Date
                        $get_date_of_offer_accepteddate_res = $this->crepo->get_date_of_offer_accepteddate( $input_details_ps );
                        
                        if(count($get_date_of_offer_releaseddate_res) !=0 && count($get_date_of_offer_accepteddate_res) !=0){
                            
                            $date1=date_create($get_date_of_offer_releaseddate_res[0]->created_on);
                            $date2=date_create($get_date_of_offer_accepteddate_res[0]->created_on);

                            $diff=date_diff($date1,$date2);
                            $stage_four = $diff->format("%a days");
                            // echo "stage_four - ".$stage_four;

                            // Onboarded  Date
                            $get_date_of_onboarded_res = $this->crepo->get_date_of_candidate_onboarded( $input_details_ps );
                            
                            if(count($get_date_of_offer_accepteddate_res) !=0 && count($get_date_of_onboarded_res) !=0){
                                
                                $date1=date_create($get_date_of_offer_accepteddate_res[0]->created_on);
                                $date2=date_create($get_date_of_onboarded_res[0]->created_on);

                                $diff=date_diff($date1,$date2);
                                $stage_five = $diff->format("%a days");
                                // echo "stage_five - ".$stage_five;
                            }
                            else{
                                $stage_five = '-';
                            }
                        }
                        else{
                            $stage_four = '-';
                            $stage_five = '-';

                        }
                    }
                    else{
                        $stage_three = '-';
                        $stage_four = '-';
                        $stage_five = '-';
                    }
                }
                else{
                    $stage_two  = '-';
                    $stage_three = '-';
                    $stage_four = '-';
                    $stage_five = '-';
                }
            }
            else{
                $stage_one = '-';
                $stage_two  = '-';
                $stage_three = '-';
                $stage_four = '-';
                $stage_five = '-';

            }
            $get_candidate_no_show_details = '';
            // $candidate_no_show_details = '';
            $original_rfh_date = date("d-m-Y", strtotime($get_date_of_rfh_res[0]->open_date));
        }
        else{
            // echo "else";
            $get_date_of_rfh_res = $this->crepo->get_date_of_rfh( $input_details );
            // echo $get_date_of_rfh_res[0]->open_date;
            $current_open_date = date("d-m-Y", strtotime($get_date_of_rfh_res[0]->open_date));
            
            if($get_date_of_rfh_res[0]->open_date !=''){
                //Date of Profile Screened 
                $get_dops_cd_res = $this->crepo->get_dops_cd( $input_details );

                $get_cdid =array();
                if(count($get_dops_cd_res) !=0){
                    foreach ($get_dops_cd_res as $key => $value) {
                        $get_cdid[] = $value->cdID;
                    }
                }
                
                $input_details_nad = array(
                    'cdID'=>$get_cdid,
                    'date_cond'=>$get_date_of_rfh_res[0]->open_date,
                );

                //get next action date 
                $profile_screened_next_date = $this->crepo->get_date_of_profile_screened_next( $input_details_nad );
                // echo $profile_screened_next_date[0]->created_on;

                if(count($get_date_of_rfh_res) !=0 && count($profile_screened_next_date) !=0){
                    
                    $date1=date_create($get_date_of_rfh_res[0]->open_date);
                    $date2=date_create($profile_screened_next_date[0]->created_on);
                    $diff=date_diff($date1,$date2);
                    $stage_one = $diff->format("%a days");

                    // Last Interviewed date
                    $get_date_of_last_interviewdate_next_res = $this->crepo->get_date_of_last_interviewdate_next( $input_details_nad );
                    
                    if(count($profile_screened_next_date) !=0 && count($get_date_of_last_interviewdate_next_res) !=0){
                    
                        $date1=date_create($profile_screened_next_date[0]->created_on);
                        $date2=date_create($get_date_of_last_interviewdate_next_res[0]->created_on);
    
                        $diff=date_diff($date1,$date2);
                        $stage_two = $diff->format("%a days");

                        // Offer Released Date
                        $get_date_of_offer_releaseddate_next_res = $this->crepo->get_date_of_offer_releaseddate_next( $input_details_nad );
                        
                        if(count($get_date_of_last_interviewdate_next_res) !=0 && count($get_date_of_offer_releaseddate_next_res) !=0){
                            
                            $date1=date_create($get_date_of_last_interviewdate_next_res[0]->created_on);
                            $date2=date_create($get_date_of_offer_releaseddate_next_res[0]->created_on);

                            $diff=date_diff($date1,$date2);
                            $stage_three = $diff->format("%a days");
                            
                            // Offer Accepted Date
                            $get_date_of_offer_accepteddate_next_res = $this->crepo->get_date_of_offer_accepteddate_next( $input_details_nad );
                                                    
                            if(count($get_date_of_offer_accepteddate_next_res) !=0 && count($get_date_of_offer_releaseddate_next_res) !=0){
                                
                                $date1=date_create($get_date_of_offer_accepteddate_next_res[0]->created_on);
                                $date2=date_create($get_date_of_offer_releaseddate_next_res[0]->created_on);

                                $diff=date_diff($date1,$date2);
                                $stage_four = $diff->format("%a days");
                                // echo "stage_four - ".$stage_four;

                                // Onboarded  Date
                                $get_date_of_onboarded_next_res = $this->crepo->get_date_of_candidate_onboarded_next( $input_details_nad );
                                
                                if(count($get_date_of_offer_accepteddate_next_res) !=0 && count($get_date_of_onboarded_next_res) !=0){
                                    
                                    $date1=date_create($get_date_of_offer_accepteddate_next_res[0]->created_on);
                                    $date2=date_create($get_date_of_onboarded_next_res[0]->created_on);

                                    $diff=date_diff($date1,$date2);
                                    $stage_five = $diff->format("%a days");
                                    // echo "stage_five - ".$stage_five;
                                }
                                else{
                                    $stage_five = '-';
                                }
                            }else{
                                $stage_four = '-';
                                $stage_five = '-';

                            }   
                        }else{
                            $stage_three = '-';
                            $stage_four = '-';
                            $stage_five = '-';
                        }
                    }
                    else{
                        $stage_two  = '-';
                        $stage_three = '-';
                        $stage_four = '-';
                        $stage_five = '-';
                    }
                }else{
                    $stage_one = '-';
                    $stage_two  = '-';
                    $stage_three = '-';
                    $stage_four = '-';
                    $stage_five = '-';
                }
            }

            $get_candidate_no_show_details  = $this->crepo->get_candidate_no_show_details( $input_details_nad );
             // get original date of RFH
                $input_details_orfh = array(
                    'rfh_no'=>$rfh_no,
                );
            $get_original_rfh_date  = $this->crepo->get_original_rfh_date( $input_details_orfh );
            if(count($get_original_rfh_date) !=0){

                $originalDate = $get_original_rfh_date[0]->created_date;
                $original_rfh_date = date("d-m-Y", strtotime($originalDate));
                        
            }
        }

       
        
        return response()->json( [
            'stage_one' => $stage_one,
            'stage_two' => $stage_two,
            'stage_three' => $stage_three,
            'stage_four' => $stage_four,
            'stage_five' => $stage_five,
            'get_candidate_no_show_details' => $get_candidate_no_show_details,
            'get_original_rfh_date' => $original_rfh_date,
            'current_open_date' =>$current_open_date,
            'position_title' => $position_title
        ]);

    }

    public function get_stagesof_recruitment_rr(Request $request){
        $hepl_recruitment_ref_number = $request->input('hepl_recruitment_ref_number');
        
        $rfh_no = $request->input('rfh_no');

        $assigned_to = $request->input('assigned_to');
        
        $input_details = array(
            'hepl_recruitment_ref_number'=>$hepl_recruitment_ref_number,
            'rfh_no'=>$rfh_no,
            'assigned_to'=>$assigned_to,
        );

        // get position title 
        $get_position_title_res = $this->crepo->get_position_title( $input_details );

        if(count($get_position_title_res) !=0){
            $position_title = $get_position_title_res[0]->position_title;
        }else{
            $position_title = '';
        }

        // Date of RFH
        $get_date_of_rfh_res = $this->crepo->get_date_of_rfh( $input_details );

        //Date of Profile Screened 
        $get_dops_cd_res = $this->crepo->get_dops_cd( $input_details );

        $get_cdid =array();
        if(count($get_dops_cd_res) !=0){
            foreach ($get_dops_cd_res as $key => $value) {
                $get_cdid[] = $value->cdID;
            }
        }
        
        $input_details_ps = array(
            'cdID'=>$get_cdid,
            'assigned_to'=>$assigned_to,
        );

        // check if candidate not show or offer rejected for this hepl ref number
        $check_candidate_not_show_res = $this->crepo->check_candidate_not_show( $input_details_ps );

        if(count($check_candidate_not_show_res) == 0){

            $current_open_date = "";

            $get_date_of_profile_screened_res = $this->crepo->get_date_of_profile_screened( $input_details_ps );
            
            if(count($get_date_of_rfh_res) !=0 && count($get_date_of_profile_screened_res) !=0){
                
                $date1=date_create($get_date_of_rfh_res[0]->open_date);
                $date2=date_create($get_date_of_profile_screened_res[0]->created_on);
                $diff=date_diff($date1,$date2);
                $stage_one = $diff->format("%a days");
                // echo "stage_one - ".$stage_one;

                // Last Interviewed date
                $get_date_of_last_interviewdate_res = $this->crepo->get_date_of_last_interviewdate( $input_details_ps );
                // print_r($get_date_of_last_interviewdate_res);
                
                if(count($get_date_of_profile_screened_res) !=0 && count($get_date_of_last_interviewdate_res) !=0){
                    
                    $date1=date_create($get_date_of_profile_screened_res[0]->created_on);
                    $date2=date_create($get_date_of_last_interviewdate_res[0]->created_on);

                    $diff=date_diff($date1,$date2);
                    $stage_two = $diff->format("%a days");
                    // echo "stage_two - ".$stage_two;

                    // Offer Released Date
                    $get_date_of_offer_releaseddate_res = $this->crepo->get_date_of_offer_releaseddate( $input_details_ps );
                    
                    if(count($get_date_of_last_interviewdate_res) !=0 && count($get_date_of_offer_releaseddate_res) !=0){
                        
                        $date1=date_create($get_date_of_last_interviewdate_res[0]->created_on);
                        $date2=date_create($get_date_of_offer_releaseddate_res[0]->created_on);

                        $diff=date_diff($date1,$date2);
                        $stage_three = $diff->format("%a days");
                        // echo "stage_three - ".$stage_three;

                        // Offer Accepted Date
                        $get_date_of_offer_accepteddate_res = $this->crepo->get_date_of_offer_accepteddate( $input_details_ps );
                        
                        if(count($get_date_of_offer_releaseddate_res) !=0 && count($get_date_of_offer_accepteddate_res) !=0){
                            
                            $date1=date_create($get_date_of_offer_releaseddate_res[0]->created_on);
                            $date2=date_create($get_date_of_offer_accepteddate_res[0]->created_on);

                            $diff=date_diff($date1,$date2);
                            $stage_four = $diff->format("%a days");
                            // echo "stage_four - ".$stage_four;

                            // Onboarded  Date
                            $get_date_of_onboarded_res = $this->crepo->get_date_of_candidate_onboarded( $input_details_ps );
                            
                            if(count($get_date_of_offer_accepteddate_res) !=0 && count($get_date_of_onboarded_res) !=0){
                                
                                $date1=date_create($get_date_of_offer_accepteddate_res[0]->created_on);
                                $date2=date_create($get_date_of_onboarded_res[0]->created_on);

                                $diff=date_diff($date1,$date2);
                                $stage_five = $diff->format("%a days");
                                // echo "stage_five - ".$stage_five;
                            }
                            else{
                                $stage_five = '-';
                            }
                        }
                        else{
                            $stage_four = '-';
                            $stage_five = '-';

                        }
                    }
                    else{
                        $stage_three = '-';
                        $stage_four = '-';
                        $stage_five = '-';
                    }
                }
                else{
                    $stage_two  = '-';
                    $stage_three = '-';
                    $stage_four = '-';
                    $stage_five = '-';
                }
            }
            else{
                $stage_one = '-';
                $stage_two  = '-';
                $stage_three = '-';
                $stage_four = '-';
                $stage_five = '-';

            }
            $get_candidate_no_show_details = '';
            // $candidate_no_show_details = '';
            $original_rfh_date = date("d-m-Y", strtotime($get_date_of_rfh_res[0]->open_date));
        }
        else{

            $get_date_of_rfh_res = $this->crepo->get_date_of_rfh( $input_details );

            $current_open_date = date("d-m-Y", strtotime($get_date_of_rfh_res[0]->open_date));
            
            if($get_date_of_rfh_res[0]->open_date !=''){
                //Date of Profile Screened 
                $get_dops_cd_res = $this->crepo->get_dops_cd( $input_details );

                $get_cdid =array();
                if(count($get_dops_cd_res) !=0){
                    foreach ($get_dops_cd_res as $key => $value) {
                        $get_cdid[] = $value->cdID;
                    }
                }
                
                $input_details_nad = array(
                    'cdID'=>$get_cdid,
                    'date_cond'=>$get_date_of_rfh_res[0]->open_date,
                    'assigned_to'=>$assigned_to,
                );

                //get next action date 
                $profile_screened_next_date = $this->crepo->get_date_of_profile_screened_next( $input_details_nad );

                if(count($get_date_of_rfh_res) !=0 && count($profile_screened_next_date) !=0){
                    
                    $date1=date_create($get_date_of_rfh_res[0]->open_date);
                    $date2=date_create($profile_screened_next_date[0]->created_on);
                    $diff=date_diff($date1,$date2);
                    $stage_one = $diff->format("%a days");

                    // Last Interviewed date
                    $get_date_of_last_interviewdate_next_res = $this->crepo->get_date_of_last_interviewdate_next( $input_details_nad );
                    
                    if(count($profile_screened_next_date) !=0 && count($get_date_of_last_interviewdate_next_res) !=0){
                    
                        $date1=date_create($profile_screened_next_date[0]->created_on);
                        $date2=date_create($get_date_of_last_interviewdate_next_res[0]->created_on);
    
                        $diff=date_diff($date1,$date2);
                        $stage_two = $diff->format("%a days");

                        // Offer Released Date
                        $get_date_of_offer_releaseddate_next_res = $this->crepo->get_date_of_offer_releaseddate_next( $input_details_nad );
                        
                        if(count($get_date_of_last_interviewdate_next_res) !=0 && count($get_date_of_offer_releaseddate_next_res) !=0){
                            
                            $date1=date_create($get_date_of_last_interviewdate_next_res[0]->created_on);
                            $date2=date_create($get_date_of_offer_releaseddate_next_res[0]->created_on);

                            $diff=date_diff($date1,$date2);
                            $stage_three = $diff->format("%a days");
                            
                            // Offer Accepted Date
                            $get_date_of_offer_accepteddate_next_res = $this->crepo->get_date_of_offer_accepteddate_next( $input_details_nad );
                                                    
                            if(count($get_date_of_offer_accepteddate_next_res) !=0 && count($get_date_of_offer_releaseddate_next_res) !=0){
                                
                                $date1=date_create($get_date_of_offer_accepteddate_next_res[0]->created_on);
                                $date2=date_create($get_date_of_offer_releaseddate_next_res[0]->created_on);

                                $diff=date_diff($date1,$date2);
                                $stage_four = $diff->format("%a days");
                                // echo "stage_four - ".$stage_four;

                                // Onboarded  Date
                                $get_date_of_onboarded_next_res = $this->crepo->get_date_of_candidate_onboarded_next( $input_details_nad );
                                
                                if(count($get_date_of_offer_accepteddate_next_res) !=0 && count($get_date_of_onboarded_next_res) !=0){
                                    
                                    $date1=date_create($get_date_of_offer_accepteddate_next_res[0]->created_on);
                                    $date2=date_create($get_date_of_onboarded_next_res[0]->created_on);

                                    $diff=date_diff($date1,$date2);
                                    $stage_five = $diff->format("%a days");
                                    // echo "stage_five - ".$stage_five;
                                }
                                else{
                                    $stage_five = '-';
                                }
                            }else{
                                $stage_four = '-';
                                $stage_five = '-';

                            }   
                        }else{
                            $stage_three = '-';
                            $stage_four = '-';
                            $stage_five = '-';
                        }
                    }
                    else{
                        $stage_two  = '-';
                        $stage_three = '-';
                        $stage_four = '-';
                        $stage_five = '-';
                    }
                }else{
                    $stage_one = '-';
                    $stage_two  = '-';
                    $stage_three = '-';
                    $stage_four = '-';
                    $stage_five = '-';
                }
            }

            $get_candidate_no_show_details  = $this->crepo->get_candidate_no_show_details( $input_details_nad );
             // get original date of RFH
                $input_details_orfh = array(
                    'rfh_no'=>$rfh_no,
                    'assigned_to'=>$assigned_to,

                );
            $get_original_rfh_date  = $this->crepo->get_original_rfh_date( $input_details_orfh );
            if(count($get_original_rfh_date) !=0){

                $originalDate = $get_original_rfh_date[0]->created_date;
                $original_rfh_date = date("d-m-Y", strtotime($originalDate));
                        
            }
        }

       
        
        return response()->json( [
            'stage_one' => $stage_one,
            'stage_two' => $stage_two,
            'stage_three' => $stage_three,
            'stage_four' => $stage_four,
            'stage_five' => $stage_five,
            'get_candidate_no_show_details' => $get_candidate_no_show_details,
            'get_original_rfh_date' => $original_rfh_date,
            'current_open_date' =>$current_open_date,
            'position_title' => $position_title
        ]);

    }


    public function send_mail_test(){
        $details = [
            'title' => 'Mail from Prohire',
            'body' => 'This is for testing email using smtp in laravel framework'
        ];
       
        \Mail::to('aishwaryaa.b@hemas.in')->send(new \App\Mail\MyTestMail($details));
       
        dd("Email is Sent.");
    }
}
