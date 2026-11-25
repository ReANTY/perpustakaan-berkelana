<?php

namespace App\Http\Controllers;

use App\Services\LoanFineCalculator;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        LoanFineCalculator::syncAll();

        $user = $request->user();

        $loans = $user->loans()
            ->with('book')
            ->latest()
            ->get();

        $activeLoans = $loans->whereIn('status', ['pending_pickup', 'active', 'overdue']);
        $historyLoans = $loans->whereIn('status', ['returned', 'cancelled']);

        $activeCount = $loans->where('status', 'active')->count();
        $overdueCount = $loans->where('status', 'overdue')->count();
        $totalUnpaidFines = $loans->where('is_fine_paid', false)->sum('fine_amount');

        return view('dashboard', compact(
            'activeLoans',
            'historyLoans',
            'activeCount',
            'overdueCount',
            'totalUnpaidFines'
        ));
    }
}
