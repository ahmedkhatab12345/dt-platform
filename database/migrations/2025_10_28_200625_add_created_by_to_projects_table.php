<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('government_entities', 'uuid')) {
            return;
        }

        Schema::table('government_entities', function (Blueprint $table) {
            $table->string('uuid')->unique()->after('id');
        });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('government_entities', 'uuid')) {
            return;
        }

        Schema::table('government_entities', function (Blueprint $table) {
            $table->dropUnique(['uuid']);
            $table->dropColumn('uuid');
        });
    }
};
