<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailMasterDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_master_details', function (Blueprint $table) {
            $table->id();
            $table->string('mID');
            $table->string('step');
            $table->string('mail_subject');
            $table->string('mail_body_content');
            $table->string('mail_footer');
            $table->string('remark');
            $table->string('business_type');
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
        Schema::dropIfExists('mail_master_details');
    }
}
