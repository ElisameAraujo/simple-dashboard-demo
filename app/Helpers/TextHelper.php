<?php

namespace App\Helpers;

use App\Helpers\Support\LocaleResolver;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

class TextHelper
{
    /**
     * Name connectors kept lowercase for locale-aware capitalization.
     */
    protected static array $nameConnectors = [
        'pt_BR' => ['da', 'de', 'do', 'das', 'dos', 'e'],
        'en_US' => [],
    ];

    /**
     * Fixed special character replacements.
     */
    protected static array $specialCharMap = [
        '&' => 'and',
        '$' => 's',
        '@' => 'at',
        '#' => '-',
        '/' => '-',
    ];

    /**
     * Resolves the locale, normalizing to the standard with underscore and uppercase letters.
     */
    protected static function resolveLocale(?string $locale): string
    {
        return LocaleResolver::resolveLocale($locale);
    }

    /**
     * Truncates text by character count after removing HTML tags.
     * @param string $text Text that will be truncated.
     * @param int $limit Maximum number of characters before the ellipsis.
     * @return string Truncated text.
     */
    public static function limitByCharacters(string $text, int $limit): string
    {
        return Str::limit(self::stripHTML($text), $limit);
    }

    /**
     * Truncates text by word count after removing HTML tags.
     * @param string $text Text that will be truncated.
     * @param int $limit Maximum number of words before the ellipsis.
     * @return string Truncated text.
     */
    public static function limitByWords(string $text, int $limit): string
    {
        return Str::words(self::stripHTML($text), $limit);
    }

    /**
     * Counts words in text after removing HTML tags.
     * @param string $text Text whose words will be counted.
     * @return int Number of words.
     */
    public static function countWords(string $text): int
    {
        preg_match_all(
            "/[\p{L}\p{N}]+(?:['’-][\p{L}\p{N}]+)*/u",
            self::cleanText(self::stripHTML($text)),
            $matches
        );

        return count($matches[0]);
    }

    /**
     * Counts characters in text after removing HTML tags.
     * @param string $text Text whose characters will be counted.
     * @param bool $ignoreSpaces Whether whitespace should be ignored.
     * @return int Number of characters.
     */
    public static function countCharacters(string $text, bool $ignoreSpaces = false): int
    {
        $text = self::stripHTML($text);

        return $ignoreSpaces
            ? Str::length((string) preg_replace('/\s+/u', '', $text))
            : Str::length($text);
    }

    /**
     * Removes punctuation from text.
     * @param string $text Text whose punctuation will be removed.
     * @return string Text without punctuation.
     */
    public static function removePunctuation(string $text): string
    {
        return (string) preg_replace('/[[:punct:]]+/u', '', $text);
    }

    /**
     * Removes HTML tags from text.
     * @param string $text Text whose tags will be removed.
     * @return string Text without HTML tags.
     */
    public static function stripHTML(string $text): string
    {
        return strip_tags($text);
    }

    /**
     * Removes duplicate whitespace and trims the text.
     * @param string $text Text that will be cleaned.
     * @return string Text with normalized whitespace.
     */
    public static function cleanText(string $text): string
    {
        return self::normalizeWhitespace($text);
    }

    /**
     * Normalizes whitespace in text.
     * @param string $text Text whose whitespace will be normalized.
     * @return string Text with duplicate whitespace collapsed.
     */
    public static function normalizeWhitespace(string $text): string
    {
        return Str::squish($text);
    }

    /**
     * Replaces line breaks with spaces.
     * @param string $text Text whose line breaks will be removed.
     * @return string Text without line breaks.
     */
    public static function removeLineBreaks(string $text): string
    {
        return Str::replace(["\r", "\n"], ' ', $text);
    }

    /**
     * Converts accented characters to ASCII.
     * @param string $text Text whose accents will be removed.
     * @return string ASCII text.
     */
    public static function removeAccents(string $text): string
    {
        return Str::ascii($text);
    }

    /**
     * Replaces mapped special characters with fixed text alternatives.
     * @param string $text Text whose special characters will be replaced.
     * @return string Text with mapped replacements applied.
     */
    public static function convertSpecialCharacters(string $text): string
    {
        foreach (self::$specialCharMap as $character => $replace) {
            $text = Str::replace($character, $replace, $text);
        }

        return $text;
    }

    /**
     * Generates a URL-friendly slug from text after applying special character replacements.
     * @param string $text Text that will be converted to a slug.
     * @param string $separator Separator used between words.
     * @param string|null $locale Locale used to choose the transliteration language.
     * @return string Generated slug.
     */
    public static function slug(string $text, string $separator = '-', ?string $locale = null): string
    {
        $locale = self::resolveLocale($locale);
        $language = explode('_', $locale)[0] ?? 'en';
        $text = self::convertSpecialCharacters(self::stripHTML($text));

        return Str::slug($text, $separator, $language);
    }

    /**
     * Builds a clean text excerpt.
     * @param string $text Text that will be summarized.
     * @param int $limit Maximum number of characters before the ellipsis.
     * @return string Clean excerpt.
     */
    public static function excerpt(string $text, int $limit = 160): string
    {
        return Str::limit(self::cleanText(self::stripHTML($text)), $limit);
    }

