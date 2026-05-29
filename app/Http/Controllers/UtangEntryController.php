<?php

namespace App\Http\Controllers;

use App\Models\Borrower;
use App\Models\UtangEntry;
use Illuminate\Http\Request;

class UtangEntryController extends Controller
{
    public function index(Request $request, Borrower $borrower)
    {
        abort_unless($borrower->user_id === $request->user()->id, 403);

        $utangEntries = $borrower->utangEntries()->latest('date')->get();

        return view('borrowers.utang', compact('borrower', 'utangEntries'));
    }

    public function store(Request $request, Borrower $borrower)
    {
        abort_unless($borrower->user_id === $request->user()->id, 403);

        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
        ]);

        $validated['user_id'] = $request->user()->id;

        $borrower->utangEntries()->create($validated);

        return redirect()->route('borrowers.utang', $borrower)
            ->with('success', 'Utang entry added successfully.');
    }

    public function destroy(Request $request, Borrower $borrower, UtangEntry $utang)
    {
        abort_unless($borrower->user_id === $request->user()->id, 403);
        abort_unless($utang->borrower_id === $borrower->id, 403);

        $utang->delete();

        return redirect()->route('borrowers.utang', $borrower)
            ->with('success', 'Utang entry deleted.');
    }
}
