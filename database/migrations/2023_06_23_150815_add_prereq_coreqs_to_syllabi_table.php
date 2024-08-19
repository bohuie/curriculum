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
        //
        Schema::table('syllabi', function (Blueprint $table) {
            //
            $table->text('course_section')->after('cross_listed_num')->nullable();
            $table->text('prerequisites')->after('course_section')->nullable();
            $table->text('corequisites')->after('prerequisites')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('syllabi', function (Blueprint $table) {
            //
            $table->dropColumn(['course_section']);
            $table->dropColumn(['prerequisites']);
            $table->dropColumn(['corequisites']);
        });
    }
};
