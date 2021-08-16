<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSyllabiAndSyllabiUsersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // update syllabi table
        Schema::table('syllabi', function(Blueprint $table) {
            // add course_title col
            $table->string('course_title')->after('id')->nullable();
            // add course_code col
            $table->string('course_code')->after('course_title')->nullable();
            // add course_num col
            $table->unsignedBigInteger('course_num')->after('course_code')->nullable();
            // add course_term col
            $table->char('course_term')->length(2)->after('course_num')->nullable();
            // add course_year col
            $table->integer('course_year')->length(4)->after('course_term')->nullable();
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
