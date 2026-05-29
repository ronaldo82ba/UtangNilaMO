<?php

namespace App\Http\Controllers;

use App\Models\Borrower;
use Illuminate\Http\Request;

class BorrowerController extends Controller
{
    public function index(Request $request)
    {
        $borrowers = $request->user()
            ->borrowers()
            ->latest()
            ->get()
            ->each(function ($borrower) {
                $borrower->total_utang_value = $borrower->utangEntries()->sum('amount');
                $borrower->total_payments_value = $borrower->payments()->sum('amount');
            });

        return view('borrowers.index', compact('borrowers'));
    }

    public function create()
    {
        return view('borrowers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_number' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        $request->user()->borrowers()->create($validated);

        return redirect()->route('borrowers.index')
            ->with('success', 'Borrower added successfully.');
    }

    public function show(Request $request, Borrower $borrower)
    {
        abort_unless($borrower->user_id === $request->user()->id, 403);

        $borrower->load(['utangEntries' => fn ($q) => $q->latest('date'), 'payments' => fn ($q) => $q->latest('date')]);

        return view('borrowers.show', compact('borrower'));
    }

    public function edit(Request $request, Borrower $borrower)
    {
        abort_unless($borrower->user_id === $request->user()->id, 403);

        return view('borrowers.edit', compact('borrower'));
    }

    public function update(Request $request, Borrower $borrower)
    {
        abort_unless($borrower->user_id === $request->user()->id, 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_number' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        $borrower->update($validated);

        return redirect()->route('borrowers.show', $borrower)
            ->with('success', 'Borrower updated successfully.');
    }

    public function destroy(Request $request, Borrower $borrower)
    {
        abort_unless($borrower->user_id === $request->user()->id, 403);

        $borrower->delete();

        return redirect()->route('borrowers.index')
            ->with('success', 'Borrower deleted successfully.');
    }
}
