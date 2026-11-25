<?php

namespace App\Services;

use App\Models\Loan;
use Carbon\Carbon;

class LoanFineCalculator
{
    /**
     * Sync overdue status and late fines (Rp 2.000 / day) for all active/overdue loans.
     */
    public static function syncAll(): int
    {
        $today = now()->startOfDay();
        $updated = 0;

        $loans = Loan::query()
            ->whereIn('status', ['active', 'overdue'])
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', $today->toDateString())
            ->get();

        foreach ($loans as $loan) {
            $dueDate = Carbon::parse($loan->due_date)->startOfDay();
            $daysLate = (int) $dueDate->diffInDays($today);
            $fine = $daysLate * Loan::DAILY_FINE;

            if ($loan->status !== 'overdue' || $loan->fine_amount !== $fine) {
                $loan->update([
                    'status' => 'overdue',
                    'fine_amount' => $fine,
                ]);
                $updated++;
            }
        }

        return $updated;
    }
}
