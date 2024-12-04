<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use App\Models\Profile;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ExportCutiController extends Controller
{
    public function exportPdf(Cuti $cuti)
    {
        $startDate = Carbon::parse($cuti->start_date); // Konversi string menjadi Carbon
        $endDate = Carbon::parse($cuti->end_date);

        $totalDays = $startDate->diffInDays($endDate) + 1;

        $idHrd = $cuti->approval_by_hrd_id;
        $idDireksi = $cuti->approval_by_direksi_id;

        $signatureHrd = Profile::find($idHrd)->signature;
        $signatureDireksi = Profile::find($idDireksi)->signature;

        $name = $cuti->user->id;

        // Tentukan path tujuan
        $path = storage_path('app/public/cuti/' . $name . '-' . $startDate->format('Y-m-d') . '/cuti.pdf');

        $pdf = Pdf::loadView('cuti.pdf', ['cuti' => $cuti, 'jumlahHari' => $totalDays, 'signatureHrd' => $signatureHrd, 'signatureDireksi' => $signatureDireksi])->setPaper('a4', 'landscape');
        return $pdf->save('cuti.pdf')->stream();
    }
}
