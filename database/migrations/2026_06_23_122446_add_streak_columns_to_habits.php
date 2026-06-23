<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bulan 4: tambah kolom streak tambahan dan kolom note pada habit_logs
     */
    public function up(): void
    {
        // Tambah kolom ke tabel habits
        Schema::table('habits', function (Blueprint $table) {
            $table->integer('longest_streak')->default(0)->after('streak_count');
            $table->date('last_checked_at')->nullable()->after('longest_streak');
        });

        // Tambah kolom note ke habit_logs
        Schema::table('habit_logs', function (Blueprint $table) {
            $table->string('note', 500)->nullable()->after('completed_at');
        });
    }

    public function down(): void
    {
        Schema::table('habits', function (Blueprint $table) {
            $table->dropColumn(['longest_streak', 'last_checked_at']);
        });

        Schema::table('habit_logs', function (Blueprint $table) {
            $table->dropColumn('note');
        });
    }
};
