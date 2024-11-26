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
            ['name' => 'Pimpinan', 'created_at' => now()],
            ['name' => 'Pejabat Eksekutif', 'created_at' => now()],
            ['name' => 'Team Leader', 'created_at' => now()],
            ['name' => 'Lending', 'created_at' => now()],
            ['name' => 'Funding', 'created_at' => now()],
            ['name' => 'Operational', 'created_at' => now()],
            ['name' => 'Collection', 'created_at' => now()],
            ['name' => 'Digital Marketing', 'created_at' => now()],
        ]);
    }
}
