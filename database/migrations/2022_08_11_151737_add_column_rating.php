<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnRating extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_ratings', function (Blueprint $table) {
            $table->unsignedBigInteger('request_id')->after('reviewer_id');
        });

        Schema::table('user_watches', function (Blueprint $table){
            $table->foreign('request_id')->references('id')->on('requests');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_ratings', function (Blueprint $table) {
            $table->unsignedBigInteger('request_id')->after('reviewer_id');
        });

        Schema::table('user_watches', function (Blueprint $table){
            $table->foreign('request_id')->references('id')->on('requests');
        });
    }
}
