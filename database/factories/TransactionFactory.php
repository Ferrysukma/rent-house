<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start_date     = fake()->dateTimeThisMonth();
        return [
            'start_date'    => $start_date,
            'end_date'      => Carbon::createFromDate($start_date)->addDays(fake()->numberBetween(1,5)),
            'status'        => fake()->randomElement(['waiting','approved','canceled']),
        ];
    }
}
