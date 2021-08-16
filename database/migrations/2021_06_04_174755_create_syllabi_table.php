<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSyllabiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // create syllabi table
        Schema::create('syllabi', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->char('campus', 1);
            $table->string('course_instructor');
            $table->string('course_location')->nullable();
            $table->text('office_hours')->nullable();
            $table->char('class_meeting_days', 20)->nullable();
            $table->text('other_instructional_staff')->nullable();
            $table->string('class_start_time', 20)->nullable();
            $table->string('class_end_time', 20)->nullable();
            $table->text('learning_outcomes')->nullable();
            $table->text('learning_assessments')->nullable();
            $table->text('learning_activities')->nullable();
            $table->text('late_policy')->nullable();
            $table->text('missed_exam_policy')->nullable();
            $table->text('missed_activity_policy')->nullable();
            $table->text('passing_criteria')->nullable();
            $table->text('learning_materials')->nullable();
            $table->text('learning_resources')->nullable();
        });

        Schema::create('syllabi_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('syllabus_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('permission')->default(0);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('syllabus_id')->references('id')->on('syllabi')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // update courses table
        Schema::table('courses', function (Blueprint $table) {
            // delete course_create_method col
            $table->dropColumn('create_method');
        });   
        // delete syllabi_users table
        Schema::dropIfExists('syllabi_users');

        // delete syllabi table
        Schema::dropIfExists('syllabi');

    
    }
}
