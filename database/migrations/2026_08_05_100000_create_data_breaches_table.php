<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_breaches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('ref_no', 32);
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('severity')->default('low'); // low|medium|high
            $table->dateTime('discovered_at');
            $table->dateTime('notify_deadline_at'); // computed at creation: discovered_at + 72 hours
            $table->string('status')->default('assessing'); // assessing|notified|resolved
            $table->dateTime('ico_notified_at')->nullable();
            $table->dateTime('resolved_at')->nullable();
            $table->timestamps();

            $table->unique(['company_id', 'ref_no']);
            $table->index(['company_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_breaches');
    }
};
