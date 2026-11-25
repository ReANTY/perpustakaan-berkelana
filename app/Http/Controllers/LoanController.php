<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoanController extends Controller
{
    public function confirmation(Request $request, Loan $loan): View
    {
        $user = $request->user();
        abort_unless($loan->user_id === $user->id || $user->isAdmin(), 403);

        $loan->load(['book', 'user']);

        return view('loans.confirmation', compact('loan'));
    }
}
