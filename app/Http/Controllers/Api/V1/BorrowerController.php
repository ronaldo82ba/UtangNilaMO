<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreBorrowerRequest;
use App\Http\Requests\Api\V1\UpdateBorrowerRequest;
use App\Http\Resources\Api\V1\BorrowerResource;
use App\Models\Borrower;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BorrowerController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $borrowers = $request->user()
            ->borrowers()
            ->with(['utangEntries', 'payments'])
            ->latest()
            ->get();

        return BorrowerResource::collection($borrowers);
    }

    public function store(StoreBorrowerRequest $request): JsonResponse
    {
        $borrower = $request->user()->borrowers()->create($request->validated());
        $borrower->load(['utangEntries', 'payments']);

        return (new BorrowerResource($borrower))
            ->additional(['message' => 'Borrower created successfully.'])
            ->response()
            ->setStatusCode(201);
    }

    public function show(Request $request, Borrower $borrower): BorrowerResource
    {
        abort_unless($borrower->user_id === $request->user()->id, 403);

        $borrower->load([
            'utangEntries' => fn ($q) => $q->latest('date'),
            'payments' => fn ($q) => $q->latest('date'),
        ]);

        return new BorrowerResource($borrower);
    }

    public function update(UpdateBorrowerRequest $request, Borrower $borrower): JsonResponse
    {
        $borrower->update($request->validated());

        return (new BorrowerResource($borrower->fresh()->load(['utangEntries', 'payments'])))
            ->additional(['message' => 'Borrower updated successfully.'])
            ->response();
    }

    public function destroy(Request $request, Borrower $borrower): JsonResponse
    {
        abort_unless($borrower->user_id === $request->user()->id, 403);

        $borrower->delete();

        return response()->json(['message' => 'Borrower deleted successfully.']);
    }
}
