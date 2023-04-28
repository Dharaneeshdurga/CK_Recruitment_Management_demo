<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfferReleasedDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offer_released_details', function (Blueprint $table) {
            $table->id();
            $table->string('orID')->unique();
            $table->string('cdID');
            $table->string('rfh_no');
            
            $table->string('hepl_recruitment_ref_number');
            $table->string('closed_date');
            $table->string('closed_salary');
            $table->string('salary_review');
            $table->string('joining_type');
            $table->string('date_of_joining');
            $table->string('remark');
            $table->string('profile_status');
            
            $table->string('created_by');
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
        Schema::dropIfExists('offer_released_details');
    }
}
