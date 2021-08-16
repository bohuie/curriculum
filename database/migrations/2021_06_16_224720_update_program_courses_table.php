<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProgramCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_programs', function (Blueprint $table) {
            $table->integer('course_required')->nullable();
            $table->integer('instructor_assigned')->nullable();
            $table->integer('map_status')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_programs', function (Blueprint $table) {
            $table->dropColumn('course_required');
            $table->dropColumn('instructor_assigned');
            $table->dropColumn('map_status');
        });
    }
}
