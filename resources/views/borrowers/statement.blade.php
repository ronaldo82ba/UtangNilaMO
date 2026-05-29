<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between print:hidden">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Statement of Account &mdash; {{ $borrower->name }}
            </h2>
            <div class="flex gap-2">
                <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                    Print Statement
                </button>
                <a href="{{ route('borrowers.show', $borrower) }}" class="text-indigo-600 hover:text-indigo-900 text-sm flex items-center">&larr; Back</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            {{-- Print Header (hidden on screen, visible on print) --}}
            <div class="hidden print:block mb-8 text-center">
                <h1 class="text-2xl font-bold">UtangNilaMO</h1>
                <h2 class="text-xl mt-2">Statement of Account</h2>
                <p class="text-lg mt-1 font-semibold">{{ $borrower->name }}</p>
                @if($borrower->contact_number)
                    <p class="text-sm text-gray-600">Contact: {{ $borrower->contact_number }}</p>
                @endif
                @if($borrower->address)
                    <p class="text-sm text-gray-600">Address: {{ $borrower->address }}</p>
                @endif
                <p class="text-sm text-gray-500 mt-1">Generated: {{ now()->format('F d, Y h:i A') }}</p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg print:shadow-none">
                <div class="p-6">
                    <div class="grid grid-cols-3 gap-4 mb-6 print:mb-4">
                        <div class="text-center p-4 bg-red-50 rounded-lg print:bg-white print:border">
                            <div class="text-sm text-gray-500 uppercase">Total Utang</div>
                            <div class="text-xl font-bold text-red-600">&#8369;{{ number_format($totalUtang, 2) }}</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg print:bg-white print:border">
                            <div class="text-sm text-gray-500 uppercase">Total Payments</div>
                            <div class="text-xl font-bold text-green-600">&#8369;{{ number_format($totalPayments, 2) }}</div>
                        </div>
                        <div class="text-center p-4 {{ $balance > 0 ? 'bg-orange-50' : 'bg-green-50' }} rounded-lg print:bg-white print:border">
                            <div class="text-sm text-gray-500 uppercase">Balance</div>
                            <div class="text-xl font-bold {{ $balance > 0 ? 'text-orange-600' : 'text-green-600' }}">
                                &#8369;{{ number_format($balance, 2) }}
                            </div>
                        </div>
                    </div>

                    @if($transactions->isEmpty())
                        <p class="text-gray-500 text-center py-8">No transactions found.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Utang</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Running Balance</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @php $runningBalance = 0; @endphp
                                    @foreach($transactions as $txn)
                                        @php
                                            $runningBalance += $txn['amount'] - $txn['payment'];
                                        @endphp
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($txn['date'])->format('M d, Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $txn['type'] === 'Utang' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                                    {{ $txn['type'] }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $txn['description'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm {{ $txn['amount'] > 0 ? 'text-red-600 font-medium' : 'text-gray-400' }}">
                                                {{ $txn['amount'] > 0 ? '₱' . number_format($txn['amount'], 2) : '—' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm {{ $txn['payment'] > 0 ? 'text-green-600 font-medium' : 'text-gray-400' }}">
                                                {{ $txn['payment'] > 0 ? '₱' . number_format($txn['payment'], 2) : '—' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold {{ $runningBalance > 0 ? 'text-orange-600' : 'text-green-600' }}">
                                                &#8369;{{ number_format($runningBalance, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-100 font-bold">
                                    <tr>
                                        <td colspan="3" class="px-6 py-3 text-right text-gray-700">TOTALS:</td>
                                        <td class="px-6 py-3 text-right text-red-700">&#8369;{{ number_format($totalUtang, 2) }}</td>
                                        <td class="px-6 py-3 text-right text-green-700">&#8369;{{ number_format($totalPayments, 2) }}</td>
                                        <td class="px-6 py-3 text-right {{ $balance > 0 ? 'text-orange-700' : 'text-green-700' }}">&#8369;{{ number_format($balance, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-6 text-center print:hidden">
                <button onclick="window.print()" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                    Print Statement
                </button>
            </div>

        </div>
    </div>

    <style>
        @media print {
            nav, .print\:hidden { display: none !important; }
            .print\:block { display: block !important; }
            .print\:shadow-none { box-shadow: none !important; }
            .print\:bg-white { background-color: white !important; }
            .print\:border { border: 1px solid #e5e7eb !important; }
            body { background: white !important; }
        }
    </style>
</x-app-layout>
