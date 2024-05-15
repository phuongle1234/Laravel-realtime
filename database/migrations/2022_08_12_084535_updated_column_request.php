<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatedColumnRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('requests', function (Blueprint $table) {

            $table->dropColumn('is_late_deadline');

            $table->tinyInteger('is_displayed')->default(1)->comment('0: non display, 1: display')->after('path');

            // $table->enum('status', ['pending', 'approved', 'denied', 'delay', 'accept', 'pass', 'complete'] )
            // ->comment("
            //     pending: when students send request \n
            //     accept: when teacher accept request \n
            //     denied: when admin reject \n
            //     delay: when teacher  late deadline \n
            //     approved: when teacher upload video \n
            //     pass: when admin approved video \n
            //     complete: when student accept complete request \n
            // ")->change();

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

            $table->renameColumn('is_late_deadline','display')->default(0)->comment('0: non display, 1: display');

            // $table->enum('status', ['pending', 'approved', 'denied', 'delay', 'accept', 'pass', 'complete'] )
            // ->comment("
            //     pending: when students send request \n
            //     accept: when teacher accept request \n
            //     denied: when admin reject \n
            //     delay: when teacher  late deadline \n
            //     approved: when teacher upload video \n
            //     pass: when admin approved video \n
            //     complete: when student accept complete request \n
            // ")->change();

        });
    }
}
