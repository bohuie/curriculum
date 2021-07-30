<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StandardsUseMapScaleId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('standards_outcome_maps', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('standard_scale_id')->default(0)->after('map_scale_value');
            
            $table->foreign('standard_scale_id')->references('standard_scale_id')->on('standard_scales')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('standards_outcome_maps', function (Blueprint $table) {
            //
            $table->dropForeign('standards_outcome_maps_standard_scale_id_foreign');
            $table->dropColumn('standard_scale_id');
        });
    }
}
