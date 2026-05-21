<?php

namespace Tests\Unit;

use App\Helpers\HTMLHelper;
use Tests\TestCase;

class HTMLHelperTest extends TestCase
{
    public function test_make_builds_chainable_html(): void
    {
        $html = HTMLHelper::make()
            ->heading(2)
            ->paragraphs()
            ->generate();

        $this->assertStringStartsWith('<h2>', $html);
        $this->assertStringContainsString('</h2><p>', $html);
        $this->assertStringEndsWith('</p>', $html);
    }

    public function test_direct_instances_initialize_faker_lazily(): void
    {
        $html = (new HTMLHelper())
            ->heading(2)
            ->generate();

        $this->assertStringStartsWith('<h2>', $html);
        $this->assertStringEndsWith('</h2>', $html);
    }

    public function test_heading_level_is_normalized_to_valid_html_heading(): void
    {
        $this->assertStringStartsWith('<h2>', HTMLHelper::make()->heading(null)->generate());
        $this->assertStringStartsWith('<h2>', HTMLHelper::make()->heading(0)->generate());
        $this->assertStringStartsWith('<h2>', HTMLHelper::make()->heading(7)->generate());
        $this->assertStringStartsWith('<h2>', HTMLHelper::make()->heading('foo')->generate());
        $this->assertStringStartsWith('<h3>', HTMLHelper::make()->heading('3')->generate());
    }

    public function test_heading_with_link_preserves_anchor_markup_and_spacing(): void
    {
        $html = HTMLHelper::make()
            ->headingWithLink(2)
            ->generate();

        $this->assertStringStartsWith('<h2>', $html);
        $this->assertStringContainsString(' <a href="#">', $html);
        $this->assertStringContainsString('</a> ', $html);
        $this->assertStringNotContainsString('<A', $html);
        $this->assertStringNotContainsString('Href', $html);
    }

    public function test_table_uses_consistent_column_counts(): void
    {
        $html = HTMLHelper::make()
            ->table()
            ->generate();

        $headerCount = substr_count($html, '<th>');

        preg_match_all('/<tbody><tr>(.*?)<\/tr><tr>(.*?)<\/tr><\/tbody>/', $html, $matches);

        $this->assertGreaterThanOrEqual(3, $headerCount);
        $this->assertCount(1, $matches[0]);
        $this->assertSame($headerCount, substr_count($matches[1][0], '<td>'));
        $this->assertSame($headerCount, substr_count($matches[2][0], '<td>'));
    }

    public function test_grid_respects_column_spans(): void
    {
        $html = HTMLHelper::make()
            ->grid([1, 2, 1])
            ->generate();

        $this->assertStringContainsString('data-cols="4"', $html);
        $this->assertStringContainsString('grid-template-columns: repeat(4, 1fr);', $html);
        $this->assertStringContainsString('data-col-span="2"', $html);
        $this->assertStringContainsString('grid-column: span 2;', $html);
    }
}
