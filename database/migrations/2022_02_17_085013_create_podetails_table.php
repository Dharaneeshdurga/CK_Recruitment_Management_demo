<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePodetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('podetails', function (Blueprint $table) {
            $table->id();
            $table->string('poID');
            $table->string('cdID');
            $table->string('rfh_no');
            $table->string('hepl_recruitment_ref_number');
            $table->string('po_detail');
            $table->string('po_description');
            $table->string('po_amount');
            $table->string('remark');
            $table->string('po_remark');
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
        Schema::dropIfExists('podetails');
    }
}
