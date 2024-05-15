<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->index();
            $table->integer('amount');
            $table->integer('current_reward')->comment('tong so tien thoi diem duoc cong/tru');
            $table->enum('status', ['active','pending','denied']);
            $table->string('note')->nullable();
            $table->enum('action', ['bonus', 'minus','complete_request','reward_request'])->nullable()
                ->comment('minus => tru tien, bonus => cong tien, complete_request => cong tien hoan thanh request, reward_request => tru tien khi rut tien');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('user_transfers', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_transfers');
    }
}
