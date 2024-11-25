<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DivisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('divisis')->insert([
            ['name' => 'Pimpinan'],
            ['name' => 'Pejabat Eksekutif'],
            ['name' => 'Team Leader'],
            ['name' => 'Lending'],
            ['name' => 'Funding'],
            ['name' => 'Operational'],
            ['name' => 'Collection'],
            ['name' => 'Digital Marketing'],
        ]);
    }
}
