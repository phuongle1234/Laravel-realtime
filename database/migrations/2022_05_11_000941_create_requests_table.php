<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('user_receive_id')->nullable();
            $table->string('file_path')->nullable();
            $table->string('title');
            $table->text('content');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('video_id')->nullable();
            $table->unsignedBigInteger('video_user_id')->nullable();
            $table->boolean('private')->default( false );
            $table->enum('status', ['pending', 'approved', 'denied', 'accept', 'pass', 'complete'] )
            ->comment("
                pending: when students send request \n
                accept: when teacher accept request \n
                denied: when student block \n
                approved: when teacher upload video \n
                pass: when admin approved video \n
                complete: when student accept complete request \n
            ");
            $table->boolean('is_direct')->default( false );
            $table->datetime('deadline')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('requests', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('user_receive_id')->references('id')->on('users');
            $table->foreign('subject_id')->references('id')->on('subjects');
            $table->foreign('video_id')->references('id')->on('videos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requests');
    }
}
