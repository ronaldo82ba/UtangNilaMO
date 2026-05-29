<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Utang ni {{ $borrower->name }}
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
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Add Utang Entry</h3>
                <form method="POST" action="{{ route('borrowers.utang.store', $borrower) }}">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <x-text-input id="description" name="description" type="text" class="mt-1 block w-full" :value="old('description')" required />
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>
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
                    </div>
                    <div class="mt-4">
                        <x-primary-button>{{ __('Add Utang') }}</x-primary-button>
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Utang Entries</h3>
                @if($utangEntries->isEmpty())
                    <p class="text-gray-500">No utang entries yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($utangEntries as $entry)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $entry->date->format('M d, Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900">{{ $entry->description }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-red-600 font-medium">&#8369;{{ number_format($entry->amount, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <form method="POST" action="{{ route('borrowers.utang.destroy', [$borrower, $entry]) }}" onsubmit="return confirm('Delete this utang entry?')">
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
                                    <td colspan="2" class="px-6 py-3 text-right font-semibold text-gray-700">Total:</td>
                                    <td class="px-6 py-3 text-right font-bold text-red-700">&#8369;{{ number_format($utangEntries->sum('amount'), 2) }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
