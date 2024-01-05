<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
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
     */
    public function down(): void
    {
        Schema::dropIfExists('syllabus_instructors');
    }
};
