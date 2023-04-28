<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Auth::routes();

Route::get( 'index', 'HomeController@index' );

Route::get( '/', 'PageController@index' );
Route::get( 'logout', 'PageController@Logout' );
Route::get( 'error', 'PageController@error' );
Route::get( 'notfound', 'PageController@notfound' );
Route::get( 'change_password', 'PageController@change_password' );
Route::post( 'change_password_process', 'PageController@change_password_process' );
Route::post( 'get_band_details', 'PageController@get_band_details' );
Route::post( 'get_position_title_af', 'PageController@get_position_title_af' );
Route::post( 'get_sub_position_title_af', 'PageController@get_sub_position_title_af' );
Route::post( 'get_location_af', 'PageController@get_location_af' );
Route::post( 'get_business_af', 'PageController@get_business_af' );
Route::post( 'get_function_af', 'PageController@get_function_af' );
Route::post( 'get_division_af', 'PageController@get_division_af' );
Route::post( 'get_raisedby_af', 'PageController@get_raisedby_af' );
Route::post( 'get_approvedby_af', 'PageController@get_approvedby_af' );
Route::post( 'get_source_list_af', 'PageController@get_source_list_af' );
Route::post( 'get_interviewer_af', 'PageController@get_interviewer_af' );

Route::post( 'login_check_process', 'LoginController@login_check_process' );

Route::get( 'view_recruit_request_default', 'BEcoordinatorController@view_recruit_request_default' );
Route::post( 'get_recruitment_request_def_list', 'BEcoordinatorController@view_recruit_request_default' );
Route::post( 'get_recruitment_request_def_list_ag', 'BEcoordinatorController@view_recruit_request_default_ag' );
Route::get( 'add_recruit_request', 'BEcoordinatorController@add_recruit_request' );

Route::post( 'reqcruitment_request_process', 'BEcoordinatorController@reqcruitment_request_process' );
Route::post( 'process_ticket_edit', 'BEcoordinatorController@process_ticket_edit' );
Route::post( 'process_ticket_delete', 'BEcoordinatorController@process_ticket_delete' );

Route::get( 'edit_recruit_request', 'BEcoordinatorController@edit_recruit_request' );
Route::post( 'get_recruitment_edit_details', 'BEcoordinatorController@get_recruitment_edit_details' );
Route::post( 'reqcruitment_request_editprocess', 'BEcoordinatorController@reqcruitment_request_editprocess' );

Route::get( 'edit_recruit_request_new', 'BEcoordinatorController@edit_recruit_request_new' );
Route::post( 'get_recruitment_edit_details_new', 'BEcoordinatorController@get_recruitment_edit_details_new' );
Route::post( 'reqcruitment_request_editprocess_new', 'BEcoordinatorController@reqcruitment_request_editprocess_new' );


Route::get( 'view_recruit_request_default', 'BEcoordinatorController@view_recruit_request_default' );
Route::post( 'get_recruitment_request_def_list', 'BEcoordinatorController@view_recruit_request_default' );

Route::get( 'view_recruit_request', 'BEcoordinatorController@view_recruit_request' );
Route::post( 'get_recruitment_request_list', 'BEcoordinatorController@view_recruit_request' );
Route::post( 'process_recruitment_assign', 'BEcoordinatorController@process_recruitment_assign' );
Route::post( 'process_recruitment_assigned_assign', 'BEcoordinatorController@process_recruitment_assigned_assign' );

Route::post( 'get_last_hepl_reference_no', 'BEcoordinatorController@get_last_hepl_reference_no' );
Route::post( 'getlast_rfhno', 'BEcoordinatorController@getlast_rfhno' );

Route::get( 'candidate_profile', 'BEcoordinatorController@get_candidate_profile' );
Route::post( 'get_candidate_profile_report', 'BEcoordinatorController@get_candidate_profile' );
Route::post( 'candidate_follow_up_history_bc', 'BEcoordinatorController@candidate_follow_up_history_bc' );
Route::post( 'get_offer_released_report_bc', 'BEcoordinatorController@get_offer_released_report_bc' );

Route::get( 'ticket_report', 'BEcoordinatorController@ticket_report' );
Route::post( 'get_ticket_report', 'BEcoordinatorController@ticket_report' );

