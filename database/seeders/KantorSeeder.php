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
            ['nama_kantor' => 'Kantor Pusat', 'latitude' => -7.979, 'longitude' => 112.624],
            ['nama_kantor' => 'Kantor Cabang', 'latitude' => -7.979, 'longitude' => 112.624],
            ['nama_kantor' => 'Kantor Kas Tlogomas', 'latitude' => -7.979, 'longitude' => 112.624],
            ['nama_kantor' => 'Kantor Kas Pakisaji', 'latitude' => -7.979, 'longitude' => 112.624],
        ]);
    }
}
