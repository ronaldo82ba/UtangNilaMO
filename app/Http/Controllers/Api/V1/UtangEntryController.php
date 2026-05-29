<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreUtangEntryRequest;
use App\Http\Requests\Api\V1\UpdateUtangEntryRequest;
use App\Http\Resources\Api\V1\UtangEntryResource;
use App\Models\Borrower;
use App\Models\UtangEntry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UtangEntryController extends Controller
{
    public function index(Request $request, Borrower $borrower): AnonymousResourceCollection
    {
        abort_unless($borrower->user_id === $request->user()->id, 403);

        $entries = $borrower->utangEntries()->latest('date')->get();

        return UtangEntryResource::collection($entries)->additional([
            'borrower_id' => $borrower->id,
            'borrower_name' => $borrower->name,
            'total' => (float) $entries->sum('amount'),
        ]);
    }

    public function store(StoreUtangEntryRequest $request, Borrower $borrower): JsonResponse
    {
        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id;

        $entry = $borrower->utangEntries()->create($validated);

        return (new UtangEntryResource($entry))
            ->additional(['message' => 'Utang entry added successfully.'])
            ->response()
            ->setStatusCode(201);
    }

    public function show(Request $request, Borrower $borrower, UtangEntry $utang): UtangEntryResource
    {
        abort_unless($borrower->user_id === $request->user()->id, 403);
        abort_unless($utang->borrower_id === $borrower->id, 404);

        $utang->load('payments');

        return new UtangEntryResource($utang);
    }

    public function update(UpdateUtangEntryRequest $request, Borrower $borrower, UtangEntry $utang): JsonResponse
    {
        abort_unless($utang->borrower_id === $borrower->id, 404);

        $utang->update($request->validated());

        return (new UtangEntryResource($utang->fresh()))
            ->additional(['message' => 'Utang entry updated successfully.'])
            ->response();
    }

    public function destroy(Request $request, Borrower $borrower, UtangEntry $utang): JsonResponse
    {
        abort_unless($borrower->user_id === $request->user()->id, 403);
        abort_unless($utang->borrower_id === $borrower->id, 404);

        $utang->delete();

        return response()->json(['message' => 'Utang entry deleted successfully.']);
    }
}
