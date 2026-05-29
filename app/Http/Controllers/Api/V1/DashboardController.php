<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\BorrowerResource;
use App\Http\Resources\Api\V1\DashboardSummaryResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();

        $totalBorrowers = $user->borrowers()->count();
        $totalUtang = (float) $user->utangEntries()->sum('amount');
        $totalPayments = (float) $user->payments()->sum('amount');

        $summary = new DashboardSummaryResource([
            'total_borrowers' => $totalBorrowers,
            'total_utang' => $totalUtang,
            'total_payments' => $totalPayments,
            'total_balance' => $totalUtang - $totalPayments,
        ]);

        $recentBorrowers = $user->borrowers()
            ->with(['utangEntries', 'payments'])
            ->latest()
            ->take(10)
            ->get();

        return response()->json([
            'summary' => $summary,
            'recent_borrowers' => BorrowerResource::collection($recentBorrowers),
        ]);
    }
}
