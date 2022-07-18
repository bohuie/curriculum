<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLActivitiesPosToLearningActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('learning_activities', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('l_activities_pos')->default(0)->after('course_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('learning_activities', function (Blueprint $table) {
            //
            $table->drop('l_activities_pos');
        });
    }
}
