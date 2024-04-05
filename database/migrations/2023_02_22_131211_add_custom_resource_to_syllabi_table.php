<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('syllabi', function (Blueprint $table) {
            //
            $table->text('custom_resource')->after('land_acknow')->nullable();
            $table->text('custom_resource_title')->after('custom_resource')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('syllabi', function (Blueprint $table) {
            //
            $table->dropColumn(['custom_resource']);
            $table->dropColumn(['custom_resource_title']);
        });
    }
};
