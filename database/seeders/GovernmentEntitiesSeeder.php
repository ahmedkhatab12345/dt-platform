<?php

namespace Database\Seeders;

use App\Models\GovernmentEntity;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class GovernmentEntitiesSeeder extends Seeder
{
    public function run(): void
    {
        $count = GovernmentEntity::whereNull('classification')
            ->orWhere('classification', '')
            ->update(['classification' => 'no classification']);

        $this->command->info("✅ تم تحديث $count جهة حكومية إلى التصنيف 'no classification'.");
    }
}
