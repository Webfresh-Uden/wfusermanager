<?php

namespace WebFresh\UserManager\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserManagerSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Roel van Lierop',
            'email' => 'roel@webfresh.nl',
            'password' => bcrypt('WebFresh2026'),
        ]);
    }
}
