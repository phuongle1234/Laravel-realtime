<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnNotifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->unsignedBigInteger('notification_deliver_id')->after('notifiable_id')->nullable();
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->foreign('notification_deliver_id')->references('id')->on('notification_deliveries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->unsignedBigInteger('notification_deliver_id')->after('notifiable_id')->nullable();
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->foreign('notification_deliver_id')->references('id')->on('notification_deliveries');
        });
    }
}
