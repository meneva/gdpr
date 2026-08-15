<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subject_access_requests', function (Blueprint $table) {
            $table->timestamp('reminder_sent_at')->nullable()->after('closed_at');
        });

        Schema::table('data_breaches', function (Blueprint $table) {
            $table->timestamp('reminder_sent_at')->nullable()->after('resolved_at');
        });
    }

    public function down(): void
    {
        Schema::table('subject_access_requests', function (Blueprint $table) {
            $table->dropColumn('reminder_sent_at');
        });

        Schema::table('data_breaches', function (Blueprint $table) {
            $table->dropColumn('reminder_sent_at');
        });
    }
};
