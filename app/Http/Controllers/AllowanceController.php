<?php

namespace App\Http\Controllers;
use App\Models\Allowance;
use Illuminate\Http\Request;

class AllowanceController extends Controller
{
    public function index()
    {
        $allowances = Allowance::all();

        return view('allowances.index', compact('allowances'));
    }
}

