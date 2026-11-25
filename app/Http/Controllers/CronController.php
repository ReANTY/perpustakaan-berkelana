<?php

namespace App\Http\Controllers;

use App\Services\LoanFineCalculator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CronController extends Controller
{
    public function syncFines(Request $request): JsonResponse
    {
        $secret = config('app.cron_secret');

        if (! $secret || $request->query('token') !== $secret) {
            return response()->json(['ok' => false, 'message' => 'Unauthorized'], 403);
        }

        $updated = LoanFineCalculator::syncAll();

        return response()->json([
            'ok' => true,
            'updated' => $updated,
            'synced_at' => now()->toIso8601String(),
        ]);
    }
}
