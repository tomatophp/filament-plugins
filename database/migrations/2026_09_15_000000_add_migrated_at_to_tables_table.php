<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Records when the table builder created (and therefore owns) the database table.
     */
    public function up(): void
    {
        Schema::table(config('filament-plugins.database_prefix') ? config('filament-plugins.database_prefix').'_tables' : 'tables', function (Blueprint $table) {
            $table->timestamp('migrated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table(config('filament-plugins.database_prefix') ? config('filament-plugins.database_prefix').'_tables' : 'tables', function (Blueprint $table) {
            $table->dropColumn('migrated_at');
        });
    }
};
