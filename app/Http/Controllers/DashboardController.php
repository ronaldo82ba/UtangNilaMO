<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $borrowers = $user->borrowers()->withCount('utangEntries', 'payments')->get();

        $totalBorrowers = $borrowers->count();
        $totalUtang = $user->utangEntries()->sum('amount');
        $totalPayments = $user->payments()->sum('amount');
        $totalBalance = $totalUtang - $totalPayments;

        return view('dashboard', compact(
            'borrowers',
            'totalBorrowers',
            'totalUtang',
            'totalPayments',
            'totalBalance'
        ));
    }
}
