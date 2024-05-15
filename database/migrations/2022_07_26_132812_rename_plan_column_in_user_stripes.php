<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenamePlanColumnInUserStripes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_stripes', function (Blueprint $table) {
            $table->renameColumn('plan','plan_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_stripes', function (Blueprint $table) {
            $table->renameColumn('plan','plan_id');
        });
    }
}
