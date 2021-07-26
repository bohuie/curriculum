<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSyllabusTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vancouver_syllabi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('syllabus_id');
            $table->foreign('syllabus_id')->references('id')->on('syllabi')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('course_credit');
            $table->string('office_location')->nullable();
            $table->text('course_description')->nullable();
            $table->text('course_contacts')->nullable();
            $table->text('instructor_bio')->nullable();
            $table->text('course_prereqs')->nullable();
            $table->text('course_coreqs')->nullable();
            $table->text('course_structure')->nullable();
            $table->text('course_schedule')->nullable();
            $table->timestamps();
        });

        Schema::create('okanagan_syllabi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('syllabus_id');
            $table->foreign('syllabus_id')->references('id')->on('syllabi')->onUpdate('cascade')->onDelete('cascade');
            $table->text('course_format')->nullable();
            $table->text('course_overview')->nullable();
            $table->timestamps();
        });

        Schema::create('vancouver_syllabus_resources', function (Blueprint $table) {
            $table->id();
            $table->string('id_name')->unique();
            $table->string('title');
            // $table->text('description');
            $table->timestamps();
        });

        Schema::create('okanagan_syllabus_resources', function (Blueprint $table) {
            $table->id();
            $table->string('id_name')->unique();
            $table->string('title');
            // $table->text('description');
            $table->timestamps();
        });


        Schema::create('syllabi_resources_vancouver', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('syllabus_id');
            $table->unsignedBigInteger('v_syllabus_resource_id');
            $table->foreign('syllabus_id')->references('id')->on('syllabi')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('v_syllabus_resource_id')->references('id')->on('vancouver_syllabus_resources')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });

        Schema::create('syllabi_resources_okanagan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('syllabus_id');
            $table->unsignedBigInteger('o_syllabus_resource_id');
            $table->foreign('syllabus_id')->references('id')->on('syllabi')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('o_syllabus_resource_id')->references('id')->on('okanagan_syllabus_resources')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vancouver_syllabi');
        Schema::dropIfExists('okanagan_syllabi');
        Schema::dropIfExists('syllabi_resources_vancouver');
        Schema::dropIfExists('syllabi_resources_okanagan');
        Schema::dropIfExists('vancouver_syllabus_resources');
        Schema::dropIfExists('okanagan_syllabus_resources');

    }
}
