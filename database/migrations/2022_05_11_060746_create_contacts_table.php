<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('user_contact_id');
            $table->unsignedBigInteger('request_id')->nullable();
            $table->unsignedBigInteger('message_id');
            $table->enum('status', ['active', 'pending', 'denied']);
            $table->datetime('seen_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('contacts', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('user_contact_id')->references('id')->on('users');
            $table->foreign('request_id')->references('id')->on('requests');
            $table->foreign('message_id')->references('id')->on('messages');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contacts');
    }
}