Route::post( 'get_recruiter_list_af', 'BEcoordinatorController@get_recruiter_list_af' );
Route::post( 'get_recruiter_team_list_af', 'BEcoordinatorController@get_recruiter_team_list_af' );

Route::get( 'ticket_candidate_details', 'BEcoordinatorController@ticket_candidate_details' );
Route::post( 'get_ticket_candidate_details', 'BEcoordinatorController@ticket_candidate_details' );

Route::get( 'view_recruiter', 'BEcoordinatorController@view_recruiter' );
Route::post( 'get_recruiter_list', 'BEcoordinatorController@view_recruiter' );
Route::post( 'process_recruiter_delete', 'BEcoordinatorController@process_recruiter_delete' );

Route::get( 'add_recruiter', 'BEcoordinatorController@add_recruiter' );
Route::post( 'add_recruiter_process', 'BEcoordinatorController@add_recruiter_process' );

Route::post( 'reset_password', 'BEcoordinatorController@reset_password' );
Route::post( 'get_recruiter_details', 'BEcoordinatorController@get_recruiter_details' );
Route::post( 'update_recruiter_details', 'BEcoordinatorController@update_recruiter_details' );

Route::get( 'recruiter_report', 'BEcoordinatorController@recruiter_report' );
Route::post( 'get_recruiter_report', 'BEcoordinatorController@recruiter_report' );

Route::get( 'recruiter_report_cp', 'BEcoordinatorController@recruiter_report_cp' );
Route::post( 'get_recruiter_cp', 'BEcoordinatorController@recruiter_report_cp' );

Route::post( 'get_offer_released_bc', 'BEcoordinatorController@get_offer_released_bc' );
Route::post( 'get_candidate_onborded_history_bc', 'BEcoordinatorController@get_candidate_onborded_history_bc' );

Route::post( 'process_unassign', 'BEcoordinatorController@process_unassign' );
Route::post( 'update_no_of_position', 'BEcoordinatorController@update_no_of_position' );

Route::get( 'deleted_request', 'BEcoordinatorController@deleted_request' );
Route::post( 'get_deleted_request_list', 'BEcoordinatorController@deleted_request' );
Route::get( 'deleted_cp', 'BEcoordinatorController@get_del_candidate_profile' );

Route::post( 'get_del_candidate_profile', 'BEcoordinatorController@get_del_candidate_profile' );
Route::post( 'update_sub_position_title', 'BEcoordinatorController@update_sub_position_title' );

Route::post( 'process_hepldelete', 'BEcoordinatorController@process_hepldelete' );
//new module
Route::get( 'daily_report', 'BEcoordinatorController@daily_report' );
Route::get( 'score_card', 'BEcoordinatorController@score_card' );
Route::get( 'recruiter_daily_report', 'BEcoordinatorController@recruiter_daily_report' );
Route::post( 'get_score_card', 'BEcoordinatorController@get_score_card' );
Route::post( 'get_score_card_filter', 'BEcoordinatorController@get_score_card_filter' );
Route::post( 'get_recruiter_daily_report', 'BEcoordinatorController@get_recruiter_daily_report' );
Route::post( 'get_recruiter_daily_report_filter', 'BEcoordinatorController@get_recruiter_daily_report_filter' );
Route::post( 'get_recruiter_list_team', 'BEcoordinatorController@get_recruiter_list_team' );
Route::post( 'get_sc_count', 'BEcoordinatorController@get_sc_count' );
Route::post( 'get_closure_details', 'BEcoordinatorController@get_closure_details' );
Route::post( 'get_average', 'BEcoordinatorController@get_average' );

Route::get( 'edit_ctc_qc', 'BEcoordinatorController@edit_ctc_oat');


Route::post( 'get_closed_position', 'BEcoordinatorController@daily_report' );
Route::post( 'get_open_position', 'BEcoordinatorController@get_open_daily_report' );

Route::post( 'get_raisedby_list', 'BEcoordinatorController@get_raisedby_list' );

Route::post('process_candidate_delete', 'BEcoordinatorController@process_candidate_delete');

Route::post('get_candidate_details_ed', 'BEcoordinatorController@get_candidate_details_ed');
Route::post('process_candidate_edit', 'BEcoordinatorController@process_candidate_edit');
Route::post('update_closed_salary_bc', 'BEcoordinatorController@update_closed_salary_bc');

