<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->nullable();
            $table->text('bank_code')->nullable();
            $table->text('branch_code')->nullable();
            $table->text('bank_account_number')->nullable();
            $table->enum('bank_account_type',[1,2])->nullable();//1:普通 ,2:当座
            $table->text('bank_account_name')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::table('bank_accounts', function (Blueprint $table) {
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
        Schema::dropIfExists('bank_accounts');
    }
}
