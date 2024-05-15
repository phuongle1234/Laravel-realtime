<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCompensationsLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_compensations_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('total_amount_exported')->comment('tong toan bo so tien khi export');
            $table->text('file_path_name')->nullable();
            $table->enum('status', ['approved','pending','denied'])->comment('approved => export thanh cong, pending => dang xu ly');
            $table->string('note')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_compensations_logs');
    }
}
