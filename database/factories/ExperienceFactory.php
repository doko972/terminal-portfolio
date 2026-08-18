<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Experience>
 */
class ExperienceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->jobTitle(),
            'company' => $this->faker->company(),
            'location' => $this->faker->city(),
            'type' => 'work',
            'start_date' => '2020-01-01',
            'end_date' => '2022-01-01',
            'is_current' => false,
            'description' => $this->faker->paragraph(),
            'tasks' => ['Tâche A', 'Tâche B'],
            'technologies' => ['PHP', 'Laravel'],
            'is_visible' => true,
            'order' => 0,
        ];
    }

    public function current(): static
    {
        return $this->state(fn () => ['is_current' => true, 'end_date' => null]);
    }

    public function hidden(): static
    {
        return $this->state(fn () => ['is_visible' => false]);
    }
}
