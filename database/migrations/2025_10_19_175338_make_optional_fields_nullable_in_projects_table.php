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
        Schema::table('projects', function (Blueprint $table) {
            $table->text('overview')->nullable()->change();
            $table->date('start_date')->nullable()->change();
            $table->date('end_date')->nullable()->change();
            $table->decimal('budget', 15, 2)->nullable()->change();
            $table->string('department')->nullable()->change();
            $table->enum('status', ['planned', 'in_progress', 'completed'])
                  ->nullable()
                  ->default(null)
                  ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->text('overview')->nullable(false)->change();
            $table->date('start_date')->nullable(false)->change();
            $table->date('end_date')->nullable(false)->change();
            $table->decimal('budget', 15, 2)->nullable(false)->change();
            $table->string('department')->nullable(false)->change();
            $table->enum('status', ['planned', 'in_progress', 'completed'])
                    ->default('planned')
                    ->nullable(false)
                    ->change();
        });
    }
};
