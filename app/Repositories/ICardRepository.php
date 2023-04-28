<?php

namespace App\Repositories;

interface ICardRepository {

    public function get_cv_count_details($advanced_filter);
    public function get_cpcv_count_details($advanced_filter);
    public function get_date_of_rfh($advanced_filter);
    public function get_date_of_profile_screened( $input_details );
    public function get_dops_cd( $input_details );
    public function get_date_of_last_interviewdate( $input_details );
    public function get_date_of_offer_releaseddate( $input_details );
    public function get_date_of_offer_accepteddate( $input_details );
    public function get_date_of_candidate_onboarded( $input_details );
    public function check_candidate_not_show( $input_details );
    public function get_date_of_profile_screened_next( $input_details_nad );
    public function get_date_of_last_interviewdate_next( $input_details_nad );
    public function get_date_of_offer_releaseddate_next( $input_details_nad );
    public function get_date_of_offer_accepteddate_next( $input_details_nad );
    public function get_date_of_candidate_onboarded_next( $input_details_nad );
    public function get_candidate_no_show_details( $input_details_nad );

    public function get_original_rfh_date( $input_details_orfh );
    public function get_position_title( $input_details );
}