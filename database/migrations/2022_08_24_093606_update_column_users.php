<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateColumnUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {

            $table->boolean('approved_admin')->default(0)->nullable()->comment("
                * using for teacher \n
                0: wait admin approved \n
                1: approved \n
                2: Request for resubmission \n
            ")->after('access_token');

            $table->tinyInteger('percent_training')->default(0)->after('approved_admin');

            $table->tinyInteger('unlock')->default(0)->nullable()->after('percent_training');

            $table->longText('introduction')->nullable()->after('unlock');

            $table->string('card_id')->nullable()->after('introduction');

            $table->string('token')->nullable()->after('card_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {

            $table->boolean('approved_admin')->default(0)->nullable()->comment("
                * using for teacher \n
                0: wait admin approved \n
                1: approved \n
                2: Request for resubmission \n
            ")->after('access_token');

            $table->tinyInteger('percent_training')->default(0)->after('approved_admin');

            $table->tinyInteger('unlock')->default(0)->nullable()->after('percent_training');

            $table->longText('introduction')->nullable()->after('unlock');

            $table->string('card_id')->nullable()->after('introduction');

            $table->string('token')->nullable()->after('card_id');
        });
    }
}
