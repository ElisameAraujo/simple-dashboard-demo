<?php

namespace Database\Seeders;

use App\Models\Demo\SearchPost;
use App\Models\Demo\SearchProduct;
use Illuminate\Database\Seeder;

class SearchDemoSeeder extends Seeder
{
    public function run(): void
    {
        SearchPost::query()->delete();
        SearchProduct::query()->delete();

        SearchPost::factory()->create([
            'title' => 'Publicando posts com imagens no editor',
            'subtitle' => 'Um exemplo completo para busca por conteudo editorial',
            'excerpt' => 'Veja como um post publicado aparece no Spotlight da demo.',
            'body' => 'Este conteudo demonstra a pesquisa por posts, imagens, editor de texto, categorias e resumo dentro do painel administrativo.',
            'slug' => 'publicando-posts-com-imagens-no-editor',
        ]);

        SearchPost::factory()->create([
            'title' => 'Relatorio de visitas para produtos populares',
            'subtitle' => 'Entenda como visitas podem apoiar decisoes do painel',
            'excerpt' => 'Um post de exemplo sobre metricas, popularidade e visitas.',
            'body' => 'A busca pode encontrar posts pelo titulo, subtitulo, resumo e corpo, respeitando pesos configurados no arquivo search.php.',
            'slug' => 'relatorio-de-visitas-para-produtos-populares',
        ]);

        SearchPost::factory()->count(18)->create();
        SearchPost::factory()->count(4)->draft()->create();

        SearchProduct::factory()->create([
            'name' => 'Kit de midias para blog e loja',
            'description' => 'Produto demonstrativo para validar a busca por midias, imagens, loja, blog e conteudo visual.',
            'slug' => 'kit-de-midias-para-blog-e-loja',
            'price' => 149.90,
        ]);

        SearchProduct::factory()->create([
            'name' => 'Painel de produtividade para administradores',
            'description' => 'Exemplo de produto publicado que aparece no Spotlight quando o usuario pesquisa por painel, admin ou produtividade.',
            'slug' => 'painel-de-produtividade-para-administradores',
            'price' => 229.90,
        ]);

        SearchProduct::factory()->count(18)->create();
        SearchProduct::factory()->count(4)->draft()->create();
    }
}
