<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStandardsOutcomeMapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('standards_outcome_maps', function (Blueprint $table) {
            $table->unsignedBigInteger('standard_id');
            $table->unsignedBigInteger('l_outcome_id');
            $table->string('map_scale_value');
            $table->timestamps();

            $table->primary(['standard_id', 'l_outcome_id']);
            $table->foreign('l_outcome_id')->references('l_outcome_id')->on('learning_outcomes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('standard_id')->references('standard_id')->on('standards')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('standards_outcome_maps');
    }
}
