<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->unsignedInteger('user_id');
            $table->dateTime('featuredAt');
            $table->string('postalCode');
            $table->string("title");
            $table->longText("description");
            $table
                ->longText("image")
                ->default(
                    "https://images.unsplash.com/photo-1574322288467-3248703be63f?ixlib=rb-1.2.1&auto=format&fit=crop&w=1351&q=80"
                );
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ads');
    }
}
