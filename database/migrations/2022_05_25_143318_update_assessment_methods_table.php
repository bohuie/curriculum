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
        Schema::table('assessment_methods', function (Blueprint $table) {
            $table->unsignedBigInteger('pos_in_alignment')->default(0)->after('course_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessment_methods', function (Blueprint $table) {
            $table->drop('pos_in_alignment');
        });
    }
};
