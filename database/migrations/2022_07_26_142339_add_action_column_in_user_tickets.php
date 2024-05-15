<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActionColumnInUserTickets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_tickets', function (Blueprint $table) {
            $table->enum('action', ['create_subscription', 'buy_ticket', 'use_ticket','refund_ticket'])->nullable()
                ->comment('create_subscription => moi tao subscription, buy_ticket => user mua ve, use_ticket => user su dung ve tao request, refund_ticket => refund tu admin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_tickets', function (Blueprint $table) {
            $table->enum('action', ['create_subscription', 'buy_ticket', 'use_ticket','refund_ticket'])->nullable()
                ->comment('create_subscription => moi tao subscription, buy_ticket => user mua ve, use_ticket => user su dung ve tao request, refund_ticket => refund tu admin');
        });
    }
}
