<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;

class RoleSuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = \Spatie\Permission\Models\Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'web',
        ]);

        $permissions = \Spatie\Permission\Models\Permission::all();
        $role->syncPermissions($permissions);
    }
}
