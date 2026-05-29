<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\StatementResource;
use App\Models\Borrower;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatementController extends Controller
{
    public function show(Request $request, Borrower $borrower): StatementResource
    {
        abort_unless($borrower->user_id === $request->user()->id, 403);

        $borrower->load(['utangEntries', 'payments']);

        return new StatementResource(['borrower' => $borrower]);
    }

    public function text(Request $request, Borrower $borrower): \Illuminate\Http\Response
    {
        abort_unless($borrower->user_id === $request->user()->id, 403);

        $utangEntries = $borrower->utangEntries()->orderBy('date')->get();
        $payments = $borrower->payments()->orderBy('date')->get();

        $totalUtang = (float) $utangEntries->sum('amount');
        $totalPayments = (float) $payments->sum('amount');
        $balance = $totalUtang - $totalPayments;

        $lines = [];
        $lines[] = str_repeat('=', 50);
        $lines[] = str_pad('UTANGNILAMO - STATEMENT OF ACCOUNT', 50, ' ', STR_PAD_BOTH);
        $lines[] = str_repeat('=', 50);
        $lines[] = "Borrower: {$borrower->name}";
        if ($borrower->contact_number) {
            $lines[] = "Contact:  {$borrower->contact_number}";
        }
        if ($borrower->address) {
            $lines[] = "Address:  {$borrower->address}";
        }
        $lines[] = 'Date:     ' . now()->format('M d, Y h:i A');
        $lines[] = str_repeat('-', 50);
        $lines[] = sprintf('%-12s %-8s %-14s %12s', 'DATE', 'TYPE', 'DESCRIPTION', 'AMOUNT');
        $lines[] = str_repeat('-', 50);

        $runningBalance = 0;

        foreach ($utangEntries as $entry) {
            $runningBalance += (float) $entry->amount;
            $lines[] = sprintf(
                '%-12s %-8s %-14s %12s',
                $entry->date->format('M d, Y'),
                'UTANG',
                mb_substr($entry->description, 0, 14),
                number_format($entry->amount, 2)
            );
        }

        foreach ($payments as $payment) {
            $runningBalance -= (float) $payment->amount;
            $desc = $payment->notes ? mb_substr($payment->notes, 0, 14) : 'Payment';
            $lines[] = sprintf(
                '%-12s %-8s %-14s %12s',
                $payment->date->format('M d, Y'),
                'PAYMENT',
                $desc,
                '-' . number_format($payment->amount, 2)
            );
        }

        $lines[] = str_repeat('-', 50);
        $lines[] = sprintf('%36s %12s', 'TOTAL UTANG:', number_format($totalUtang, 2));
        $lines[] = sprintf('%36s %12s', 'TOTAL PAYMENTS:', number_format($totalPayments, 2));
        $lines[] = sprintf('%36s %12s', 'BALANCE:', number_format($balance, 2));
        $lines[] = str_repeat('=', 50);

        return response(implode("\n", $lines), 200)
            ->header('Content-Type', 'text/plain; charset=utf-8');
    }

    public function escpos(Request $request, Borrower $borrower): \Illuminate\Http\Response
    {
        abort_unless($borrower->user_id === $request->user()->id, 403);

        $utangEntries = $borrower->utangEntries()->orderBy('date')->get();
        $payments = $borrower->payments()->orderBy('date')->get();

        $totalUtang = (float) $utangEntries->sum('amount');
        $totalPayments = (float) $payments->sum('amount');
        $balance = $totalUtang - $totalPayments;

        $esc = "\x1B";
        $gs = "\x1D";

        $commands = '';
        $commands .= $esc . "@"; // Initialize
        $commands .= $esc . "a\x01"; // Center align
        $commands .= $esc . "!\x10"; // Double height
        $commands .= "UTANGNILAMO\n";
        $commands .= $esc . "!\x00"; // Normal
        $commands .= "Statement of Account\n";
        $commands .= str_repeat('-', 32) . "\n";
        $commands .= $esc . "a\x00"; // Left align
        $commands .= "Borrower: {$borrower->name}\n";
        if ($borrower->contact_number) {
            $commands .= "Contact: {$borrower->contact_number}\n";
        }
        $commands .= 'Date: ' . now()->format('M d, Y') . "\n";
        $commands .= str_repeat('-', 32) . "\n";

        foreach ($utangEntries as $entry) {
            $desc = mb_substr($entry->description, 0, 18);
            $amt = number_format($entry->amount, 2);
            $commands .= sprintf("%-18s %12s\n", $desc, $amt);
        }

        if ($payments->isNotEmpty()) {
            $commands .= str_repeat('-', 32) . "\n";
            $commands .= "PAYMENTS:\n";
            foreach ($payments as $payment) {
                $desc = $payment->notes ? mb_substr($payment->notes, 0, 18) : 'Payment';
                $amt = number_format($payment->amount, 2);
                $commands .= sprintf("%-18s %12s\n", $desc, '-' . $amt);
            }
        }

        $commands .= str_repeat('=', 32) . "\n";
        $commands .= $esc . "!\x10"; // Double height
        $commands .= sprintf("BALANCE: P%s\n", number_format($balance, 2));
        $commands .= $esc . "!\x00"; // Normal
        $commands .= "\n\n\n";
        $commands .= $gs . "V\x00"; // Cut paper

        return response($commands, 200)
            ->header('Content-Type', 'application/octet-stream')
            ->header('Content-Disposition', "attachment; filename=\"statement-{$borrower->id}.bin\"");
    }
}
