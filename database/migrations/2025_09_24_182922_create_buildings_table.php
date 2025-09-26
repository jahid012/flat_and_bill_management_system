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
        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address');
            $table->string('city');
            $table->string('state');
            $table->string('zip_code');
            $table->integer('total_floors')->default(1);
            $table->integer('total_flats')->default(1);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('house_owner_id')->constrained('house_owners')->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['house_owner_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buildings');
    }
};
