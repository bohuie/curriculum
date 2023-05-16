<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsCheckableOptionalPriority extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('optional_priorities', function (Blueprint $table) {
            //
            $table->tinyInteger('isCheckable')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('optional_priorities', function (Blueprint $table) {
            //
            $table->dropColumn('isCheckable');
        });
    }
}