    /**
     * Capitalizes names while preserving lowercase connectors for the locale.
     * @param string $name Name that will be capitalized.
     * @param string|null $locale Locale used to resolve lowercase connectors.
     * @return string Capitalized name.
     */
    public static function capitalizeNames(string $name, ?string $locale = null): string
    {
        $locale = self::resolveLocale($locale);
        $connectors = self::$nameConnectors[$locale] ?? [];

        // Normalize and capitalize
        $formatted = Str::title(Str::lower($name));

        // Leave the connectors in lowercase.
        foreach ($connectors as $connector) {
            $formatted = preg_replace(
                "/\b" . Str::ucfirst($connector) . "\b/u",
                Str::lower($connector),
                $formatted
            );
        }

        return $formatted;
    }

    /**
     * Sanitizes plain text for display or persistence.
     * @param string $text Text that will be sanitized.
     * @return string Sanitized text.
     */
    public static function sanitize(string $text): string
    {
        $text = self::stripHTML($text);
        $text = self::removeLineBreaks($text);
        return self::cleanText($text);
    }

    /**
     * Sanitizes and capitalizes a name.
     * @param string $text Name that will be normalized.
     * @param string|null $locale Locale used to resolve lowercase connectors.
     * @return string Normalized name.
     */
    public static function normalizeNames(string $text, ?string $locale = null): string
    {
        $text = self::sanitize($text);
        return self::capitalizeNames($text, $locale);
    }

    /**
     * Returns the first name from a normalized full name.
     * @param string $name Full name.
     * @param string|null $locale Locale used to normalize the name first.
     * @return string First name.
     */
    public static function firstName(string $name, ?string $locale = null): string
    {
        $parts = preg_split('/\s+/u', self::normalizeNames($name, $locale), -1, PREG_SPLIT_NO_EMPTY);

        return $parts[0] ?? '';
    }

    /**
     * Builds initials from a name.
     * @param string $name Name used to generate initials.
     * @param int $limit Maximum number of initials.
     * @param string|null $locale Locale used to ignore lowercase name connectors.
     * @return string Generated initials.
     */
    public static function initials(string $name, int $limit = 2, ?string $locale = null): string
    {
        if ($limit < 1) {
            return '';
        }

        $locale = self::resolveLocale($locale);
        $connectors = self::$nameConnectors[$locale] ?? [];
        $parts = preg_split('/\s+/u', self::normalizeNames($name, $locale), -1, PREG_SPLIT_NO_EMPTY);

        return collect($parts)
            ->reject(fn(string $part) => in_array(Str::lower($part), $connectors, true))
            ->take($limit)
            ->map(fn(string $part) => Str::upper(Str::substr($part, 0, 1)))
            ->implode('');
    }

    /**
     * Removes all non-numeric characters from text.
     * @param string $text Text that will be filtered.
     * @return string Numeric characters only.
     */
    public static function onlyNumbers(string $text): string
    {
        return (string) preg_replace('/\D/u', '', $text);
    }

    /**
     * Returns fallback text when the value is null or blank.
     * @param string|int|float|null $value Value that will be displayed.
     * @param string $fallback Text returned when the value is blank.
     * @return string Display-ready text.
     */
    public static function emptyFallback(string|int|float|null $value, string $fallback = '—'): string
    {
        $text = trim((string) $value);

        return $text === '' ? $fallback : $text;
    }

    /**
     * Estimates reading time in minutes.
     * @param string $text Text that will be counted.
     * @param int $wordsPerMinute Reading speed used in the estimate.
     * @return int Estimated minutes, or zero for empty text.
     */
    public static function readingTime(string $text, int $wordsPerMinute = 200): int
    {
        $words = self::countWords($text);

        if ($words === 0) {
            return 0;
        }

        return (int) ceil($words / max(1, $wordsPerMinute));
    }

    /**
     * Returns a localized label for a boolean value.
     * @param bool $value Boolean value.
     * @param string|null $locale Locale used to choose the label.
     * @return string Localized boolean label.
     */
    public static function booleanLabel(bool $value, ?string $locale = null): string
    {
        $locale = self::resolveLocale($locale);

        if (str_starts_with($locale, 'pt')) {
            return $value ? 'Sim' : 'Não';
        }

        return $value ? 'Yes' : 'No';
    }

    /**
     * Pluralizes a word or translation key using locale rules when available.
     * @param string $string Base word or key defined in lang/{locale}/plurals.php.
     * @param int|array $count Number of items or array for automatic counting.
     * @param string|null $locale Locale used to resolve pluralization.
     * @return string Pluralized text.
     */
    public static function plural(string $string, int|array $count, ?string $locale = null): string
    {
        $count = is_array($count) ? count($count) : $count;

        $locale = self::resolveLocale($locale);
        $translationLocale = LocaleResolver::resolveTranslationLocale($locale);
        $key = "plurals.{$string}";

        if (Lang::has($key, $translationLocale)) {
            return trans_choice($key, $count, locale: $translationLocale);
        }

        return Str::plural($string, $count);
    }
}
