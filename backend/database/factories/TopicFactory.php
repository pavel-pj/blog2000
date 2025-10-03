<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Topic;
use App\Models\Subject;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Topic>
 */
class TopicFactory extends Factory
{
   protected $model = Topic::class;

    public function definition(): array
    {
        return [
            'subject_id' => Subject::factory(),  
            'name' => $this->faker->word(),
            
        ];
    }
}
