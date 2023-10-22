<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = array(
            ['slug' => 'ENG', 'name' => 'English Language'],
            ['slug' => 'MTH', 'name' => 'Mathematics'],
            ['slug' => 'FRN', 'name' => 'French'],
            ['slug' => 'IGB', 'name' => 'Igbo Language'],
            ['slug' => 'CRS', 'name' => 'Christian Religious Studies'],
            ['slug' => 'CIVIC', 'name' => 'Civic Education'],
            ['slug' => 'LIT', 'name' => 'Literature in English'],

            ['slug' => 'BSC', 'name' => 'Basic Science'],
            ['slug' => 'ICT', 'name' => 'Information and Comm. Tech.'],
            ['slug' => 'BTEC', 'name' => 'Basic Technology'],
            ['slug' => 'PHE', 'name' => 'Physical & Health Edu.'],
            ['slug' => 'AGRIC', 'name' => 'Agricultural Science'],
            ['slug' => 'SEC', 'name' => 'Security Education'],
            ['slug' => 'HOMEC', 'name' => 'Home Economics'],
            ['slug' => 'CCA', 'name' => 'Cultural and Creative Arts'],
            ['slug' => 'MUS', 'name' => 'Music'],

            ['slug' => 'PHY', 'name' => 'Physics'],
            ['slug' => 'CHEM', 'name' => 'Chemistry'],
            ['slug' => 'BIO', 'name' => 'Biology'],
            ['slug' => 'GOVT', 'name' => 'Government'],
            ['slug' => 'ECONS', 'name' => 'Economics'],
            ['slug' => 'DATA', 'name' => 'Data Processing'],
            ['slug' => 'BOOK', 'name' => 'Book Keeping'],
        );

        Subject::query()->upsert($subjects, 'slug');
    }
}
