<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStandardScalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('standard_scales', function (Blueprint $table) {
            $table->bigIncrements('standard_scale_id');
            $table->unsignedBigInteger('scale_category_id');
            $table->string('title');
            $table->string('abbreviation');
            $table->text('description');
            $table->char('colour', 7);
            $table->timestamps();

            $table->foreign('scale_category_id')->references('scale_category_id')->on('standards_scale_categories')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('standard_scales');
    }
}
