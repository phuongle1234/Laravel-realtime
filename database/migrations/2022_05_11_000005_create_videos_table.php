<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('owner_id')->comment('id of teacher');
            $table->unsignedInteger('vimeo_id');
            $table->text('user_vimeo_id');
            $table->text('title');
            $table->string('description');
            $table->string('path');
            $table->boolean('private')->nullable();
            $table->boolean('active')->nullable()->default( 1 );
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('videos', function (Blueprint $table) {
            $table->foreign('owner_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('videos');
    }
}
