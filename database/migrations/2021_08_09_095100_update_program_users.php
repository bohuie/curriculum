<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProgramUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // rename program_users table
        Schema::rename('program_users', 'program_users_old');

        //create new course_userss table
        Schema::create('program_userss', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('program_id');
            $table->unsignedBigInteger('permission')->default(0);
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('program_id')->references('program_id')->on('programs')->onDelete('cascade')->onUpdate('cascade');

            $table->unique(['user_id', 'program_id']);
        });
        // rename it to course_users.... This is the only solution that allowed me to define the foreign keys properly without deleting the old table.
        Schema::rename('program_userss', 'program_users');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('program_users');
    }
}
