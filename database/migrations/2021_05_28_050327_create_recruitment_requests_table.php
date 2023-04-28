<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecruitmentRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recruitment_requests', function (Blueprint $table) {
            $table->id();
            $table->string('recReqID')->unique();
            $table->string('rfh_no');
            $table->string('position_title');
            $table->string('sub_position_title');
            $table->string('no_of_position');
            $table->string('band');
            $table->date('open_date');
            $table->string('critical_position');
            $table->string('business');
            $table->string('division');
            $table->string('function');
            $table->string('location');
            $table->string('billing_status');
            $table->string('interviewer');
            $table->string('salary_range');
            $table->string('request_status');
            $table->date('close_date')->nullable();
            $table->string('closed_by');
            $table->string('assigned_status');
            $table->string('assigned_to')->nullable();
            $table->string('assigned_date')->nullable();
            
            $table->string('hepl_recruitment_ref_number')->nullable();
            $table->string('action_for_the_day_status')->nullable();
            $table->string('created_by');
            $table->string('modified_by');
            $table->integer('delete_status');
            
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
        Schema::dropIfExists('recruitment_requests');
    }
}
