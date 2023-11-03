<?php

namespace Database\Seeders;

use App\Models\User;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(OauthClientSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ProgramSeeder::class);
        $this->call(QuizSeeder::class);
    }
}
