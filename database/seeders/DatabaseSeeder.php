<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Admin MAJAP',
            'email' => 'admin@majap.com',
        ]);

        // Seed initial settings
        $this->call([
            SettingSeeder::class,
            ContentSeeder::class,
        ]);
    }
}
