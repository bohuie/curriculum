<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLastModifiedUserToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            //
            $table->string('last_modified_user')->nullable();
        });

        Schema::table('programs', function (Blueprint $table) {
            //
            $table->string('last_modified_user')->nullable();
        });

        Schema::table('syllabi', function (Blueprint $table) {
            //
            $table->string('last_modified_user')->nullable();
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
            $table->dropColumn('last_modified_user');
        });

        Schema::table('programs', function (Blueprint $table) {
            //
            $table->dropColumn('last_modified_user');
        });

        Schema::table('syllabi', function (Blueprint $table) {
            //
            $table->dropColumn('last_modified_user');
        });
    }
}
