<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMapScaleIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('outcome_maps', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('map_scale_id')->default(0)->after('map_scale_value');
            
            $table->foreign('map_scale_id')->references('map_scale_id')->on('mapping_scales')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('outcome_maps', function (Blueprint $table) {
            //
            $table->dropForeign('outcome_maps_map_scale_id_foreign');
            $table->dropColumn('map_scale_id');
        });
    }
}
