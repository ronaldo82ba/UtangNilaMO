<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Borrowers') }}
            </h2>
            <a href="{{ route('borrowers.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                + Add Borrower
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($borrowers->isEmpty())
                        <p class="text-gray-500 text-center py-8">No borrowers yet. Click "Add Borrower" to get started.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Utang</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Paid</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($borrowers as $borrower)
                                        @php
                                            $balance = $borrower->total_utang_value - $borrower->total_payments_value;
                                        @endphp
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <a href="{{ route('borrowers.show', $borrower) }}" class="font-medium text-indigo-600 hover:text-indigo-900">
                                                    {{ $borrower->name }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $borrower->contact_number ?? '—' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-red-600 font-medium">&#8369;{{ number_format($borrower->total_utang_value, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-green-600 font-medium">&#8369;{{ number_format($borrower->total_payments_value, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right font-bold {{ $balance > 0 ? 'text-orange-600' : 'text-green-600' }}">
                                                &#8369;{{ number_format($balance, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm space-x-2">
                                                <a href="{{ route('borrowers.show', $borrower) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                                <a href="{{ route('borrowers.utang', $borrower) }}" class="text-red-600 hover:text-red-900">Utang</a>
                                                <a href="{{ route('borrowers.payments', $borrower) }}" class="text-green-600 hover:text-green-900">Payments</a>
                                                <a href="{{ route('borrowers.statement', $borrower) }}" class="text-gray-600 hover:text-gray-900">Statement</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
