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
        Schema::table('standards_outcome_maps', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('course_id')->after('standard_id');
            $table->foreign('course_id')->references('course_id')->on('courses')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('standards_outcome_maps', function (Blueprint $table) {
            //
            $table->dropColumn('course_id');
        });
    }
};
