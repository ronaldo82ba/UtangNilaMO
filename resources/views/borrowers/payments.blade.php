<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Payments ni {{ $borrower->name }}
            </h2>
            <a href="{{ route('borrowers.show', $borrower) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">&larr; Back to Borrower</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Record Payment</h3>
                <form method="POST" action="{{ route('borrowers.payments.store', $borrower) }}">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <x-input-label for="amount" :value="__('Amount (₱)')" />
                            <x-text-input id="amount" name="amount" type="number" step="0.01" min="0.01" class="mt-1 block w-full" :value="old('amount')" required />
                            <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="date" :value="__('Date')" />
                            <x-text-input id="date" name="date" type="date" class="mt-1 block w-full" :value="old('date', date('Y-m-d'))" required />
                            <x-input-error :messages="$errors->get('date')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="utang_entry_id" :value="__('For Utang (optional)')" />
                            <select id="utang_entry_id" name="utang_entry_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">— General Payment —</option>
                                @foreach($utangEntries as $entry)
                                    <option value="{{ $entry->id }}" {{ old('utang_entry_id') == $entry->id ? 'selected' : '' }}>
                                        {{ $entry->description }} (&#8369;{{ number_format($entry->amount, 2) }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('utang_entry_id')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="notes" :value="__('Notes (optional)')" />
                            <x-text-input id="notes" name="notes" type="text" class="mt-1 block w-full" :value="old('notes')" />
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>
                    </div>
                    <div class="mt-4">
                        <x-primary-button>{{ __('Record Payment') }}</x-primary-button>
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment History</h3>
                @if($payments->isEmpty())
                    <p class="text-gray-500">No payments yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">For Utang</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($payments as $payment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $payment->date->format('M d, Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-green-600 font-medium">&#8369;{{ number_format($payment->amount, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $payment->utangEntry?->description ?? '—' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $payment->notes ?? '—' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <form method="POST" action="{{ route('borrowers.payments.destroy', [$borrower, $payment]) }}" onsubmit="return confirm('Delete this payment?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td class="px-6 py-3 text-right font-semibold text-gray-700">Total:</td>
                                    <td class="px-6 py-3 text-right font-bold text-green-700">&#8369;{{ number_format($payments->sum('amount'), 2) }}</td>
                                    <td colspan="3"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
