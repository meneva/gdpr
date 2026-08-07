<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dpias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('ref_no', 32);
            $table->string('project_name');
            $table->string('owner_name')->nullable();
            $table->text('description')->nullable();
            $table->string('risk_level')->default('low'); // low|medium|high
            $table->string('status')->default('draft'); // draft|in_review|approved|rejected
            $table->date('due_at');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->unique(['company_id', 'ref_no']);
            $table->index(['company_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dpias');
    }
};
