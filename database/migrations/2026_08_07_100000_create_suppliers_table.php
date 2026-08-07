<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('ref_no', 32);
            $table->string('name');
            $table->string('category')->nullable();
            $table->boolean('dpa_on_file')->default(false);
            $table->string('risk_level')->default('low'); // low|medium|high
            $table->date('last_reviewed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['company_id', 'ref_no']);
            $table->index(['company_id', 'risk_level']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
