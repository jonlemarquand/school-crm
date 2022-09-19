<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
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
            'title' => fake()->title($gender),
            'surname' => fake()->lastName(),
            'firstname' => fake()->firstName($gender),
            'middlenames' => fake()->firstName($gender) . ' ' . fake()->firstName($gender),
            'gender' => $gender_letter,
        ];
    }
}
