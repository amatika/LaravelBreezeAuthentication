<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use PDF;

class PDFController extends Controller
{
    public function generatePDF($id)
    {
        $user = User::findOrFail($id);

        $pdf = PDF::loadView('pdf.user_report', compact('user'));

       // return $pdf->download('user_report.pdf');
       return $pdf->stream('user_report.pdf');//allows one to preview the report
    }
}