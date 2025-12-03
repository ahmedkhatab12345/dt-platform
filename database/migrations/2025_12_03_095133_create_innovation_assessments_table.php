<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('innovation_assessments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('government_entity_id')
                ->constrained('government_entities')
                ->cascadeOnDelete();

            $table->text('summary')->nullable();
            $table->text('strengths')->nullable();
            $table->text('weaknesses')->nullable();
            $table->text('recommendation')->nullable();

            $table->enum('understanding_level', ['high', 'medium', 'low'])
                ->default('medium');

            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->index(['government_entity_id', 'created_by']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('innovation_assessments');
    }
};
