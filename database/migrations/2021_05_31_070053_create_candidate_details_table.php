<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandidateDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidate_details', function (Blueprint $table) {
            $table->id();
            $table->string('cdID')->unique();
            $table->string('rfh_no');
            $table->string('status');
            $table->string('hepl_recruitment_ref_number');
            $table->string('candidate_name');
            $table->string('candidate_cv');
            $table->string('red_flag_status');
            $table->string('created_on');
            $table->string('created_by');
            $table->string('candidate_source');
            $table->string('gender');
            $table->string('candidate_status');
            $table->string('candidate_email');
            $table->string('candidate_type');
            $table->string('candidate_mobile');

            $table->string('proof_of_identity');
            $table->string('poi_filename');
            $table->string('proof_of_address');
            $table->string('poa_filename');
            $table->string('tax_entity_proof');
            $table->string('proof_of_relieving');
            $table->string('proof_of_vaccination');
            $table->string('proof_of_dob');
            $table->string('proof_of_bg');
            $table->string('proof_of_bankacc');
            
            $table->string('doc_status');
            $table->string('offer_rel_status');
            $table->string('c_doc_status');
            $table->string('payroll_status');
            $table->string('payroll_remark');
            $table->string('po_letter_filename');
            $table->string('leader_status');
            $table->string('po_finance_status');
            $table->string('fn_po_remark');
            $table->string('fn_po_attach');
            $table->string('payroll_verify_type');
            $table->string('welcome_buddy');
            $table->string('offer_letter_filename');
            $table->string('or_cc_mailid');
            $table->string('or_bc_mailid');
            $table->string('or_doj');
            $table->string('closed_salary_pa');
            $table->string('last_drawn_ctc');
            $table->string('register_type');
            
            $table->string('get_emp_mode');
            $table->string('or_department');
            $table->string('po_type');
            $table->string('po_file_status');
            $table->string('client_po_attach');
            $table->string('client_po_number');
            $table->string('or_recruiter_name');
            $table->string('or_recruiter_email');
            $table->string('or_recruiter_mobile_no');
            $table->string('approver');
            $table->string('c_doc_upload_status');
            $table->string('proof_of_attach');
            $table->string('client_po_update_date');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('candidate_details');
    }
}
