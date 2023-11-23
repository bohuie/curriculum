<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCopyirghtToSyllabiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('syllabi', function (Blueprint $table) {
            $table->text('cc_license')->after('include_alignment')->nullable();
            $table->boolean('copyright')->after('cc_license')->nullable();

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
            $table->dropColumn(['cc_license']);
            $table->dropColumn(['copyright']);
        });
    }
}
