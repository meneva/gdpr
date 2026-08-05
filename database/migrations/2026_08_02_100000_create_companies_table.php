<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('industry')->nullable();
            $table->string('timezone')->default('UTC');
            $table->string('subscription_status')->default('trial'); // trial|active|cancelled

            // Per-module sequence counters, used to generate ref numbers like SAR-2026-014.
            // Kept on the company row (not COUNT(*) on the child table) so ref numbers stay
            // stable and race-safe even if requests are later deleted.
            $table->unsignedInteger('sar_sequence')->default(0);
            $table->unsignedInteger('breach_sequence')->default(0);
            $table->unsignedInteger('dpia_sequence')->default(0);
            $table->unsignedInteger('supplier_sequence')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
