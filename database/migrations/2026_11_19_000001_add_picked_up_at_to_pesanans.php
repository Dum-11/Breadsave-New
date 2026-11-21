<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('pesanans', 'picked_up_at')) {
            Schema::table('pesanans', function (Blueprint $table) {
                $table->timestamp('picked_up_at')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('pesanans', 'picked_up_at')) {
            Schema::table('pesanans', function (Blueprint $table) {
                $table->dropColumn('picked_up_at');
            });
        }
    }
};
