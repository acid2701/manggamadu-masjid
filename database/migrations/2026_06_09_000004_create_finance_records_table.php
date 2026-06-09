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
        Schema::create('finance_records', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('type', ['income', 'expense']);
            $table->string('category', 100)->comment('Donasi, Operasional, Renovasi, dll');
            $table->decimal('amount', 15, 2);
            $table->enum('source', ['donation', 'manual'])->default('manual');
            $table->foreignUuid('donation_id')->nullable()->constrained('donations')->nullOnDelete();
            $table->text('description')->nullable();
            $table->date('transaction_date');
            $table->foreignUuid('recorded_by')->constrained('users');
            $table->timestamps();

            $table->index('type');
            $table->index('transaction_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_records');
    }
};
