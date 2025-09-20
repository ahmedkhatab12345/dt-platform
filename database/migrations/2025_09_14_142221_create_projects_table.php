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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('overview')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('budget', 15, 2);
            $table->enum('status', ['planned', 'in_progress', 'completed'])->default('planned');
            $table->string('department');
            $table->json('indicators')->nullable();
            $table->json('final_deliverables')->nullable();
            $table->json('activities')->nullable();
            $table->foreignId('government_entity_id')
                ->constrained('government_entities')
                ->cascadeOnDelete();

            $table->foreignId('standard_id')
                ->constrained('standards')
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
