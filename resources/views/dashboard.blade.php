<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500 uppercase tracking-wide">Total Borrowers</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ $totalBorrowers }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500 uppercase tracking-wide">Total Utang</div>
                    <div class="mt-2 text-3xl font-bold text-red-600">&#8369;{{ number_format($totalUtang, 2) }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500 uppercase tracking-wide">Total Payments</div>
                    <div class="mt-2 text-3xl font-bold text-green-600">&#8369;{{ number_format($totalPayments, 2) }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500 uppercase tracking-wide">Outstanding Balance</div>
                    <div class="mt-2 text-3xl font-bold {{ $totalBalance > 0 ? 'text-orange-600' : 'text-green-600' }}">
                        &#8369;{{ number_format($totalBalance, 2) }}
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Borrowers</h3>
                        <a href="{{ route('borrowers.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                            + Add Borrower
                        </a>
                    </div>

                    @if($borrowers->isEmpty())
                        <p class="text-gray-500">No borrowers yet. Add your first borrower to get started.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utang Entries</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payments</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($borrowers as $borrower)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                                <a href="{{ route('borrowers.show', $borrower) }}" class="text-indigo-600 hover:text-indigo-900">
                                                    {{ $borrower->name }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $borrower->utang_entries_count }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $borrower->payments_count }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <a href="{{ route('borrowers.show', $borrower) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
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
