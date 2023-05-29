<?php

namespace Sirthxalot\Laravel\I18n\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Sirthxalot\Laravel\I18n\Models\Language;

class LanguageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Language::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return ['locale' => fake()->locale()];
    }
}
