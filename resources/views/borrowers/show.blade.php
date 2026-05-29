<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $borrower->name }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('borrowers.edit', $borrower) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white text-sm font-medium rounded-md hover:bg-yellow-600 transition">
                    Edit
                </a>
                <form method="POST" action="{{ route('borrowers.destroy', $borrower) }}" onsubmit="return confirm('Are you sure you want to delete this borrower? All utang entries and payments will also be deleted.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500 uppercase tracking-wide">Total Utang</div>
                    <div class="mt-2 text-2xl font-bold text-red-600">&#8369;{{ number_format($borrower->total_utang, 2) }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500 uppercase tracking-wide">Total Payments</div>
                    <div class="mt-2 text-2xl font-bold text-green-600">&#8369;{{ number_format($borrower->total_payments, 2) }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-sm text-gray-500 uppercase tracking-wide">Balance</div>
                    <div class="mt-2 text-2xl font-bold {{ $borrower->balance > 0 ? 'text-orange-600' : 'text-green-600' }}">
                        &#8369;{{ number_format($borrower->balance, 2) }}
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Borrower Details</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm text-gray-500">Contact Number</dt>
                        <dd class="text-gray-900">{{ $borrower->contact_number ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Address</dt>
                        <dd class="text-gray-900">{{ $borrower->address ?? '—' }}</dd>
                    </div>
                    <div class="md:col-span-2">
                        <dt class="text-sm text-gray-500">Notes</dt>
                        <dd class="text-gray-900">{{ $borrower->notes ?? '—' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="flex flex-wrap gap-3 mb-6">
                <a href="{{ route('borrowers.utang', $borrower) }}" class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition">
                    Manage Utang
                </a>
                <a href="{{ route('borrowers.payments', $borrower) }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition">
                    Manage Payments
                </a>
                <a href="{{ route('borrowers.statement', $borrower) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition">
                    View Statement
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Utang Entries</h3>
                    @if($borrower->utangEntries->isEmpty())
                        <p class="text-gray-500">No utang entries yet.</p>
                    @else
                        <div class="space-y-3">
                            @foreach($borrower->utangEntries->take(5) as $entry)
                                <div class="flex justify-between items-center border-b pb-2">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $entry->description }}</div>
                                        <div class="text-sm text-gray-500">{{ $entry->date->format('M d, Y') }}</div>
                                    </div>
                                    <div class="text-red-600 font-semibold">&#8369;{{ number_format($entry->amount, 2) }}</div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Payments</h3>
                    @if($borrower->payments->isEmpty())
                        <p class="text-gray-500">No payments yet.</p>
                    @else
                        <div class="space-y-3">
                            @foreach($borrower->payments->take(5) as $payment)
                                <div class="flex justify-between items-center border-b pb-2">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $payment->notes ?? 'Payment' }}</div>
                                        <div class="text-sm text-gray-500">{{ $payment->date->format('M d, Y') }}</div>
                                    </div>
                                    <div class="text-green-600 font-semibold">&#8369;{{ number_format($payment->amount, 2) }}</div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
