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
        Schema::create('table_cols', function (Blueprint $table) {
            $table->id();

            $table->foreignId('table_id')->constrained('tables')->cascadeOnDelete();

            $table->string('name');
            $table->string('type')->default('varchar')->nullable();
            $table->bigInteger('length')->default(255)->nullable();
            $table->string('default')->nullable();
            $table->string('comment')->nullable();

            $table->string('foreign_table')->nullable();
            $table->string('foreign_col')->nullable();
            $table->string('foreign_model')->nullable();

            $table->boolean('nullable')->default(false)->nullable();
            $table->boolean('index')->default(false)->nullable();
            $table->boolean('auto_increment')->default(false)->nullable();
            $table->boolean('primary')->default(false)->nullable();
            $table->boolean('unique')->default(false)->nullable();
            $table->boolean('unsigned')->default(false)->nullable();
            $table->boolean('foreign')->default(false)->nullable();
            $table->boolean('foreign_on_delete_cascade')->default(false)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_cols');
    }
};
