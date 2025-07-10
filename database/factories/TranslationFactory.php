<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Translation>
 */
class TranslationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
             'locale' => $this->faker->randomElement(['en', 'fr', 'es']),
        'tag' => $this->faker->randomElement(['web', 'mobile', 'desktop']),
        'key' => $this->faker->unique()->slug,
        'value' => $this->faker->sentence,
        ];
    }
}