Route::get( 'offers_bc', 'BEcoordinatorController@offers_bc' );
Route::post( 'get_offer_list_bc_apo', 'BEcoordinatorController@get_offer_list_bc_apo' );
Route::post( 'get_offer_accepted_for_bc', 'BEcoordinatorController@get_offer_accepted_for_bc' );
Route::post( 'get_offer_rejected_for_bc', 'BEcoordinatorController@get_offer_rejected_for_bc' );
Route::post( 'get_nonpo_pending_list', 'BEcoordinatorController@get_nonpo_pending_list' );
Route::post('send_to_leader_ol_bc', 'BEcoordinatorController@send_to_leader_ol');
Route::post('get_approved_nonpo', 'BEcoordinatorController@get_approved_nonpo');

Route::get( 'document_collection_bc', 'BEcoordinatorController@document_collection_bc' );
Route::post('get_candidate_docinfo_bc', 'BEcoordinatorController@document_collection_bc');
Route::get( 'cd_preview_bc', 'BEcoordinatorController@cd_preview_bc' );
Route::post( 'get_oat_ageing_bc', 'BEcoordinatorController@get_oat_ageing_dt' );
Route::post('payroll_revert_update_bc', 'BEcoordinatorController@payroll_revert_update');


Route::post('get_candidate_preview_details_bc', 'BEcoordinatorController@get_candidate_preview_details_bc');

// External Candidate Database

Route::get( 'external_candidate_database', 'ExternalCandidate@get_external_candidate_database' );
Route::post( 'get_external_candidate_database', 'ExternalCandidate@get_external_candidate_database' );
Route::post( 'get_position_apply_title_af', 'ExternalCandidate@get_position_apply_title_af' );
Route::post('/import_excel/import', 'ExternalCandidate@import');
Route::post( 'get_save_CV_form', 'ExternalCandidate@cv_upload_save' );
Route::get( 'external_candidate_database', 'ExternalCandidate@get_external_candidate_database' );
Route::post( 'get_external_candidate_database', 'ExternalCandidate@get_external_candidate_database' );
Route::post( 'get_position_apply_title_af', 'ExternalCandidate@get_position_apply_title_af' );
Route::post('/import_excel/import', 'ExternalCandidate@import');

Route::get( 'view_task_detail', 'RecruiterController@view_task_detail' );
Route::post( 'get_assigned_recruitment_request_list', 'RecruiterController@view_task_detail' );
Route::post( 'upload_cvprocess', 'RecruiterController@upload_cvprocess' );
Route::post( 'show_uploaded_cv', 'RecruiterController@show_uploaded_cv' );
Route::post( 'process_default_status', 'RecruiterController@process_default_status' );
Route::post( 'process_offer_release_details', 'RecruiterController@process_offer_release_details' );
Route::post( 'get_offer_released_tb', 'RecruiterController@get_offer_released_tb' );
Route::post( 'offer_released_edit_process', 'RecruiterController@offer_released_edit_process' );
Route::post( 'or_ldj_history', 'RecruiterController@or_ldj_history' );
Route::post( 'or_ldj_onboard_status', 'RecruiterController@or_ldj_onboard_status' );
Route::post( 'candidate_follow_up_history', 'RecruiterController@candidate_follow_up_history' );
Route::post( 'get_offer_released_report', 'RecruiterController@get_offer_released_report' );

Route::get( 'view_recruit_request_new', 'RecruiterController@view_recruit_request_new' );
Route::post( 'get_recruitment_view_details_new', 'RecruiterController@get_recruitment_view_details_new' );
Route::post( 'get_candidate_onborded_history', 'RecruiterController@get_candidate_onborded_history' );

Route::post( 'get_assigned_recruitment_request_oldlist', 'RecruiterController@get_assigned_recruitment_request_oldlist' );
Route::post( 'closedate_update', 'RecruiterController@closedate_update' );

Route::get( 'list_candidate_profile', 'RecruiterController@list_candidate_profile' );
Route::post( 'get_candidate_profile_list_report', 'RecruiterController@list_candidate_profile' );

Route::post( 'get_recruitment_inactive', 'RecruiterController@get_recruitment_inactive' );

Route::get( 'ticket_report_recruiter', 'RecruiterController@ticket_report_recruiter' );
Route::post( 'get_ticket_report_recruiter', 'RecruiterController@ticket_report_recruiter' );

