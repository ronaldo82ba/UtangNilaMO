<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StorePaymentRequest;
use App\Http\Requests\Api\V1\UpdatePaymentRequest;
use App\Http\Resources\Api\V1\PaymentResource;
use App\Models\Borrower;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PaymentController extends Controller
{
    public function index(Request $request, Borrower $borrower): AnonymousResourceCollection
    {
        abort_unless($borrower->user_id === $request->user()->id, 403);

        $payments = $borrower->payments()->with('utangEntry')->latest('date')->get();

        return PaymentResource::collection($payments)->additional([
            'borrower_id' => $borrower->id,
            'borrower_name' => $borrower->name,
            'total' => (float) $payments->sum('amount'),
        ]);
    }

    public function store(StorePaymentRequest $request, Borrower $borrower): JsonResponse
    {
        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id;

        $payment = $borrower->payments()->create($validated);

        return (new PaymentResource($payment))
            ->additional(['message' => 'Payment recorded successfully.'])
            ->response()
            ->setStatusCode(201);
    }

    public function show(Request $request, Borrower $borrower, Payment $payment): PaymentResource
    {
        abort_unless($borrower->user_id === $request->user()->id, 403);
        abort_unless($payment->borrower_id === $borrower->id, 404);

        return new PaymentResource($payment);
    }

    public function update(UpdatePaymentRequest $request, Borrower $borrower, Payment $payment): JsonResponse
    {
        abort_unless($payment->borrower_id === $borrower->id, 404);

        $payment->update($request->validated());

        return (new PaymentResource($payment->fresh()))
            ->additional(['message' => 'Payment updated successfully.'])
            ->response();
    }

    public function destroy(Request $request, Borrower $borrower, Payment $payment): JsonResponse
    {
        abort_unless($borrower->user_id === $request->user()->id, 403);
        abort_unless($payment->borrower_id === $borrower->id, 404);

        $payment->delete();

        return response()->json(['message' => 'Payment deleted successfully.']);
    }
}
