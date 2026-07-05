<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->index('year');
            $table->index('fuel_type');
            $table->index('transmission');
            $table->index(['status', 'is_featured']);
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->index('read_at');
        });
    }

    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropIndex(['year']);
            $table->dropIndex(['fuel_type']);
            $table->dropIndex(['transmission']);
            $table->dropIndex(['status', 'is_featured']);
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->dropIndex(['read_at']);
        });
    }
};
