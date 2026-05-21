<?php

namespace App\Helpers;

use Faker\Factory;
use Faker\Generator;
use Illuminate\Support\Str;

class HTMLHelper
{
    protected Generator $faker;
    protected string $output = '';

    /**
     * `make`:
     * Starts a fluent HTML generator instance.
     * @return static
     */
    public static function make(): static
    {
        return new static();
    }

    /**
     * `heading`:
     * Appends a heading element with random text.
     * @param int|string|null $level Heading level from 1 to 6. Invalid values fallback to 2.
     * @return static
     */
    public function heading(int | string | null $level = 2): static
    {
        $level = $this->headingLevel($level);
        $this->output .= '<h' . $level . '>' . $this->titleWords() . '</h' . $level . '>';

        return $this;
    }

    /**
     * `headingWithLink`:
     * Appends a heading element containing random text and an internal anchor.
     * @param int|string|null $level Heading level from 1 to 6. Invalid values fallback to 2.
     * @return static
     */
    public function headingWithLink(int | string | null $level = 2): static
    {
        $level = $this->headingLevel($level);
        $heading = $this->titleWords(2, 3) . ' <a href="#">' . $this->titleWords(2, 3) . '</a> ' . $this->titleWords(2, 3);
        $this->output .= '<h' . $level . '>' . $heading . '</h' . $level . '>';

        return $this;
    }

    /**
     * `emptyParagraph`:
     * Appends an empty paragraph element.
     * @return static
     */
    public function emptyParagraph(): static
    {
        $this->output .= '<p></p>';

        return $this;
    }

    /**
     * `paragraphs`:
     * Appends one or more paragraphs with optional random links.
     * @param int $count Number of paragraphs to generate. Values below 1 fallback to 1.
     * @param bool $withRandomLinks Whether each paragraph should include a random anchor.
     * @return static
     */
    public function paragraphs(int $count = 1, bool $withRandomLinks = false): static
    {
        $count = $this->positiveInteger($count);

        if ($withRandomLinks) {
            $this->output .= '<p>' . collect($this->faker()->paragraphs($count))->map(function ($paragraph) {
                $paragraphWords = explode(' ', $paragraph);
                $key = array_rand($paragraphWords);

                $paragraphWords[$key] = '<a href="' . $this->faker()->url() . '">' . $this->faker()->words(rand(3, 8), true) . '</a>';

                return implode(' ', $paragraphWords);
            })->implode('</p><p>') . '</p>';

            return $this;
        } else {
            $this->output .= '<p>' . collect($this->faker()->paragraphs($count))->implode('</p><p>') . '</p>';
        }

        return $this;
    }

    /**
     * `unorderedList`:
     * Appends an unordered list with random words.
     * @param int $count Number of list items. Values below 1 fallback to 1.
     * @return static
     */
    public function unorderedList(int $count = 1): static
    {
        $count = $this->positiveInteger($count);
        $this->output .= '<ul><li>' . collect($this->faker()->words($count))->implode('</li><li>') . '</li></ul>';

        return $this;
    }

    /**
     * `orderedList`:
     * Appends an ordered list with random words.
     * @param int $count Number of list items. Values below 1 fallback to 1.
     * @return static
     */
    public function orderedList(int $count = 1): static
    {
        $count = $this->positiveInteger($count);
        $this->output .= '<ol><li>' . collect($this->faker()->words($count))->implode('</li><li>') . '</li></ol>';

        return $this;
    }

    /**
     * `image`:
     * Appends an image element with a fake image URL.
     * @param int|null $width Image width. Invalid values fallback to 640.
     * @param int|null $height Image height. Invalid values fallback to 480.
     * @return static
     */
    public function image(?int $width = 640, ?int $height = 480): static
    {
        $width = $this->positiveInteger($width, 640);
        $height = $this->positiveInteger($height, 480);
        $this->output .= '<img src="' . $this->faker()->imageUrl($width, $height) . '" alt="' . $this->faker()->sentence . '" width="' . $width . '" height="' . $height . '">';

        return $this;
    }

    /**
     * `link`:
     * Appends an anchor element with a fake URL.
     * @return static
     */
    public function link(): static
    {
        $this->output .= '<a href="' . $this->faker()->url() . '">' . $this->faker()->words(rand(3, 8), true) . '</a>';

        return $this;
    }

    /**
     * `video`:
     * Appends a fake YouTube or Vimeo iframe.
     * @param string|null $provider Video provider. Accepts "youtube" or "vimeo".
     * @param int|null $width Iframe width. Invalid values fallback to 640.
     * @param int|null $height Iframe height. Invalid values fallback to 480.
     * @return static
     */
    public function video(?string $provider = 'youtube', ?int $width = 640, ?int $height = 480): static
    {
        $provider = $provider === 'vimeo' ? 'vimeo' : 'youtube';
        $width = $this->positiveInteger($width, 640);
        $height = $this->positiveInteger($height, 480);

        if ($provider === 'vimeo') {
            $this->output .= '<iframe width="' . $width . '" height="' . $height . '" src="https://player.vimeo.com/video/' . $this->faker()->numberBetween(1, 999999999) . '" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>';
        } else {
            $this->output .= '<iframe width="' . $width . '" height="' . $height . '" src="https://www.youtube.com/embed/' . $this->faker()->regexify('[a-zA-Z0-9_-]{11}') . '" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
        }

        return $this;
    }

