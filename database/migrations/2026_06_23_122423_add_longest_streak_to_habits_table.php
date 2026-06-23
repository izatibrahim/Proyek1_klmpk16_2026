<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('habits', function (Blueprint $table) {
            // Streak terpanjang sepanjang masa
            $table->integer('longest_streak')->default(0)->after('streak_count');
            // Tanggal mulai habit (untuk statistik)
            $table->date('started_at')->nullable()->after('longest_streak');
        });
    }

    public function down(): void
    {
        Schema::table('habits', function (Blueprint $table) {
            $table->dropColumn(['longest_streak', 'started_at']);
        });
    }
};
