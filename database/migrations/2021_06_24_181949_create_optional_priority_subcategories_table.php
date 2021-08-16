<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionalPrioritySubcategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('optional_priority_subcategories', function (Blueprint $table) {
            $table->bigIncrements('subcat_id');            
            $table->text('subcat_name');
            $table->unsignedBigInteger('cat_id');
            $table->text('subcat_desc')->nullable();
            $table->text('subcat_postamble')->nullable();
            $table->timestamps();
            $table->foreign('cat_id')->references('cat_id')->on('optional_priority_categories')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('optional_priority_subcategories');
    }
}
