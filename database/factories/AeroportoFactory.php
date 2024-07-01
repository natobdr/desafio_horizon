<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Aeroporto>
 */
class AeroportoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'codigo_iata' => $this->faker->unique()->lexify('???'),
            'nome' => $this->faker->city . ' Airport',
            'cidade_id' => Cidade::factory(),
        ];
    }
}
