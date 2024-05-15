<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIntoRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->tinyInteger('tag_id')->nullable()->after('status');
            $table->tinyInteger('field_id')->nullable()->after('tag_id');
            $table->text('video_title')->nullable()->after('field_id');
            $table->text('description')->nullable()->after('title');
            //path
            $table->text('path')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->tinyInteger('tag_id')->nullable()->after('status');
            $table->tinyInteger('field_id')->nullable()->after('tag_id');
            $table->text('video_title')->nullable()->after('field_id');
            $table->text('description')->nullable()->after('title');
            $table->text('path')->nullable()->after('description');
        });
    }
}
