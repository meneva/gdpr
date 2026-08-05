<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subject_access_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('ref_no', 32);
            $table->string('requester_name');
            $table->string('requester_type')->default('customer'); // customer|employee|applicant|other
            $table->date('received_at');
            $table->date('deadline_at'); // computed at creation: received_at + 30 days
            $table->string('status')->default('received'); // received|verifying|in_progress|completed
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();

            $table->unique(['company_id', 'ref_no']);
            $table->index(['company_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subject_access_requests');
    }
};
