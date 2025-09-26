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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flat_id')->constrained('flats')->onDelete('cascade');
            $table->foreignId('bill_category_id')->constrained('bill_categories')->onDelete('cascade');
            $table->foreignId('building_id')->constrained('buildings')->onDelete('cascade');
            $table->string('bill_month'); 
            $table->decimal('amount', 10, 2);
            $table->decimal('due_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->storedAs('amount + due_amount');
            $table->enum('status', ['unpaid', 'paid', 'partially_paid', 'overdue'])->default('unpaid');
            $table->date('due_date');
            $table->date('paid_date')->nullable();
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->text('payment_notes')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('house_owners')->onDelete('set null');
            $table->timestamps();
            
            $table->unique(['flat_id', 'bill_category_id', 'bill_month']);
            $table->index(['building_id', 'status', 'due_date']);
            $table->index(['flat_id', 'status']);
            $table->index(['bill_month', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
