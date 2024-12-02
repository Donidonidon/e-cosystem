<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportCutiController extends Controller
{
    public function exportPdf(Cuti $cuti)
    {
        $pdf = Pdf::loadView('cuti.pdf', ['cuti' => $cuti]);
        return $pdf->download('cuti-' . $cuti->id . '.pdf');
    }
}
