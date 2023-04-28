<?php

namespace App\Repositories;

interface ICoordinatorRepository {

    public function reqcruitment_requestEntry( $form_credentials );
    public function get_reqcruitment_request_default($where_cond);
    public function get_reqcruitment_request_default_report();
    public function get_reqcruitment_request_afilter( $advanced_filter );
    public function get_reqcruitment_request($rfh_no);
    public function get_recruiter_list( );
    public function get_recruiter_list_all();
    public function process_recruitment_assign( $input_details );
    public function get_last_hepl_reference_no();
    public function get_recruitment_for_duplicate( $input_details );

    public function get_candidate_profile_all( $input_details );

    public function process_ticket_edit( $input_details );
    public function process_ticket_delete( $input_details );
    public function process_recruiter_delete( $input_details );

    public function get_ticket_candidate_details( $input_details );

    public function get_user_row($emp_id);

    public function get_band_details();
    public function get_position_title_af();
    public function get_location_af();
    public function get_business_af();
    public function get_function_af();
    public function get_recruiter_list_af();
    public function get_recruiter_team_list_af( $team );
    public function get_division_af(  );
    public function get_raisedby_af();
    public function get_approvedby_af();
    public function get_source_list_af(  );
    public function get_interviewer_af();

    public function get_candidate_profile_all_af($input_details);
    public function get_ticket_candidate_details_af($advanced_filter );

    public function get_recruitment_edit_details($input_details);
    public function reqcruitment_request_editprocess( $form_credentials );

    public function change_password_process( $input_details );
    public function add_recruiter_process( $form_credentials );
    public function get_recruiter_details( $input_details);
    public function update_recruiter_details( $input_details );

    public function recruiter_ageing_details($input_details);
    public function recruiter_ageing_details_dr($input_details);

    public function get_tblrfh_details($input_details);
    public function reqcruitment_request_editprocess_new( $form_credentials );

    public function recruiter_last_modified_date($credentials);
    public function recruiter_last_modified_date_dr($credentials);

    public function get_assigned_recruiters($rfh_no);
    public function get_interviewer_self( $rfh_no );
    public function get_cv_count($hepl_recruitment_ref_number);

    public function get_ticket_report_recruiter_afilter( $advanced_filter );
    public function get_ticket_report_recruiter(  );

    public function get_offer_released_bc( $credentials);
    public function get_candidate_onborded_history( $input_details );

    public function process_unassign( $input_details );

    public function get_recruitment_requests( $input_details );
    public function get_table_last_row($table);

    public function insert_data($data);

    public function unassigned_count_nop( $input_details );

    public function delete_unassigned_ticket( $input_details );
    public function delete_unassigned_ticket_el( $input_details );

    public function get_deleted_request($advanced_filter);

    public function get_del_ticket_candidate_details_af( $input_details );
    public function get_del_ticket_candidate_details( $input_details );

    public function update_sub_position_title( $input_details );

    public function process_hepldelete($input_details);
    public function get_cv_count_rr($input_details);
    public function get_cv_count_dr($input_details);

    public function check_recruiter_already_assigned( $check_input_details );

    public function get_position_closed_count( $credentials );

    public function get_closed_position_details( $input_details );
    public function get_closed_position_details_af( $input_details );

    public function get_open_position_details( $input_details );
    public function get_open_position_details_af( $input_details );
    public function get_current_status_rr( $input_details_cc );

    public function get_raisedby_list();
    public function process_candidate_delete( $input_details);

    public function get_candidate_details_ed( $input_details );
    public function process_candidate_edit( $input_details );

    public function get_current_status_recruiter( $input_details_cc );

    public function get_closed_salary($input_details);
    public function get_approval_for_hire( $rfh_no );
    public function update_closed_salary_bc($input_details);

    public function get_closed_by_name( $hepl_recruitment_ref_number );

    public function get_buddylist();
    public function get_buddy_details( $input_details_bi );


    public function get_approved_offers( $input_details );
    public function get_offer_accepted_for_bc_dt( $input_details );
    public function get_candidate_profile_dc_bc( $input_details );

    public function get_candidate_edu_details( $input_details );
    public function get_candidate_exp_details( $input_details );
    public function get_candidate_benefits_details( $input_details );

    public function get_user_details($input_details_ad);
    public function get_user_recruiter();
    public function get_recruiter_position($recruiter_id);
    public function get_recruiter_interviews($recruiter_id);
    public function get_position_working_rfh($data);
    public function get_total_csv($recruiter_id);
    public function get_position_cv_count( $data );
    public function get_time_data_filter( $data );
    public function get_recruiter_offers_filter( $data );
    public function get_recruiter_interviews_filter( $data );
    public function get_recruiter_position_filter( $data );
    public function get_user_recruiter_filter();
    public function get_user_recruiter_id( $data );
    public function get_time_data( $data );
    public function get_recruiter_offers($recruiter_id);
    public function get_offer_release_position($recruiter_id);
    public function get_position_working_filter($data);
    public function get_position_cv_count_filter( $data );
    public function get_total_csv_filter($data);
    public function get_recruiter_offers_filter_id($data);
    public function get_offer_release_position_filter($data);
    public function get_team_recruiter( $team );
    public function get_recruitment_dt( $data );
    public function get_avg_open( $data );
    public function get_user_recruiter_count();
    public function get_billable_status( $bill );
    public function get_max_count_recruiter($data);
    public function get_assigned_date_closed_report( $input_details );
}
