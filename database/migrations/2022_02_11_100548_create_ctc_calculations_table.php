<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCtcCalculationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ctc_calculations', function (Blueprint $table) {
            $table->id();
            $table->string('ctcID');
            $table->string('cdID');
            $table->string('rfh_no');
            $table->string('hepl_recruitment_ref_number');
            $table->integer('basic_pm');
            $table->integer('basic_pa');
            $table->integer('hra_pm');
            $table->integer('hra_pa');
            $table->integer('medi_al_pm');
            $table->integer('medi_al_pa');
            $table->integer('conv_pm');
            $table->integer('conv_pa');
            $table->integer('spl_al_pm');
            $table->integer('spl_al_pa');
            $table->integer('comp_a_pm');
            $table->integer('comp_a_pa');
            $table->integer('ec_pf_pm');
            $table->integer('ec_pf_pa');
            $table->integer('ec_esi_pm');
            $table->integer('ec_esi_pa');
            $table->integer('sub_totalb_pm');
            $table->integer('sub_totalb_pa');
            $table->integer('gratuity_pm');
            $table->integer('gratuity_pa');
            $table->integer('st_bonus_pm');
            $table->integer('st_bonus_pa');
            $table->integer('sub_totalc_pm');
            $table->integer('sub_totalc_pa');
            $table->integer('abc_pm');
            $table->integer('abc_pa');
            $table->integer('net_pay');
            $table->string('created_by');
            $table->string('modified_by');
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
        Schema::dropIfExists('ctc_calculations');
    }
}
