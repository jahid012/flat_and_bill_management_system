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
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('building_id')->constrained('buildings')->onDelete('cascade');
            $table->foreignId('flat_id')->nullable()->constrained('flats')->onDelete('set null');
            $table->foreignId('assigned_by')->nullable()->constrained('admins')->onDelete('set null');
            $table->date('lease_start_date')->nullable();
            $table->date('lease_end_date')->nullable();
            $table->decimal('security_deposit', 10, 2)->default(0);
            $table->timestamps();
            
            $table->index(['building_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
