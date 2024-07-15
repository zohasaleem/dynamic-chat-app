<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;



class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'view user','guard_name' => 'web']);
        Permission::create(['name' => 'edit user', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete user','guard_name' => 'web']);


        $adminRole = Role::where('name', 'admin')->first();

        $permissions = Permission::all(); 
        $adminRole->syncPermissions($permissions);
    }
}
