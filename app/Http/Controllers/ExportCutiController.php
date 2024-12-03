<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ExportCutiController extends Controller
{
    public function exportPdf(Cuti $cuti)
    {
        // $pdf = App::make('dompdf.wrapper');
        // $pdf->loadHTML('<h1>Test</h1>');
        // return $pdf->stream();

        $startDate = Carbon::parse($cuti->start_date); // Konversi string menjadi Carbon
        $endDate = Carbon::parse($cuti->end_date);

        $totalDays = $startDate->diffInDays($endDate) + 1;

        $pdf = Pdf::loadView('cuti.pdf', ['cuti' => $cuti, 'jumlahHari' => $totalDays])->setPaper('a4', 'landscape');
        return $pdf->stream();
    }
}
