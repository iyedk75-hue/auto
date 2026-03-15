<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class CandidatePaymentController extends Controller
{
    public function index(Request $request): View
    {
        return view('candidate.payments', [
            'payments' => $request->user()->payments()->latest()->paginate(10),
        ]);
    }
}
