<?php

namespace Database\Seeders;

use App\Models\Listings;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@renthouse.test',
            'role'  => 'admin'
        ]);
        
        $user     = User::factory(10)->create();
        $listings = Listings::factory(10)->create();

        Transaction::factory(10)
            ->state(
                new Sequence(fn(Sequence $sequence) => [
                    'user_id'    => $user->random(),
                    'listing_id' => $listings->random(),
                ])
            )
        ->create();
    }
}
