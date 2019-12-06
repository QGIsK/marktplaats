<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdsBids extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads_bids', function (Blueprint $table) {
             $table->bigIncrements('id');
             $table->unsignedBigInteger('user_id');
             $table->foreign('user_id')->references('id')->on('users');
             $table->unsignedBigInteger('ad_id');
             $table->foreign('ad_id')->references('id')->on('ads');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ads_bids'); 
   }
}
