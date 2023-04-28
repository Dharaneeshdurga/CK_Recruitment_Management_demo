<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfferReleasedLaterDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offer_released_later_dates', function (Blueprint $table) {
            $table->id();
            $table->string('orldID')->unique();
            $table->string('cdID');
            $table->string('rfh_no');
            $table->string('hepl_recruitment_ref_number');
            $table->string('orladj_resignation_received');
            $table->string('orladj_touchbase');
            $table->string('initiate_backfil');
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
        Schema::dropIfExists('offer_released_later_dates');
    }
}
