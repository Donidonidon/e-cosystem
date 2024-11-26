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
            // Pimpinan
            ['divisi_id' => 1, 'name' => 'Direktur Utama', 'created_at' => now()],
            ['divisi_id' => 1, 'name' => 'Direktur Bisnis', 'created_at' => now()],
            ['divisi_id' => 1, 'name' => 'Pimpinan Cabang', 'created_at' => now()],
            ['divisi_id' => 1, 'name' => 'Pimpinan Kas Tlogomas', 'created_at' => now()],
            ['divisi_id' => 1, 'name' => 'Pimpinan Kas Pakisaji', 'created_at' => now()],

            // PE
            ['divisi_id' => 2, 'name' => 'HRD', 'created_at' => now()],
            ['divisi_id' => 2, 'name' => 'Audit', 'created_at' => now()],
            ['divisi_id' => 2, 'name' => 'Risk', 'created_at' => now()],
            ['divisi_id' => 2, 'name' => 'Legal', 'created_at' => now()],

            // Team Leader
            ['divisi_id' => 3, 'name' => 'Team Leader Lending', 'created_at' => now()],
            ['divisi_id' => 3, 'name' => 'Team Leader Funding', 'created_at' => now()],
            ['divisi_id' => 3, 'name' => 'Team Leader Operational', 'created_at' => now()],
            ['divisi_id' => 3, 'name' => 'Team Leader Collection', 'created_at' => now()],
            ['divisi_id' => 3, 'name' => 'Team Leader Corporate', 'created_at' => now()],
            ['divisi_id' => 3, 'name' => 'Team Leader Digital Marketing', 'created_at' => now()],

            // Lending
            ['divisi_id' => 4, 'name' => 'AO Sertif', 'created_at' => now()],
            ['divisi_id' => 4, 'name' => 'AO Remun', 'created_at' => now()],
            ['divisi_id' => 4, 'name' => 'AO Multiguna', 'created_at' => now()],
            ['divisi_id' => 4, 'name' => 'AO Haji', 'created_at' => now()],
            ['divisi_id' => 4, 'name' => 'AO Corporate', 'created_at' => now()],
            ['divisi_id' => 4, 'name' => 'AO Digital Marketing', 'created_at' => now()],

            //Funding
            ['divisi_id' => 5, 'name' => 'Funding Corporate', 'created_at' => now()],
            ['divisi_id' => 5, 'name' => 'Funding Retail', 'created_at' => now()],

            //Operational
            ['divisi_id' => 6, 'name' => 'Accounting', 'created_at' => now()],
            ['divisi_id' => 6, 'name' => 'Kasir', 'created_at' => now()],
            ['divisi_id' => 6, 'name' => 'Tabungan', 'created_at' => now()],
            ['divisi_id' => 6, 'name' => 'Customer Service', 'created_at' => now()],
            ['divisi_id' => 6, 'name' => 'Driver', 'created_at' => now()],
            ['divisi_id' => 6, 'name' => 'OB', 'created_at' => now()],
            ['divisi_id' => 6, 'name' => 'Security', 'created_at' => now()],

            //Collection
            ['divisi_id' => 7, 'name' => 'Remedial', 'created_at' => now()],
            ['divisi_id' => 7, 'name' => 'Field Collection', 'created_at' => now()],

            //DGM
            ['divisi_id' => 8, 'name' => 'Productions', 'created_at' => now()],
            ['divisi_id' => 8, 'name' => 'Branding', 'created_at' => now()],
            ['divisi_id' => 8, 'name' => 'IT Developer', 'created_at' => now()],

            // ['divisi_id' => 1, 'name' => 'PE Manrisk'],
            // ['divisi_id' => 1, 'name' => 'PE Audit Internal'],


            // ['divisi_id' => 3, 'name' => 'Team Leader Operasional'],
            // ['divisi_id' => 3, 'name' => 'Admin Tabungan'],
            // ['divisi_id' => 3, 'name' => 'Customer Service'],
            // ['divisi_id' => 3, 'name' => 'Kasir'],
            // ['divisi_id' => 3, 'name' => 'Accounting'],
            // ['divisi_id' => 3, 'name' => 'Admin Kredit'],
            // ['divisi_id' => 3, 'name' => 'Admin Jaminan'],

            // ['divisi_id' => 6, 'name' => 'Team Leader Analis'],
            // ['divisi_id' => 6, 'name' => 'Analis'],

            // ['divisi_id' => 6, 'name' => 'Team Leader Collection'],
            // ['divisi_id' => 6, 'name' => 'Remedial'],
            // ['divisi_id' => 6, 'name' => 'Field Call'],
            // ['divisi_id' => 6, 'name' => 'Recovery'],

            // ['divisi_id' => 5, 'name' => 'Team Leader Account Officer'],
            // ['divisi_id' => 5, 'name' => 'Account Officer'],

            // ['divisi_id' => 4, 'name' => 'Team Leader Digital Marketing'],
            // ['divisi_id' => 4, 'name' => 'Account Officer Digital'],

            // ['divisi_id' => 5, 'name' => 'Account Officer Haji'],
            // ['divisi_id' => 5, 'name' => 'Account Officer Bidan'],

            // ['divisi_id' => 5, 'name' => 'OB'],
            // ['divisi_id' => 5, 'name' => 'Driver'],
            // ['divisi_id' => 5, 'name' => 'Security'],

        ]);
    }
}
