<?php

namespace App\Repositories;

interface ICandidateRepository {

    public function put_candidate_experience( $input_details_exp );
    public function update_candidate_experience( $input_details_exp );
    public function remove_experience_fields_exist( $input_details_exp );

    public function put_candidate_education( $input_details_edu );
    public function update_candidate_education( $input_details_edu );
    public function remove_education_fields_exist( $input_details_edu );
    
    public function update_candidate_details( $input_details_cd );

    public function put_candidate_benefits( $input_details_cbd );
    public function update_candidate_benefits( $input_details_cbd );
    public function remove_compensation_fields_exist( $input_details_cbd );

    public function offer_release_followup_entry( $or_followup_input );
    
}