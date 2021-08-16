<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseOptionalPrioritiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_optional_priorities', function (Blueprint $table) {
            $table->unsignedBigInteger('op_id');
            $table->unsignedBigInteger('course_id');
            $table->primary(['op_id','course_id']);
            //$table->unsignedBigInteger('input_status'); //not sure what this does
            $table->timestamps();
            $table->foreign('course_id')->references('course_id')->on('courses')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('op_id')->references('op_id')->on('optional_priorities')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_optional_priorities');
    }
}
