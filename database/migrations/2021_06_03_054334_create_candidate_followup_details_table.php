<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandidateFollowupDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidate_followup_details', function (Blueprint $table) {
            $table->id();
            $table->string('cfdID')->unique();
            $table->string('cdID');
            $table->string('rfh_no');
            $table->string('follow_up_status');
            $table->date('created_on');
            $table->string('created_by');
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
        Schema::dropIfExists('candidate_followup_details');
    }
}
