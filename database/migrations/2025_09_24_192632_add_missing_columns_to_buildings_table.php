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
        Schema::table('buildings', function (Blueprint $table) {
            $table->string('type')->default('Residential');
            $table->year('built_year')->nullable();
            $table->boolean('has_parking')->default(false);
            $table->boolean('has_elevator')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buildings', function (Blueprint $table) {
            $table->dropColumn(['type', 'built_year', 'has_parking', 'has_elevator']);
        });
    }
};
