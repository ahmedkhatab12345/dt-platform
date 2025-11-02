<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $resources = [
            'users',
            'roles',
            'government_entities',
            'categories',
            'projects',
            'perspectives',
            'pillars',
            'standards',
            'tools',
            'assignments',
            'performance_analysis',
        ];

        $actions = ['create', 'read', 'update', 'delete'];

        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                $name = "{$action} {$resource}";
                Permission::firstOrCreate(['name' => $name]);
            }
        }
    }
}
