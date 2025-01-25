<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Absence>
 */
class AbsenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $startHours = [
            '08:00',
            '08:55',
            '09:50',
            '10:45',
            '11:15',
            '12:10',
            '13:05',
        ];

        $endtHours = [
            '08:55',
            '09:50',
            '10:45',
            '11:15',
            '12:10',
            '13:05',
            '14:00',
        ];

        $start = fake()->numberBetween(0, 6);
        $end = fake()->numberBetween($start+1, 6);

        return [
            'user_id' => fake()->numberBetween(1, 2),
            'comment' => fake()->text(),
            'startHour' => $startHours[$start],
            'endHour' => $endtHours[$end],
        ];
    }
}
