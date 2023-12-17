<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('okanagan_syllabi', function (Blueprint $table) {
            $table->text('course_description')->after('course_overview')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('okanagan_syllabi', function (Blueprint $table) {
            $table->dropColumn(['course_description']);
        });
    }
};
