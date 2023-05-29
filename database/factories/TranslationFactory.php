<?php

namespace Sirthxalot\Laravel\I18n\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Sirthxalot\Laravel\I18n\Models\Translation;

class TranslationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Translation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'locale' => fake()->locale(),
            'key' => fake()->word(),
            'message' => fake()->text(),
        ];
    }
}
