<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $techOptions = ['Laravel', 'Livewire', 'PHP', 'MySQL', 'Tailwind CSS', 'Vue.js', 'Alpine.js', 'Redis'];

        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(3),
            'image' => null,
            'url' => $this->faker->optional()->url(),
            'github_url' => $this->faker->optional()->url(),
            'technologies' => $this->faker->randomElements($techOptions, rand(2, 4)),
            'status' => $this->faker->randomElement(['in_progress', 'completed', 'archived']),
            'order' => $this->faker->numberBetween(1, 10),
            'featured' => $this->faker->boolean(30),
        ];
    }
}
