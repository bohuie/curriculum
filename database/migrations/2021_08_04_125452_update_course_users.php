<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateCourseUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        // rename course_users table
        Schema::rename('course_users', 'course_users_old');

        //create new course_userss table
        Schema::create('course_userss', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('permission')->default(0);
            $table->timestamps();
            
            $table->foreign('course_id')->references('course_id')->on('courses')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->unique(['course_id', 'user_id']);
        });
        // rename it to course_users.... This is the only solution that allowed me to define the foreign keys properly without deleting the old table.
        Schema::rename('course_userss', 'course_users');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_users');
    }
}
