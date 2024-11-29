<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('shifts')->insert([
            [
                'name' => 'Pagi',
                'start_time' => '08:00:00',
                'end_time' => '16:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lembur',
                'start_time' => '16:00:00',
                'end_time' => '23:59:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
