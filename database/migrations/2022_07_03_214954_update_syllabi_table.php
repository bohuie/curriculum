<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSyllabiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('syllabi', function (Blueprint $table) {
            $table->string('faculty')->after('campus')->nullable();
            $table->string('department')->after('faculty')->nullable();
            $table->unsignedBigInteger('course_id')->nullable();
            $table->foreign('course_id')->references('course_id')->on('courses')->onDelete('set null')->onUpdate('cascade'); 
            $table->boolean('include_alignment')->after('course_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('syllabi', function (Blueprint $table) {
            $table->dropForeign('syllabi_course_id_foreign');
            $table->dropColumn(['faculty', 'department', 'course_id', 'include_alignment']);
        });
    }
}
