<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;


class SupportPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'view chat']);
        Permission::create(['name' => 'block user']);
        Permission::create(['name' => 'view online-status']);


        // $supportRole = Role::where('name', 'support')->first();

        // $permissions = Permission::all(); 
        // $adminRole->syncPermissions($permissions);
    }
}
