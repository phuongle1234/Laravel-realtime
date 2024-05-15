<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class CreateVimeosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vimeos', function (Blueprint $table) {
            $table->id();
            $table->text('client_id')->nullable()->comment('vimeo client identifier');
            $table->text('client_secrets')->nullable()->comment('vimeo client secrets');
            $table->text('personal_access_token')->nullable()->comment('vimeo client personal access token');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });

        // call seeder
        Artisan::call('db:seed',[
            '--class' => 'VimeoSeeder'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vimeos');
    }
}
