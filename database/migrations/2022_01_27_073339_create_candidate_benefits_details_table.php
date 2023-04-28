<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandidateBenefitsDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidate_benefits_details', function (Blueprint $table) {
            $table->id();
            $table->string('cdID');
            $table->string('rfh_no');
            $table->string('hepl_recruitment_ref_number');
            $table->string('doc_type');
            $table->string('doc_filename');
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
        Schema::dropIfExists('candidate_benefits_details');
    }
}
