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
        Schema::table('learning_activities', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('l_activities_pos')->default(0)->after('course_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('learning_activities', function (Blueprint $table) {
            //
            $table->drop('l_activities_pos');
        });
    }
};
