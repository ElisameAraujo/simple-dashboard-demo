<?php

namespace Database\Factories\Demo;

use App\Models\Demo\SearchProduct;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<SearchProduct>
 */
class SearchProductFactory extends Factory
{
    protected $model = SearchProduct::class;

    public function definition(): array
    {
        $name = fake()->randomElement([
            'Kit de organizacao para escritorio',
            'Planner semanal de produtividade',
            'Suporte ergonomico para notebook',
            'Luminaria LED para mesa de trabalho',
            'Caderno premium para planejamento',
        ]);

        $suffix = fake()->unique()->numberBetween(100, 9999);

        return [
            'name' => "{$name} #{$suffix}",
            'description' => fake()->paragraph(3),
            'image' => fake()->randomElement([
                'https://images.unsplash.com/photo-1516321497487-e288fb19713f',
                'https://images.unsplash.com/photo-1524758631624-e2822e304c36',
                'https://images.unsplash.com/photo-1452860606245-08befc0ff44b',
            ]),
            'slug' => Str::slug($name)."-{$suffix}",
            'price' => fake()->randomFloat(2, 29, 499),
            'status' => 'published',
            'published_at' => fake()->dateTimeBetween('-120 days', 'now'),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (): array => [
            'status' => 'draft',
            'published_at' => null,
        ]);
    }
}
