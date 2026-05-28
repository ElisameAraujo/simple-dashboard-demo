<?php

namespace Database\Factories\Demo;

use App\Models\Demo\SearchPost;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<SearchPost>
 */
class SearchPostFactory extends Factory
{
    protected $model = SearchPost::class;

    public function definition(): array
    {
        $title = fake()->randomElement([
            'Guia pratico para organizar o painel administrativo',
            'Como preparar imagens para posts de blog',
            'Checklist para publicar conteudo com seguranca',
            'Boas praticas para medir visitas em paginas internas',
            'Otimizando o fluxo editorial com categorias',
        ]);

        $suffix = fake()->unique()->numberBetween(100, 9999);

        return [
            'title' => "{$title} #{$suffix}",
            'subtitle' => fake()->sentence(7),
            'excerpt' => fake()->paragraph(2),
            'body' => fake()->paragraphs(5, true),
            'cover_image' => fake()->randomElement([
                'https://images.unsplash.com/photo-1499750310107-5fef28a66643',
                'https://images.unsplash.com/photo-1516321318423-f06f85e504b3',
                'https://images.unsplash.com/photo-1497366754035-f200968a6e72',
            ]),
            'slug' => Str::slug($title)."-{$suffix}",
            'status' => 'published',
            'published_at' => fake()->dateTimeBetween('-90 days', 'now'),
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