    /**
     * `details`:
     * Appends a details element with a summary and body.
     * @return static
     */
    public function details(): static
    {
        $this->output .= '<details><summary>' . $this->faker()->sentence() . '</summary><div>' . $this->faker()->paragraph() . '</div></details>';

        return $this;
    }

    /**
     * `code`:
     * Appends a preformatted code block.
     * @param string|null $className CSS class applied to the pre element. Empty values fallback to "hljs".
     * @return static
     */
    public function code(?string $className = 'hljs'): static
    {
        $className = $className ?: 'hljs';
        $this->output .= "<pre class=\"{$className}\"><code>export default function testComponent({\n\nstate,\n\n}) {\n\nreturn {\n\nstate,\n\ninit: function () {\n\n// Initialise the Alpine component here, if you need to.\n\n},\n\n}\n\n}</code></pre>";

        return $this;
    }

    /**
     * `blockquote`:
     * Appends a blockquote element with a fake sentence.
     * @return static
     */
    public function blockquote(): static
    {
        $this->output .= '<blockquote>' . $this->faker()->sentence() . '</blockquote>';

        return $this;
    }

    /**
     * `hr`:
     * Appends a horizontal rule element.
     * @return static
     */
    public function hr(): static
    {
        $this->output .= '<hr>';

        return $this;
    }

    /**
     * `br`:
     * Appends a line break element.
     * @return static
     */
    public function br(): static
    {
        $this->output .= '<br>';

        return $this;
    }

    /**
     * `table`:
     * Appends a table with consistent header and body column counts.
     * @return static
     */
    public function table(): static
    {
        $columns = rand(3, 6);
        $headers = collect($this->faker()->words($columns))
            ->map(fn(string $word) => Str::title($word))
            ->implode('</th><th>');

        $rows = collect(range(1, 2))->map(function () use ($columns) {
            return '<tr><td>' . collect($this->faker()->words($columns))->implode('</td><td>') . '</td></tr>';
        })->implode('');

        $this->output .= '<table><thead><tr><th>' . $headers . '</th></tr></thead><tbody>' . $rows . '</tbody></table>';

        return $this;
    }

    /**
     * `grid`:
     * Appends a responsive grid where each value represents a column span.
     * @param array $cols Column spans for each generated grid item.
     * @return static
     */
    public function grid(array $cols = [1, 1, 1]): static
    {
        $cols = $this->columnSpans($cols);
        $totalColumns = array_sum($cols);

        $this->output .= '<div class="grid" data-type="responsive" data-cols="' . $totalColumns . '" style="grid-template-columns: repeat(' . $totalColumns . ', 1fr);" data-stack-at="md">';

        foreach ($cols as $col) {
            $this->output .= '<div class="grid__column" data-col-span="' . $col . '" style="grid-column: span ' . $col . ';"><h2>' . $this->titleWords() . '</h2><p>' . $this->faker()->paragraph() . '</p></div>';
        }

        $this->output .= '</div>';

        return $this;
    }

    /**
     * `generate`:
     * Returns the generated HTML string.
     * @return string
     */
    public function generate(): string
    {
        return $this->output;
    }

    /**
     * Normalizes heading levels to valid HTML heading tags.
     */
    private function headingLevel(int|string|null $level): int
    {
        $level = filter_var($level, FILTER_VALIDATE_INT, [
            'options' => [
                'min_range' => 1,
                'max_range' => 6,
            ],
        ]);

        return $level === false ? 2 : $level;
    }

    /**
     * Returns title-cased fake words.
     */
    private function titleWords(int $min = 3, int $max = 8): string
    {
        return Str::title($this->faker()->words(rand($min, $max), true));
    }

    /**
     * Normalizes numeric options that must be greater than zero.
     */
    private function positiveInteger(?int $value, int $fallback = 1): int
    {
        return $value !== null && $value > 0 ? $value : $fallback;
    }

    /**
     * Normalizes grid column spans.
     */
    private function columnSpans(array $cols): array
    {
        $spans = collect($cols)
            ->map(fn($col) => filter_var($col, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]))
            ->filter(fn($col) => $col !== false)
            ->values()
            ->all();

        return $spans ?: [1, 1, 1];
    }

    /**
     * Returns the Faker generator, initializing it lazily when needed.
     */
    private function faker(): Generator
    {
        if (!isset($this->faker)) {
            $this->faker = Factory::create();
        }

        return $this->faker;
    }
}
