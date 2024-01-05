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
        Schema::table('learning_outcomes', function (Blueprint $table) {
            $table->unsignedBigInteger('pos_in_alignment')->default(0)->after('course_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('learning_outcomes', function (Blueprint $table) {
            //
            $table->dropColumn('pos_in_alignment');
        });
    }
};
