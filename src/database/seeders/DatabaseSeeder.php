<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\TicketSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            CustomerSeeder::class,
            TicketSeeder::class,
        ]);

        // User::factory()->create([
        //     'name' => 'Admin',
        //     'email' => 'admin@example.com',
        // ]);
    }
}
