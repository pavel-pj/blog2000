<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Subject;
use App\Models\User;

class SubjectFactory extends Factory
{
    protected $model = Subject::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // This creates a new user for each subject
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
        ];
    }
    /*
    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }
        */
}