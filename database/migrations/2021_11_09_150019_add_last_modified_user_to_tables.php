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
        Schema::table('courses', function (Blueprint $table) {
            //
            $table->string('last_modified_user')->nullable();
        });

        Schema::table('programs', function (Blueprint $table) {
            //
            $table->string('last_modified_user')->nullable();
        });

        Schema::table('syllabi', function (Blueprint $table) {
            //
            $table->string('last_modified_user')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            //
            $table->dropColumn('last_modified_user');
        });

        Schema::table('programs', function (Blueprint $table) {
            //
            $table->dropColumn('last_modified_user');
        });

        Schema::table('syllabi', function (Blueprint $table) {
            //
            $table->dropColumn('last_modified_user');
        });
    }
};
