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
        Schema::table('optional_priorities', function (Blueprint $table) {
            //
            $table->tinyInteger('isCheckable')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('optional_priorities', function (Blueprint $table) {
            //
            $table->dropColumn('isCheckable');
        });
    }
};
