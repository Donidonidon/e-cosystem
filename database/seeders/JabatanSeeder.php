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
            ['divisi_id' => 1, 'jabatan' => 'Direktur Utama'],
            ['divisi_id' => 1, 'jabatan' => 'Direktur Bisnis'],
            ['divisi_id' => 1, 'jabatan' => 'Pimpinan Cabang'],
            ['divisi_id' => 1, 'jabatan' => 'Pimpinan Kas Tlogomas'],
            ['divisi_id' => 1, 'jabatan' => 'Pimpinan Kas Pakisaji'],

            // PE
            ['divisi_id' => 2, 'jabatan' => 'HRD'],
            ['divisi_id' => 2, 'jabatan' => 'Audit'],
            ['divisi_id' => 2, 'jabatan' => 'Risk'],
            ['divisi_id' => 2, 'jabatan' => 'Legal'],

            // Team Leader
            ['divisi_id' => 3, 'jabatan' => 'Team Leader Lending'],
            ['divisi_id' => 3, 'jabatan' => 'Team Leader Funding'],
            ['divisi_id' => 3, 'jabatan' => 'Team Leader Operational'],
            ['divisi_id' => 3, 'jabatan' => 'Team Leader Collection'],
            ['divisi_id' => 3, 'jabatan' => 'Team Leader Corporate'],
            ['divisi_id' => 3, 'jabatan' => 'Team Leader Digital Marketing'],

            // Lending
            ['divisi_id' => 4, 'jabatan' => 'AO Sertif'],
            ['divisi_id' => 4, 'jabatan' => 'AO Remun'],
            ['divisi_id' => 4, 'jabatan' => 'AO Multiguna'],
            ['divisi_id' => 4, 'jabatan' => 'AO Haji'],
            ['divisi_id' => 4, 'jabatan' => 'AO Corporate'],
            ['divisi_id' => 4, 'jabatan' => 'AO Digital Marketing'],

            //Funding
            ['divisi_id' => 5, 'jabatan' => 'Funding Corporate'],
            ['divisi_id' => 5, 'jabatan' => 'Funding Retail'],

            //Operational
            ['divisi_id' => 6, 'jabatan' => 'Accounting'],
            ['divisi_id' => 6, 'jabatan' => 'Kasir'],
            ['divisi_id' => 6, 'jabatan' => 'Tabungan'],
            ['divisi_id' => 6, 'jabatan' => 'Customer Service'],
            ['divisi_id' => 6, 'jabatan' => 'Driver'],
            ['divisi_id' => 6, 'jabatan' => 'OB'],
            ['divisi_id' => 6, 'jabatan' => 'Security'],

            //Collection
            ['divisi_id' => 7, 'jabatan' => 'Remedial'],
            ['divisi_id' => 7, 'jabatan' => 'Field Collection'],

            //DGM
            ['divisi_id' => 8, 'jabatan' => 'Productions'],
            ['divisi_id' => 8, 'jabatan' => 'Branding'],
            ['divisi_id' => 8, 'jabatan' => 'IT Developer'],

            // ['divisi_id' => 1, 'jabatan' => 'PE Manrisk'],
            // ['divisi_id' => 1, 'jabatan' => 'PE Audit Internal'],


            // ['divisi_id' => 3, 'jabatan' => 'Team Leader Operasional'],
            // ['divisi_id' => 3, 'jabatan' => 'Admin Tabungan'],
            // ['divisi_id' => 3, 'jabatan' => 'Customer Service'],
            // ['divisi_id' => 3, 'jabatan' => 'Kasir'],
            // ['divisi_id' => 3, 'jabatan' => 'Accounting'],
            // ['divisi_id' => 3, 'jabatan' => 'Admin Kredit'],
            // ['divisi_id' => 3, 'jabatan' => 'Admin Jaminan'],

            // ['divisi_id' => 6, 'jabatan' => 'Team Leader Analis'],
            // ['divisi_id' => 6, 'jabatan' => 'Analis'],

            // ['divisi_id' => 6, 'jabatan' => 'Team Leader Collection'],
            // ['divisi_id' => 6, 'jabatan' => 'Remedial'],
            // ['divisi_id' => 6, 'jabatan' => 'Field Call'],
            // ['divisi_id' => 6, 'jabatan' => 'Recovery'],

            // ['divisi_id' => 5, 'jabatan' => 'Team Leader Account Officer'],
            // ['divisi_id' => 5, 'jabatan' => 'Account Officer'],

            // ['divisi_id' => 4, 'jabatan' => 'Team Leader Digital Marketing'],
            // ['divisi_id' => 4, 'jabatan' => 'Account Officer Digital'],

            // ['divisi_id' => 5, 'jabatan' => 'Account Officer Haji'],
            // ['divisi_id' => 5, 'jabatan' => 'Account Officer Bidan'],

            // ['divisi_id' => 5, 'jabatan' => 'OB'],
            // ['divisi_id' => 5, 'jabatan' => 'Driver'],
            // ['divisi_id' => 5, 'jabatan' => 'Security'],

        ]);
    }
}
