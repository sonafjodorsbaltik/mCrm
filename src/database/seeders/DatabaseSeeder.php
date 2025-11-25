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
        User::factory()->asAdmin()->create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
        ]);

        User::factory()->asManager()->create([
            'name' => 'Manager',
            'email' => 'manager@test.com',
        ]);

        $this->call([
            CustomerSeeder::class,
            TicketSeeder::class,
        ]);
    }
}
