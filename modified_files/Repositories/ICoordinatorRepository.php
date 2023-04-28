<?php

namespace App\Repositories;

interface ICoordinatorRepository {
    
    public function reqcruitment_requestEntry( $form_credentials );
    public function get_reqcruitment_request_default($where_cond);
    public function get_reqcruitment_request_default_report();
    public function get_reqcruitment_request_afilter( $advanced_filter );
    public function get_reqcruitment_request($rfh_no );
    public function get_recruiter_list( );
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

    public function get_candidate_profile_all_af($input_details);
    public function get_ticket_candidate_details_af($advanced_filter );

    public function get_recruitment_edit_details($input_details);
    public function reqcruitment_request_editprocess( $form_credentials );

    public function change_password_process( $input_details );
    public function add_recruiter_process( $form_credentials );

    public function recruiter_ageing_details($input_details);
    public function get_tblrfh_details($input_details);
    public function reqcruitment_request_editprocess_new( $form_credentials );

    public function recruiter_last_modified_date($credentials);
    
    public function get_assigned_recruiters($rfh_no);
    public function get_interviewer_self( $rfh_no );
    public function get_cv_count($hepl_recruitment_ref_number);

    public function get_ticket_report_recruiter_afilter( $advanced_filter );
    public function get_ticket_report_recruiter(  );
}