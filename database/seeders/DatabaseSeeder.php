<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\RoleSuperAdminSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::firstOrCreate([
            'email' => 'admin@admin.com',
        ], [
            'name' => 'Usuário administrador',
            'password' => bcrypt('admin'),
        ]);

        $this->call([
            RoleSuperAdminSeeder::class,
        ]);

        $user->assignRole('Super Admin');
        
    }
}
