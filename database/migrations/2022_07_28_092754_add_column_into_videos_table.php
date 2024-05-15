<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIntoVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->text('thumbnail')->nullable()->after('active');
            $table->text('player_embed_url')->nullable()->after('thumbnail');
            $table->text('transcode')->nullable()->after('player_embed_url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->text('thumbnail')->nullable()->after('active');
            $table->text('player_embed_url')->nullable()->after('thumbnail');
            $table->text('transcode')->nullable()->after('player_embed_url');
        });
    }
}
