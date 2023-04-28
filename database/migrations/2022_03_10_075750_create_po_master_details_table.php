<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoMasterDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('po_master_details', function (Blueprint $table) {
            $table->id();
            $table->string('pmID');
            $table->string('medical_insurance');
            $table->string('accident_coverage');
            $table->string('term_insurance');
            $table->string('staff_welfare');
            $table->string('hr_software_modules');
            $table->string('internet_charges_wfh');
            $table->string('pf_admin_charge_percent');
            $table->string('hepl_bs_charge_percent');
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
        Schema::dropIfExists('po_master_details');
    }
}
