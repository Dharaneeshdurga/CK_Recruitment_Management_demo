<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandidateExperienceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidate_experience_details', function (Blueprint $table) {
            $table->id();
            $table->string('cdID');
            $table->string('rfh_no');
            $table->string('hepl_recruitment_ref_number');
            $table->string('job_title');
            $table->string('company_name');
            $table->string('start_month');
            $table->string('start_year');
            $table->string('end_month');
            $table->string('end_year');
            $table->string('certificate');
            $table->date('created_on');

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
        Schema::dropIfExists('candidate_experience_details');
    }
}
