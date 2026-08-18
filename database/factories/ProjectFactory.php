<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(3);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => $this->faker->paragraph(),
            'technologies' => json_encode(['Laravel', 'PHP']),
            'url' => $this->faker->url(),
            'github_url' => $this->faker->url(),
            'status' => 'termine',
            'completed_at' => $this->faker->date(),
            'is_featured' => false,
            'order' => 0,
        ];
    }

    public function featured(): static
    {
        return $this->state(fn () => ['is_featured' => true]);
    }

    public function archived(): static
    {
        return $this->state(fn () => ['status' => 'archive']);
    }
}
