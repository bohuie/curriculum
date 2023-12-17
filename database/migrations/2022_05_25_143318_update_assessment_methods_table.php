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
        Schema::table('assessment_methods', function (Blueprint $table) {
            $table->unsignedBigInteger('pos_in_alignment')->default(0)->after('course_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assessment_methods', function (Blueprint $table) {
            $table->drop('pos_in_alignment');
        });
    }
};
