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

        // Start in index 0 for 8:00 and end in 7 for 14:00
        $hours = [
            '08:00',
            '08:55',
            '09:50',
            '10:45',
            '11:15',
            '12:10',
            '13:05',
            '14:00',
        ];


        // Start in index 0 for monday and end in 4 for friday
        $days = [
            'monday',
            'tuesday',
            'wednesday',
            'thursday',
            'friday',
        ];

        $shifts = [
            "morning",
            "afternoon"
        ];

        // Random hour
        $hour = fake()->numberBetween(0, count($hours)-2);

        // Random shift
        $shift = fake()->numberBetween(0, count($shifts)-1);



        // Return the array with the data
        return [
            'user_id' => fake()->numberBetween(1, 2),
            'comment' => fake()->text(),
            'startHour' => $hours[$hour],
            'endHour' => $hours[$hour+1],
            'hourNumber' => $hour,
            'dayNumber' => fake()->numberBetween(0, count($days)-1),
            'week' => fake()->numberBetween(0, 3),
            'shift' => $shifts[$shift],
        ];
    }
}
