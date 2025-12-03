<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('innovation_frameworks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('government_entity_id')
                ->constrained('government_entities')
                ->cascadeOnDelete();

            $table->string('framework_name');
            $table->text('details')->nullable();

            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->index(['government_entity_id', 'created_by']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('innovation_frameworks');
    }
};
