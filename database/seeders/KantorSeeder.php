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
            ['nama_kantor' => 'Kantor Pusat'],
            ['nama_kantor' => 'Kantor Cabang'],
            ['nama_kantor' => 'Kantor Kas Tlogomas'],
            ['nama_kantor' => 'Kantor Kas Pakisaji'],
        ]);
    }
}
