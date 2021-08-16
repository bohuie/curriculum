<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateMSCourses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->unsignedBigInteger('standard_category_id')->default(1);
            $table->unsignedBigInteger('scale_category_id')->default(1);

            $table->foreign('standard_category_id')->references('standard_category_id')->on('standard_categories')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('scale_category_id')->references('scale_category_id')->on('standards_scale_categories')->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            //
            $table->dropForeign('courses_standard_category_id_foreign');
            $table->dropColumn('standard_category_id');

            $table->dropForeign('courses_scale_category_id_foreign');
            $table->dropColumn('scale_category_id');
        });
    }
}
