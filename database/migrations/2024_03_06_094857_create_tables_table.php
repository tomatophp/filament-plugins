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
        Schema::create('tables', function (Blueprint $table) {
            $table->id();

            //Link To Module
            $table->string('module');
            $table->string('name')->index()->unique();
            $table->string('comment')->nullable();

            //Options
            $table->boolean('timestamps')->default(true)->nullable();
            $table->boolean('soft_deletes')->default(false)->nullable();
            $table->boolean('migrated')->default(false)->nullable();
            $table->boolean('generated')->default(false)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};
