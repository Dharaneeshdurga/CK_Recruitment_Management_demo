<?php

namespace App\Repositories;

interface IRecruiterRepository {

    public function get_assigned_reqcruitment_request( $input_details );
    public function get_assigned_reqcruitment_request_old_positions( $input_details );

    public function show_uploaded_cv( $hepl_recruitment_ref_number );
    public function show_uploaded_cv_recruiter( $input_details );
    public function process_default_status($input_details);
    public function process_offer_release_details($input_details);
    public function cd_status_update( $input_details_cd );
    public function get_offer_released_tb( $credentials);
    public function check_offer_release_details($credentials);
    public function offer_released_edit_process( $credentials );
    public function or_ldj_history($credentials);
    public function customusers_entry( $insert_details );
    public function file_proof_attach( $input_details_f );
    public function get_offer_release_details( $input_details );

    public function get_no_profile_position( $credentials );
    public function get_no_candidate_onboarded( $credentials );

    public function update_position_status_closed( $credentials );
    public function update_position_status_orldj( $credentials );
    public function update_reject_status( $credentials );

    public function candidate_follow_up_history( $input_details );

    public function update_red_flag( $rfs_input_details );

    public function get_offer_released_report( $input_details );
    public function get_offer_released_ld_report( $input_details );

    public function get_candidate_onborded_history($credentials);

    public function get_candidate_profile( $input_details );

    public function get_assigned_reqcruitment_inactive($credentials);

    public function get_ticket_report_recruiter( $input_details );
    public function ticket_candidate_details( $input_details );

    public function get_ticket_report_recruiter_afilter( $advanced_filter );
    public function get_ticket_report_recruiter_custom_filter( $input_details );

    public function get_candidate_profile_af( $advanced_filter );

    public function ticket_candidate_details_af( $advanced_filter );

    // public function initiate_backfil_reopen($input_details);

    public function get_tblrfh_details($input_details);
    public function get_interviewer_self( $rfh_no );

    public function get_no_profile_position_recreq($input_details);

    public function get_recr_req($input_details);
    public function get_candidate_row($input_details);
    public function get_cv_count($input_details);

    public function modify_close_date( $input_details );

    public function candidate_follow_up_history_pd( $input_details );

    public function process_candidate_delete( $input_details );

    public function get_candidate_details_ed( $input_details );
    public function process_candidate_edit( $input_details );

    public function dateofjoining_update( $input_details );

    public function get_current_status_rr( $input_details_cc );

    public function get_candidate_profile_dc($input_details);

    public function get_candidate_edu_details( $input_details );
    public function get_candidate_exp_details( $input_details );
    public function get_candidate_benefits_details( $input_details );
    public function process_cdoc_status( $input_details );
    public function process_orpop_status( $input_details );

    public function insert_ctc_calc( $ctc_calculation_data );
    public function update_ctc_calc( $ctc_calculation_data );
    public function check_ctc_calc( $input_details_cd );
    public function cd_status_update_ors( $credentials );

    public function get_offer_accepted_for_rr_dt( $input_details );
    public function process_default_status_cd( $input_details );

    public function get_approved_offers( $input_details );
    public function update_new_doj( $up_details );
}