Route::get( 'ticket_cd_recruiter', 'RecruiterController@ticket_candidate_details' );
Route::post( 'get_ticket_cd_recruiter', 'RecruiterController@ticket_candidate_details' );
Route::post('process_candidate_delete_rl', 'RecruiterController@process_candidate_delete_rl');

Route::post('get_candidate_details_ed_rl', 'RecruiterController@get_candidate_details_ed');
Route::post('process_candidate_edit_rl', 'RecruiterController@process_candidate_edit');

Route::post('dateofjoining_update', 'RecruiterController@dateofjoining_update');

Route::get( 'document_collection', 'RecruiterController@document_collection' );
Route::post('get_candidate_docinfo', 'RecruiterController@document_collection');
Route::post('get_buddylist', 'RecruiterController@get_buddylist');
Route::post('get_department_list', 'RecruiterController@get_department_list');

Route::get( 'cd_preview', 'RecruiterController@cd_preview' );
Route::post('get_candidate_preview_details', 'RecruiterController@get_candidate_preview_details');
Route::post('update_cdoc_status', 'RecruiterController@update_cdoc_status');
Route::post('send_to_payroll', 'RecruiterController@send_to_payroll');
Route::get( 'offers_recruiter', 'RecruiterController@offers_recruiter' );
Route::post( 'get_offer_list_rt_apo', 'RecruiterController@get_offer_list_rt_apo' );
Route::post( 'send_to_candidate_ol', 'RecruiterController@send_to_candidate_ol' );
Route::post( 'get_offer_accepted_for_rr', 'RecruiterController@get_offer_accepted_for_rr' );
Route::post( 'get_offer_rejected_for_rr', 'RecruiterController@get_offer_rejected_for_rr' );
Route::post( 'get_oat_ageing_re', 'RecruiterController@get_oat_ageing_dt' );
Route::post( 'upload_rc_file_attach', 'RecruiterController@upload_rc_file_attach' );
Route::post( 'get_department_name', 'RecruiterController@get_department_name' );


Route::post( 'update_can_doj', 'RecruiterController@update_can_doj' );

Route::get( 'prohire_card', 'PhcardController@prohire_card' );
Route::post( 'get_cv_count_details', 'PhcardController@get_cv_count_details' );
Route::post( 'get_cpcv_count_details', 'PhcardController@get_cpcv_count_details' );
Route::post('get_stagesof_recruitment', 'PhcardController@get_stagesof_recruitment');
Route::post('get_stagesof_recruitment_rr', 'PhcardController@get_stagesof_recruitment_rr');
Route::get('send_mail_test', 'PhcardController@send_mail_test');


Route::get('allocation_list/{rfh_no}', 'ImportController@export');
Route::get('scorecard_export', 'ImportController@scorecard_export');

Route::post('process_offer_letter_release', 'ImportController@process_offer_letter_release');
Route::post('submit_esi_form', 'ImportController@submit_esi_form');

Route::get('candidate_dc/{candidate_id}', 'DocumentCollection@candidate_dc');
Route::post('get_candidate_details_exist', 'DocumentCollection@get_candidate_details_exist');
Route::post('candidate_basic_doc_upload', 'DocumentCollection@candidate_basic_doc_upload');
Route::post('candidate_edu_document', 'DocumentCollection@candidate_edu_document');
Route::post('remove_education_fields_exist', 'DocumentCollection@remove_education_fields_exist');
Route::post('candidate_exp_document', 'DocumentCollection@candidate_exp_document');
Route::post('remove_experience_fields_exist', 'DocumentCollection@remove_experience_fields_exist');
Route::post('candidate_benefit_document', 'DocumentCollection@candidate_benefit_document');
Route::post('remove_compensation_fields_exist', 'DocumentCollection@remove_compensation_fields_exist');
Route::post('candidate_proof_document', 'DocumentCollection@candidate_proof_document');
Route::post('candidate_document_upload', 'DocumentCollection@candidate_document_upload');
Route::post('offer_response_candidate', 'DocumentCollection@offer_response_candidate');
Route::post('update_reject_status', 'DocumentCollection@offer_reject_update');

