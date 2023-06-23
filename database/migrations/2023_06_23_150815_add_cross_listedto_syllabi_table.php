<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCrossListedtoSyllabiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('syllabi', function (Blueprint $table) {
            //
            $table->text('cross_listed_code')->after('custom_resource_title')->nullable();
            $table->text('cross_listed_num')->after('cross_listed_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('syllabi', function (Blueprint $table) {
            //
            $table->dropColumn(['cross_listed_code']);
            $table->dropColumn(['cross_listed_num']);
        });
    }
}
