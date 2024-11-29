<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('schedules')->insert([
            [
                'user_id' => 1,
                'shift_id' => 1,
                'kantor_id' => 1,
                'created_at' => now()
            ],
            [
                'user_id' => 2,
                'shift_id' => 1,
                'kantor_id' => 1,
                'created_at' => now()
            ],

        ]);
    }
}
