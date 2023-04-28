<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRaisedbyListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raisedby_lists', function (Blueprint $table) {
            $table->id();
            $table->string('rbID')->unique();
            $table->string('raised_by');
            $table->integer('status');
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
        Schema::dropIfExists('raisedby_lists');
    }
}
