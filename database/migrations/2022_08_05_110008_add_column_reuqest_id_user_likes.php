<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnReuqestIdUserLikes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_likes', function (Blueprint $table) {
            $table->unsignedInteger('request_id')->nullable()->after('video_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_likes', function (Blueprint $table) {
            $table->unsignedInteger('request_id')->nullable()->after('video_id');
        });
    }
}
