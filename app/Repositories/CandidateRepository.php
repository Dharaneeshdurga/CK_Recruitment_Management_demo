<?php

namespace App\Repositories;

use App\Models\Candidate_education_details;
use App\Models\Candidate_experience_details;
use App\Models\Candidate_details;
use App\Models\Candidate_benefits_details;
use App\Models\Offer_release_followup_details;

use DB;
class CandidateRepository implements ICandidateRepository
{  
    
    public function put_candidate_education( $input_details_edu ){
        
        $c_edutbl = new Candidate_education_details();

        $c_edutbl->cdID = $input_details_edu['cdID'];
        $c_edutbl->rfh_no = $input_details_edu['rfh_no'];
        $c_edutbl->hepl_recruitment_ref_number = $input_details_edu['hepl_recruitment_ref_number'];
        $c_edutbl->degree = $input_details_edu['degree'];
        $c_edutbl->university = $input_details_edu['university'];
        $c_edutbl->edu_start_month = $input_details_edu['edu_start_month'];
        $c_edutbl->edu_start_year = $input_details_edu['edu_start_year'];
        $c_edutbl->edu_end_month = $input_details_edu['edu_end_month' ];
        $c_edutbl->edu_end_year = $input_details_edu['edu_end_year' ];
        $c_edutbl->edu_certificate = $input_details_edu['edu_certificate' ];
        $c_edutbl->created_on = $input_details_edu['created_on' ];
        
        $c_edutbl->save();

        if($c_edutbl) {
            return true;
        } else {
            return false;
        }
    }

    public function update_candidate_education( $input_details_edu ){
        $update_cdtbl = new Candidate_education_details();
        $update_cdtbl = $update_cdtbl->where( 'id', '=', $input_details_edu['id'] );

        $update_cdtbl->update($input_details_edu);
    }

    public function remove_education_fields_exist( $input_details ){
        DB::table('candidate_education_details')->where('id', $input_details['id'])->delete();

    }

    public function put_candidate_experience( $input_details_exp ){
        
        $c_exptbl = new Candidate_experience_details();

        $c_exptbl->cdID = $input_details_exp['cdID'];
        $c_exptbl->rfh_no = $input_details_exp['rfh_no'];
        $c_exptbl->hepl_recruitment_ref_number = $input_details_exp['hepl_recruitment_ref_number'];
        $c_exptbl->job_title = $input_details_exp['job_title'];
        $c_exptbl->company_name = $input_details_exp['company_name'];
        $c_exptbl->exp_start_month = $input_details_exp['exp_start_month'];
        $c_exptbl->exp_start_year = $input_details_exp['exp_start_year'];
        $c_exptbl->exp_end_month = $input_details_exp['exp_end_month' ];
        $c_exptbl->exp_end_year = $input_details_exp['exp_end_year' ];
        $c_exptbl->certificate = $input_details_exp['certificate' ];
        
        $c_exptbl->save();

        if($c_exptbl) {
            return true;
        } else {
            return false;
        }
    }

    public function update_candidate_experience( $input_details_exp ){
        $update_cdtbl = new Candidate_experience_details();
        $update_cdtbl = $update_cdtbl->where( 'id', '=', $input_details_exp['id'] );

        $update_cdtbl->update($input_details_exp);
    }

    public function remove_experience_fields_exist( $input_details ){
        DB::table('candidate_experience_details')->where('id', $input_details['id'])->delete();

    }

    public function update_candidate_details( $input_details_cd ){

        $update_cdtbl = new Candidate_details();
        $update_cdtbl = $update_cdtbl->where( 'cdID', '=', $input_details_cd['cdID'] );

        $update_cdtbl->update($input_details_cd);
        // $update_cdtbl->update([ 
        //     'proof_of_identity' => $input_details_cd['proof_of_identity'],
        //     'poi_filename' => $input_details_cd['poi_filename'],
        //     'proof_of_address' => $input_details_cd['proof_of_address'],
        //     'poa_filename'=> $input_details_cd['poa_filename'], 
        //     'candidate_mobile'=> $input_details_cd['candidate_mobile']
            
        // ]);
    }
    
    public function put_candidate_benefits( $input_details_cbd ){
        
        $c_bentbl = new Candidate_benefits_details();

        $c_bentbl->cdID = $input_details_cbd['cdID'];
        $c_bentbl->rfh_no = $input_details_cbd['rfh_no'];
        $c_bentbl->hepl_recruitment_ref_number = $input_details_cbd['hepl_recruitment_ref_number'];
        $c_bentbl->doc_type = $input_details_cbd['doc_type'];
        $c_bentbl->doc_filename = $input_details_cbd['doc_filename'];
        $c_bentbl->created_on = $input_details_cbd['created_on' ];
        
        $c_bentbl->save();

        if($c_bentbl) {
            return true;
        } else {
            return false;
        }
    }
    public function update_candidate_benefits( $input_details_cbd ){
        $update_cdtbl = new Candidate_benefits_details();
        $update_cdtbl = $update_cdtbl->where( 'id', '=', $input_details_cbd['id'] );

        $update_cdtbl->update($input_details_cbd);
    }
    public function remove_compensation_fields_exist( $input_details ){
        DB::table('candidate_benefits_details')->where('id', $input_details['id'])->delete();

    }

    public function offer_release_followup_entry( $or_followup_input ){

        $c_orfdtbl = new Offer_release_followup_details();
        $c_orfdtbl->orfID = 'ORF'.str_pad( ( $c_orfdtbl->max( 'id' )+1 ), 4, '0', STR_PAD_LEFT );
        $c_orfdtbl->cdID = $or_followup_input['cdID'];
        $c_orfdtbl->rfh_no = $or_followup_input['rfh_no'];
        $c_orfdtbl->hepl_recruitment_ref_number = $or_followup_input['hepl_recruitment_ref_number'];
        $c_orfdtbl->description = $or_followup_input['description'];
        $c_orfdtbl->created_by = $or_followup_input['created_by'];
       
        $c_orfdtbl->save();

        if($c_orfdtbl) {
            return true;
        } else {
            return false;
        }

    }

}