<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('processing_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('ref_no', 32);
            $table->string('name');
            $table->text('purpose')->nullable();
            $table->string('legal_basis')->nullable();
            $table->text('data_categories')->nullable();
            $table->string('retention_period')->nullable();
            $table->text('third_parties_involved')->nullable();
            $table->string('owner_name')->nullable();
            $table->timestamps();

            $table->unique(['company_id', 'ref_no']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('processing_activities');
    }
};
