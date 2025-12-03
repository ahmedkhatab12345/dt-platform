<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('government_entities', 'created_by')) {
            return;
        }

        Schema::table('government_entities', function (Blueprint $table) {
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->after('uuid');
        });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('government_entities', 'created_by')) {
            return;
        }
        
        Schema::table('government_entities', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
        });
    }
};
