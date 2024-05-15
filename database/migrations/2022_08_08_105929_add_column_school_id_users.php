<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnSchoolIdUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('university_Code')->nullable()->comment('Using for teacher')->after('seen_at');
            $table->unsignedInteger('faculty_code')->nullable()->comment('Using for teacher')->after('university_Code');
            $table->enum('edu_status',['graduated','not_graduated'])->comment('educational status Using for teacher')->nullable()->after('faculty_code');
        });

        // Schema::table('users', function (Blueprint $table) {
        //     $table->foreign('university_Code')->references('university_Code')->on('school_masters');
        //     $table->foreign('faculty_code')->references('faculty_code')->on('school_masters');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('university_Code')->nullable()->comment('Using for teacher')->after('seen_at');
            $table->unsignedInteger('faculty_code')->nullable()->comment('Using for teacher')->after('university_Code');
            $table->enum('edu_status',['graduated','not_graduated'])->comment('educational status Using for teacher')->nullable()->after('faculty_code');
        });

        // Schema::table('users', function (Blueprint $table) {
        //     $table->foreign('university_Code')->references('university_Code')->on('school_masters');
        //     $table->foreign('faculty_code')->references('faculty_code')->on('school_masters');
        // });

    }
}
