<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $delivery_modalities = ['O', 'B', 'I'];
        $semesters = ['W1', 'W2', 'S1', 'S2'];

        return [
            'course_code' => $this->faker->asciify('****'),
            'course_num' => $this->faker->randomNumber($nbDigits = 3, $strict = true),
            'delivery_modality' => $delivery_modalities[array_rand($delivery_modalities)],
            'year' => $this->faker->year(),
            'semester' => $semesters[array_rand($semesters)],
            'course_title' => $this->faker->asciify('********************'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'assigned' => 1,
            'type' => 'unassigned',

        ];
    }
}
