<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddStatusDelayIntoRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `requests` CHANGE `status` `status` ENUM('pending','approved','denied','accept','pass','complete','delayed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT ' pending: when students send request \r\n\r\n accept: when teacher accept request \r\n\r\n denied: when admin reject \r\n\r\n approved: when teacher upload video \r\n\r\n pass: when admin approved video \r\n\r\n complete: when student accept complete request\r\n\r\n delayed: when teacher uploaded deadline has \r\n expired\r\n\r\n\r\n \r\n\r\n ';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
