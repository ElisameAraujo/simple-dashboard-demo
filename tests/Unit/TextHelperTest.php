<?php

namespace Tests\Unit;

use App\Helpers\TextHelper;
use Tests\TestCase;

class TextHelperTest extends TestCase
{
    public function test_limits_ignore_html_tags(): void
    {
        $this->assertSame('Olá mundo...', TextHelper::limitByCharacters('<p>Olá mundo bonito</p>', 10));
        $this->assertSame('Olá mundo...', TextHelper::limitByWords('<p>Olá mundo bonito</p>', 2));
    }

    public function test_counts_words_and_characters_with_unicode_text(): void
    {
        $this->assertSame(3, TextHelper::countWords('Olá ação coração'));
        $this->assertSame(9, TextHelper::countCharacters('Olá mundo'));
        $this->assertSame(8, TextHelper::countCharacters("Olá \n mundo", true));
    }

    public function test_cleans_and_sanitizes_text(): void
    {
        $this->assertSame('Olá mundo', TextHelper::removePunctuation('Olá, mundo!'));
        $this->assertSame('Olá mundo', TextHelper::stripHTML('<p>Olá <strong>mundo</strong></p>'));
        $this->assertSame('Olá mundo', TextHelper::cleanText("  Olá \n\t mundo  "));
        $this->assertSame('Olá mundo', TextHelper::normalizeWhitespace("  Olá \n\t mundo  "));
        $this->assertSame('Olá mundo', TextHelper::removeLineBreaks("Olá\nmundo"));
        $this->assertSame('Olá mundo', TextHelper::sanitize("<p>Olá</p>\n mundo"));
    }

    public function test_transforms_text_for_slugs_excerpts_and_plain_values(): void
    {
        $this->assertSame('acao-coracao', TextHelper::slug('Ação Coração', '-', 'pt-BR'));
        $this->assertSame('kesha-and-ac-dc', TextHelper::slug('Ke$ha & AC/DC', '-', 'en-US'));
        $this->assertSame('Ação coração...', TextHelper::excerpt('<p>Ação coração brasileira</p>', 12));
        $this->assertSame('acao', TextHelper::removeAccents('ação'));
        $this->assertSame('Rock and Roll - 2026', TextHelper::convertSpecialCharacters('Rock & Roll / 2026'));
        $this->assertSame('61999990000', TextHelper::onlyNumbers('(61) 99999-0000'));
    }

    public function test_formats_names_and_initials_by_locale(): void
    {
        $this->assertSame('Maria da Silva e Souza', TextHelper::capitalizeNames('maria da silva e souza', 'pt-BR'));
        $this->assertSame('Maria da Silva', TextHelper::normalizeNames('  maria   da silva  ', 'pt-BR'));
        $this->assertSame('Maria', TextHelper::firstName('maria da silva', 'pt-BR'));
        $this->assertSame('MS', TextHelper::initials('maria da silva', 2, 'pt-BR'));
        $this->assertSame('', TextHelper::initials('maria da silva', 0, 'pt-BR'));
    }

    public function test_dashboard_display_helpers_return_consistent_values(): void
    {
        $this->assertSame('—', TextHelper::emptyFallback(null));
        $this->assertSame('—', TextHelper::emptyFallback('   '));
        $this->assertSame('0', TextHelper::emptyFallback(0));
        $this->assertSame('Indisponível', TextHelper::emptyFallback('', 'Indisponível'));

        $this->assertSame(0, TextHelper::readingTime(''));
        $this->assertSame(1, TextHelper::readingTime('Uma leitura curta', 200));
        $this->assertSame(2, TextHelper::readingTime(str_repeat('palavra ', 201), 200));

        $this->assertSame('Sim', TextHelper::booleanLabel(true, 'pt-BR'));
        $this->assertSame('Não', TextHelper::booleanLabel(false, 'pt-BR'));
        $this->assertSame('Yes', TextHelper::booleanLabel(true, 'en_US'));
        $this->assertSame('No', TextHelper::booleanLabel(false, 'en_US'));
    }

    public function test_plural_uses_locale_rules_when_available(): void
    {
        $this->assertSame('Nenhum Comentário', TextHelper::plural('comments', 0, 'pt-BR'));
        $this->assertSame('comentário', TextHelper::plural('comments', 1, 'pt-BR'));
        $this->assertSame('comentários', TextHelper::plural('comments', 2, 'pt-BR'));
        $this->assertSame('No Comments', TextHelper::plural('comments', 0, 'en_US'));
        $this->assertSame('comment', TextHelper::plural('comments', 1, 'en_US'));
        $this->assertSame('comments', TextHelper::plural('comments', 2, 'en_US'));
        $this->assertSame('cars', TextHelper::plural('car', 2, 'en_US'));
        $this->assertSame('comments', TextHelper::plural('comments', ['a', 'b'], 'en_US'));
    }
}
