<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('jabatans')->insert([
            ['jabatan' => 'Direktur Utama'],
            ['jabatan' => 'Direktur Bisnis'],
            ['jabatan' => 'PE Manrisk'],
            ['jabatan' => 'PE Audit Internal'],
            ['jabatan' => 'HRD'],

            ['jabatan' => 'Pimpinan Cabang'],
            ['jabatan' => 'Pimpinan Kas Tlogomas'],
            ['jabatan' => 'Pimpinan Kas Pakisaji'],

            ['jabatan' => 'Team Leader Corporate'],
            ['jabatan' => 'Account Officer Corporate'],
            ['jabatan' => 'Fundind Corporate'],
            ['jabatan' => 'Fundind Retail'],

            ['jabatan' => 'Team Leader Operasional'],
            ['jabatan' => 'Admin Tabungan'],
            ['jabatan' => 'Customer Service'],
            ['jabatan' => 'Kasir'],
            ['jabatan' => 'Accounting'],
            ['jabatan' => 'Admin Kredit'],
            ['jabatan' => 'Admin Jaminan'],

            ['jabatan' => 'Team Leader Analis'],
            ['jabatan' => 'Analis'],

            ['jabatan' => 'Team Leader Collection'],
            ['jabatan' => 'Remedial'],
            ['jabatan' => 'Field Call'],
            ['jabatan' => 'Recovery'],

            ['jabatan' => 'Team Leader Account Officer'],
            ['jabatan' => 'Account Officer'],

            ['jabatan' => 'Team Leader Digital Marketing'],
            ['jabatan' => 'Account Officer Digital'],
            ['jabatan' => 'Productions'],
            ['jabatan' => 'IT Developer'],

            ['jabatan' => 'Account Officer Haji'],
            ['jabatan' => 'Account Officer Bidan'],

            ['jabatan' => 'OB'],
            ['jabatan' => 'Driver'],
            ['jabatan' => 'Security'],

        ]);
    }
}
