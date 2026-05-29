<?php

namespace Database\Seeders;

use App\Models\Borrower;
use App\Models\Payment;
use App\Models\User;
use App\Models\UtangEntry;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@utangnilamo.com',
            'password' => Hash::make('password'),
        ]);

        $borrower1 = Borrower::create([
            'user_id' => $user->id,
            'name' => 'Juan Dela Cruz',
            'contact_number' => '09171234567',
            'address' => 'Manila, Philippines',
            'notes' => 'Suki borrower',
        ]);

        $borrower2 = Borrower::create([
            'user_id' => $user->id,
            'name' => 'Maria Santos',
            'contact_number' => '09181234567',
            'address' => 'Quezon City, Philippines',
        ]);

        $entry1 = UtangEntry::create([
            'user_id' => $user->id,
            'borrower_id' => $borrower1->id,
            'description' => 'Bigas 25kg',
            'amount' => 1500.00,
            'date' => now()->subDays(10),
        ]);

        $entry2 = UtangEntry::create([
            'user_id' => $user->id,
            'borrower_id' => $borrower1->id,
            'description' => 'Grocery items',
            'amount' => 850.50,
            'date' => now()->subDays(5),
        ]);

        UtangEntry::create([
            'user_id' => $user->id,
            'borrower_id' => $borrower2->id,
            'description' => 'School supplies',
            'amount' => 2200.00,
            'date' => now()->subDays(7),
        ]);

        Payment::create([
            'user_id' => $user->id,
            'borrower_id' => $borrower1->id,
            'utang_entry_id' => $entry1->id,
            'amount' => 500.00,
            'date' => now()->subDays(3),
            'notes' => 'Partial payment for bigas',
        ]);

        Payment::create([
            'user_id' => $user->id,
            'borrower_id' => $borrower2->id,
            'amount' => 1000.00,
            'date' => now()->subDays(2),
            'notes' => 'First payment',
        ]);
    }
}
