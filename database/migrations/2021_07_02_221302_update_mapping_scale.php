<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateMappingScale extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mapping_scales', function (Blueprint $table) {
            $table->unsignedBigInteger('mapping_scale_categories_id')->nullable();

            $table->foreign('mapping_scale_categories_id')->references('mapping_scale_categories_id')->on('mapping_scale_categories')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mapping_scales', function (Blueprint $table) {
            $table->dropForeign('mapping_scales_mapping_scale_categories_id_foreign');
            $table->dropColumn('mapping_scale_categories_id');
        });
    }
}
