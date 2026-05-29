<?php

namespace App\Http\Controllers;

use App\Models\Borrower;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request, Borrower $borrower)
    {
        abort_unless($borrower->user_id === $request->user()->id, 403);

        $payments = $borrower->payments()->with('utangEntry')->latest('date')->get();
        $utangEntries = $borrower->utangEntries()->get();

        return view('borrowers.payments', compact('borrower', 'payments', 'utangEntries'));
    }

    public function store(Request $request, Borrower $borrower)
    {
        abort_unless($borrower->user_id === $request->user()->id, 403);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'utang_entry_id' => 'nullable|exists:utang_entries,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        $validated['user_id'] = $request->user()->id;

        $borrower->payments()->create($validated);

        return redirect()->route('borrowers.payments', $borrower)
            ->with('success', 'Payment recorded successfully.');
    }

    public function destroy(Request $request, Borrower $borrower, Payment $payment)
    {
        abort_unless($borrower->user_id === $request->user()->id, 403);
        abort_unless($payment->borrower_id === $borrower->id, 403);

        $payment->delete();

        return redirect()->route('borrowers.payments', $borrower)
            ->with('success', 'Payment deleted.');
    }
}
