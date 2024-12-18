<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Flush the 'users' table
        User::query()->forceDelete();

        User::factory()->api()->create();
        User::factory()->admin()->create();
    }
}
