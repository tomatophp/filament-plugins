<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table(config('filament-plugins.database_prefix') ? config('filament-plugins.database_prefix') . '_table_cols' : 'table_cols', function (Blueprint $table) {
            $table->integer('order')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(config('filament-plugins.database_prefix') ? config('filament-plugins.database_prefix') . '_table_cols' : 'table_cols', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
};