Route::get( 'ol_payroll_verify', 'PayrollController@ol_payroll_verify');
Route::post('get_cor_oat_po', 'PayrollController@ol_payroll_verify');
Route::post('send_to_leader_ol', 'PayrollController@send_to_leader_ol');
Route::post('get_cor_oat_ao', 'PayrollController@get_cor_oat_ao');
Route::get( 'edit_ctc_oat', 'PayrollController@edit_ctc_oat');
Route::post('get_ctc_edit_oat', 'PayrollController@get_ctc_edit_oat');
Route::post('update_ctc_edit', 'PayrollController@update_ctc_edit');
Route::post('payroll_revert_update', 'PayrollController@payroll_revert_update');
Route::post('get_candidate_for_budgie', 'PayrollController@get_candidate_for_budgie');


Route::post( 'candidate_follow_up_history_oat', 'PayrollController@candidate_follow_up_history_oat' );
Route::post( 'get_offer_released_report_oat', 'PayrollController@get_offer_released_report_oat' );
Route::post( 'send_po_finance', 'PayrollController@send_po_finance' );
Route::post( 'send_po_buisness_head', 'PayrollController@send_po_buisness_head' );
Route::get( 'add_po_details', 'PayrollController@add_po_details' );
Route::post( 'submit_po_process', 'PayrollController@submit_po_process' );
Route::post( 'get_po_components', 'PayrollController@get_po_components' );
Route::post( 'upload_clientpo', 'PayrollController@upload_clientpo' );
Route::post( 'get_oat_ageing_dt', 'PayrollController@get_oat_ageing_dt' );

Route::get( 'ol_leader_verify', 'LeaderController@ol_leader_verify');
Route::post('get_offer_list_ld', 'LeaderController@ol_leader_verify');
Route::post('process_ld_approval', 'LeaderController@process_ld_approval');
Route::post('get_cor_ld_ao', 'LeaderController@get_cor_ld_ao');
Route::post('get_po_details_ld', 'LeaderController@get_po_details_ld');
Route::get ( 'edit_po_details_bh', 'LeaderController@edit_po_details_bh' );
Route::post( 'get_po_components_bh', 'LeaderController@get_po_components_bh' );
Route::post( 'submit_po_process_bh', 'LeaderController@submit_po_process_bh' );
Route::post( 'submit_oat_process_bh', 'LeaderController@submit_oat_process_bh' );
Route::post( 'send_po_finance_l', 'LeaderController@send_po_finance_l' );
Route::post( 'send_oat_mail', 'LeaderController@send_oat_mail' );
Route::post( 'get_dandidate_dt', 'LeaderController@get_candidate_details' );
Route::post( 'get_oat_ageing_bh', 'LeaderController@get_oat_ageing_dt' );
Route::get( 'cd_preview_bh', 'LeaderController@cd_preview_bh' );
Route::post('get_candidate_preview_details_bh', 'LeaderController@get_candidate_preview_details');
Route::post('get_candidate_for_budgie_ld', 'LeaderController@get_candidate_for_budgie');

Route::get( 'offers_finance', 'FinanceController@offers_finance' );
Route::post( 'get_pending_po_request', 'FinanceController@offers_finance' );
Route::post( 'get_po_details_fn', 'FinanceController@get_po_details_fn' );
Route::post( 'process_fn_postatus', 'FinanceController@process_fn_postatus' );
Route::post( 'get_approved_po_fin', 'FinanceController@get_approved_po_fin' );
Route::post( 'get_oat_ageing_fc', 'FinanceController@get_oat_ageing_dt' );

Route::post('upload_status', 'BudgieController@upload_status');

Route::get('test', 'DocumentCollection@test');

Route::post('client_type_update', 'PayrollController@client_type_update');
Route::post('send_offer_release', 'PayrollController@send_to_candidate_offerRelease');
Route::post('upload_mail_attach_po', 'PayrollController@upload_mail_attach_po');
Route::post('save_as_draft_mail', 'PayrollController@save_as_draft_mail');
Route::post('get_mail_details', 'PayrollController@get_mail_details');



Route::get('offers_poteam', 'PoteamController@offers_poteam');
Route::post( 'get_internal_po_request', 'PoteamController@offers_poteam' );
Route::post( 'upload_clientpo_po', 'PoteamController@upload_clientpo' );
Route::post( 'get_approved_po_po', 'PoteamController@get_approved_po_fin' );
Route::post('get_ageing_report', 'BEcoordinatorController@get_ageing_report');

