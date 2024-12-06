<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cuti;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ExportCutiController extends Controller
{
    public function exportPdf(Cuti $cuti)
    {
        $totalDays = $cuti->jumlah_hari;

        $idLeader = $cuti->approval_by_leader_id;
        $idHrd = $cuti->approval_by_hrd_id;
        $idDireksi = $cuti->approval_by_direksi_id;

        $signatureLeader = Profile::find($idLeader)->signature;
        $signatureHrd = Profile::find($idHrd)->signature;
        $signatureDireksi = Profile::find($idDireksi)->signature;

        $leaderFirstName = Profile::find($idLeader)->first_name;
        $hrdFirstName = Profile::find($idHrd)->first_name;
        $direksiFirstName = Profile::find($idDireksi)->first_name;

        $leaderLastName = Profile::find($idLeader)->last_name;
        $hrdLastName = Profile::find($idHrd)->last_name;
        $direksiLastName = Profile::find($idDireksi)->last_name;

        $leaderName = $leaderFirstName . ' ' . $leaderLastName;
        $hrdName = $hrdFirstName . ' ' . $hrdLastName;
        $direksiName = $direksiFirstName . ' ' . $direksiLastName;

        $name = $cuti->user->name;
        $nameSlug = Str::slug($name);

        // Generate PDF
        $pdf = Pdf::loadView('cuti.pdf', [
            'cuti' => $cuti,
            'jumlahHari' => $totalDays,
            'signatureLeader' => $signatureLeader,
            'signatureHrd' => $signatureHrd,
            'signatureDireksi' => $signatureDireksi,
            'leaderName' => $leaderName,
            'hrdName' => $hrdName,
            'direksiName' => $direksiName
        ])->setPaper('a5', 'portrait');

        // Tentukan nama file dan path untuk menyimpan PDF
        $fileName = 'cuti_' . $cuti->id . '-' . $nameSlug .  '.pdf';
        $path = storage_path('app/public/pdfs/' . $fileName);  // Path di dalam folder storage/public/pdfs

        // Simpan PDF ke path yang telah ditentukan
        $pdf->save($path);

        $cuti->path_cuti_pdf = 'pdfs/' . $fileName;
        $cuti->save();

        // Return PDF untuk di-stream
        return $pdf->stream();
    }
}
