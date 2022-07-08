<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSyllabusInstructorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('syllabus_instructors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('syllabus_id');
            $table->foreign('syllabus_id')->references('id')->on('syllabi')->onDelete('cascade')->onUpdate('cascade');
            $table->string('name');
            $table->string('email');
            // $table->string('office_hours');
            // $table->string('office_location');
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
        Schema::dropIfExists('syllabus_instructors');
    }
}
