<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KantorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kantors')->insert([
            ['name' => 'Kantor Pusat', 'latitude' => -7.937910060243632, 'longitude' => 112.62552200666696, 'created_at' => now(), 'radius' => 15],
            ['name' => 'Kantor Cabang', 'latitude' => -7.66780, 'longitude' => 112.69946, 'created_at' => now(), 'radius' => 15],
            ['name' => 'Kantor Kas Tlogomas', 'latitude' => -7.92938, 'longitude' => 112.60305, 'created_at' => now(), 'radius' => 15],
            ['name' => 'Kantor Kas Pakisaji', 'latitude' => -8.07425, 'longitude' => 112.59364, 'created_at' => now(), 'radius' => 15],
        ]);
    }
}
