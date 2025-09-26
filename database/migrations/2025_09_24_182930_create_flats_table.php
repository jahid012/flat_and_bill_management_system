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
        Schema::create('flats', function (Blueprint $table) {
            $table->id();
            $table->string('flat_number');
            $table->integer('floor');
            $table->enum('type', ['1BHK', '2BHK', '3BHK', '4BHK', 'Studio', 'Other'])->default('1BHK');
            $table->decimal('area_sqft', 8, 2)->nullable();
            $table->decimal('rent_amount', 10, 2)->default(0);
            $table->string('flat_owner_name')->nullable();
            $table->string('flat_owner_phone')->nullable();
            $table->string('flat_owner_email')->nullable();
            $table->boolean('is_occupied')->default(false);
            $table->boolean('is_active')->default(true);
            $table->foreignId('building_id')->constrained('buildings')->onDelete('cascade');
            $table->foreignId('house_owner_id')->constrained('house_owners')->onDelete('cascade');
            $table->unsignedBigInteger('current_tenant_id')->nullable();
            $table->timestamps();
            
            $table->unique(['building_id', 'flat_number']);
            $table->index(['building_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flats');
    }
};
