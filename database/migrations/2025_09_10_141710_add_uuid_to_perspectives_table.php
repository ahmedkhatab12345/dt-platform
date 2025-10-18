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
        Schema::table('perspectives', function (Blueprint $table) {
            $table->string('uuid')->unique()->after('id');
            $table->dropColumn('order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perspectives', function (Blueprint $table) {
            $table->dropUnique(['uuid']);
            $table->dropColumn('uuid');

            $table->unsignedInteger('order')->default(0);
        });
    }
};
