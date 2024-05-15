<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code',10);
            $table->text('name');
            $table->text('last_name')->nullable();
            $table->text('first_name')->nullable();
            $table->text('kana')->nullable();
            $table->string('email',50)->unique();
            $table->enum('role', ['admin', 'teacher', 'student']);
            $table->text('avatar')->nullable();
            $table->date('birthday')->nullable();
            $table->enum('sex', ['male', 'female'])->default('male');
            $table->string('tel', 30)->nullable();
            $table->text('zip_code')->nullable();
            $table->text('address')->nullable();
            $table->enum('status', ['active', 'pending', 'denied', 'deleted']);
            $table->integer('vimeo_folder_id')->nullable();
            $table->datetime('seen_at')->nullable();
            $table->text('password');
            $table->text('password_crypt')->nullable()->comment('using show admin');
            $table->text('access_token')->nullable();
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });

        // foreign key

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
