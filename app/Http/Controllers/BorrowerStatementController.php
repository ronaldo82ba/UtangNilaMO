<?php

namespace App\Http\Controllers;

use App\Models\Borrower;
use Illuminate\Http\Request;

class BorrowerStatementController extends Controller
{
    public function show(Request $request, Borrower $borrower)
    {
        abort_unless($borrower->user_id === $request->user()->id, 403);

        $utangEntries = $borrower->utangEntries()->orderBy('date')->get();
        $payments = $borrower->payments()->orderBy('date')->get();

        $transactions = collect();

        foreach ($utangEntries as $entry) {
            $transactions->push([
                'date' => $entry->date,
                'type' => 'Utang',
                'description' => $entry->description,
                'amount' => $entry->amount,
                'payment' => 0,
            ]);
        }

        foreach ($payments as $payment) {
            $transactions->push([
                'date' => $payment->date,
                'type' => 'Payment',
                'description' => $payment->notes ?? 'Payment',
                'amount' => 0,
                'payment' => $payment->amount,
            ]);
        }

        $transactions = $transactions->sortBy('date')->values();

        $totalUtang = $utangEntries->sum('amount');
        $totalPayments = $payments->sum('amount');
        $balance = $totalUtang - $totalPayments;

        return view('borrowers.statement', compact(
            'borrower',
            'transactions',
            'totalUtang',
            'totalPayments',
            'balance'
        ));
    }
}
