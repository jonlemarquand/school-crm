<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $gender = rand(0,1);
        $gender = $gender == 1 ? 'male' : 'female';
        $gender_letter = $gender == 'male' ? 'm' : 'f';

        return [
            'surname' => fake()->lastName(),
            'firstname' => fake()->firstName($gender),
            'middlenames' => fake()->firstName($gender) . ' ' . fake()->firstName($gender),
            'gender' => $gender_letter,
            'date_of_birth' => fake()->dateTimeBetween('-18 years', '-11 years'),
            'form' => rand(1,10),
        ];
    }
}
