<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCompensationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_compensations', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->index();
            $table->unsignedInteger('log_id')->index()->nullable()->comment('null => moi vua yeu cau, chua dc export tu admin');
            $table->integer('amount');
            $table->enum('status', ['approved','pending','denied'])->comment('approved => export thanh cong, pending => dang xu ly');
            $table->string('note')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('user_compensations', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
        Schema::table('user_compensations', function (Blueprint $table) {
            $table->foreign('log_id')->references('id')->on('user_compensations_logs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_compensations');
    }
}
