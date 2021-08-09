<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionalPrioritiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('optional_priorities', function (Blueprint $table) {
            $table->bigIncrements('op_id');            
            $table->unsignedBigInteger('subcat_id');
            $table->text('optional_priority');
            //$table->unsignedBigInteger('input_status');
            $table->timestamps();

            $table->foreign('subcat_id')->references('subcat_id')->on('optional_priority_subcategories')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('optional_priorities');
    }
}
