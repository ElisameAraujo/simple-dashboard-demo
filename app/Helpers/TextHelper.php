<?php

namespace App\Helpers;

use App\Helpers\Support\LocaleResolver;
use Illuminate\Support\Str;
use Locale;

class TextHelper
{
    /**
     * Conectores de nomes por locale
     * Mantidos em minúsculo para facilitar substituição
     */
    protected static array $nameConnectors = [
        'pt_BR' => ['da', 'de', 'do', 'das', 'dos', 'e'],
        'en_US' => [], // inglês não usa conectores minúsculos
    ];

    /**
     * Mapeamento de caracteres especiais para substituição
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
     * `limitByCharacters`:
     * Truncates a string to a specified length, adding ellipses if necessary, ignoring HTML tags
     * @param string $text Defines the string to be truncated
     * @param int $limit Number of characters to be displayed
     * @return string
     */
    public static function limitByCharacters(string $text, int $limit): string
    {
        return Str::limit(self::stripHTML($text), $limit);
    }

    /**
     * `limitByWords`:
     * Limits the text by the number of words, ignoring HTML tags
     * @param string $text Defines the string that will be truncated
     * @param int $limit Number of words to be displayed
     * @return string
     */
    public static function limitByWords(string $text, int $limit): string
    {
        return Str::words(self::stripHTML($text), $limit);
    }

    /**
     * `countWords`:
     * Counts the number of words in a text, ignoring HTML tags
     * @param string $text Text that will have the number of words counted
     * @return int
     */
    public static function countWords(string $text): int
    {
        return Str::wordCount(self::stripHTML($text));
    }

    /**
     * `countCharacters`:
     * Counts the number of characters in a text, ignoring or not the spaces between characters or words
     * @param string $text Text that will have the number of characters counted
     * @param bool $ignoreSpaces Defines whether or not the function should ignore spaces in the string passed to count the characters
     * @return int
     */
    public static function countCharacters(string $text, bool $ignoreSpaces = false): int
    {
        $text = self::stripHTML($text);
        return $ignoreSpaces
            ? Str::length(str_replace(' ', '', $text))
            : Str::length($text);
    }

    /**
     * `removePunctuation`:
     * Removes punctuation such as commas, periods, exclamation marks, question marks, etc
     * @param string $text Text to remove punctuation
     * @return string
     */
    public static function removePunctuation(string $text): string
    {
        return preg_replace('/[[:punct:]]+/', '', $text);
    }

    /**
     * `stripHTML`:
     * Removes all HTML tags from a string
     * @param string $text Text to be stripped of any HTML tags
     * @return string
     */
    public static function stripHTML(string $text): string
    {
        return strip_tags($text);
    }

    /**
     * `cleanText`:
     * Removes duplicate spaces, line breaks, and tabs
     * @param string $text Text to be cleaned
     * @return string
     */
    public static function cleanText(string $text): string
    {
        return preg_replace('/\s+/', ' ', trim($text));
    }

    /**
     * `removeLineBreaks`:
     * Removes line breaks (\n, \r) and replaces them with single spaces.
     * @param string $text Text to be cleared
     * @return string
     */
    public static function removeLineBreaks(string $text): string
    {
        return Str::replace(["\r", "\n"], ' ', $text);
    }

    /**
     * `removeAccents`:
     * Removes accents and normalizes special characters
     * @param string $text Text to be cleared
     * @return string
     */
    public static function removeAccents(string $text): string
    {
        return Str::ascii($text);
    }

    /**
     * `convertSpecialCharacters`:
     * Replaces special characters defined in the protected array.
     * @param string $text Text that will have the characters replaced
     * @return string
     */
    public static function convertSpecialCharacters(string $text): string
    {
        foreach (self::$specialCharMap as $character => $replace) {
            $text = Str::replace($character, $replace, $text);
        }

        return $text;
    }

    /**
     * `capitalizeNames`:
     * Capitalizes names respecting connectors according to the locale.
     * @param string $name String of name that will be capitalized
     * @param string $locale This string defines whether connectors should be formatted based on the application's language
     * @return string
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
     * `sanitize`:
     * Cleans text for safe use: removes HTML, line breaks, and duplicate spaces.
     * Useful for use in comment sections on a website.
     * @param string $text Text to be cleaned
     */
    public static function sanitize(string $text): string
    {
        $text = self::stripHTML($text);
        $text = self::removeLineBreaks($text);
        return self::cleanText($text);
    }

    /**
     * `normalizeNames`:
     * Complete cleaning + capitalization of names.
     * @param string $text Name to be normalized
     * @param string $locale This string defines whether connectors should be formatted based on the application's language

     */
    public static function normalizeNames(string $text, ?string $locale = null): string
    {
        $text = self::sanitize($text);
        return self::capitalizeNames($text, $locale);
    }

    /**
     * `onlyNumbers`:
     * Removes all non-numeric characters from a string.
     * @param string $text Text that will only contain the returned numbers
     * @return string
     */
    public static function onlyNumbers(string $text): string
    {
        return preg_replace('/\D/', '', $text);
    }


    /**
     * `plural`:
     * Pluralizes a word based on a number or array, using rules
     * specific to the application's language when available.
     *
     * This function offers two operating modes:
     *
     * ##### 1. SMART LANGUAGE PLURALIZATION (RECOMMENDED)
     * 
     * If a corresponding entry exists in the file `resources/lang/{locale}/plurals.php`,
     * then pluralization will be done using `trans_choice()`, allowing
     * complete pluralization rules for any language.
     *
     * Example of the `plurals.php` file:
     *
     * ```
     * return [
     * 'products' => '{1} product|[2,*] products',
     * ];
     * ```
     *
     * Usage:
     * 
     * ```
     * TextHelper::plural('products', 1); // "product"
     * TextHelper::plural('products', 5); // "products"
     * ```
     * 
     * ##### 2. AUTOMATIC FALLBACK
     * 
     * If the word does not exist in the plural file of the current language,
     * the function uses `Str::plural()`, which works well for English and
     * some simple plurals.
     *
     * Usage:
     * 
     * ```
     * TextHelper::plural('car', 2); // "cars"
     * ```
     *
     * @param string $string Base word or key defined in the plurals.php file
     * @param int|array $count Number of items or array for automatic counting
     * @param string|null $locale Desired locale (e.g., 'pt_BR'). If omitted,
     * uses the application's locale.
     *
     * @return string Correctly pluralized word

     */
    public static function plural(string $string, int|array $count, ?string $locale = null): string
    {
        $count = is_array($count) ? count($count) : $count;

        // Resolve locale using the same logic as NumberHelper.
        $locale = self::resolveLocale($locale);
        $translationLocale = LocaleResolver::resolveTranslationLocale($locale);

        // Path to the key in the plurals.php file.
        $key = "plurals.{$string}";

        // If it exists in the current language's plural file, use trans_choice.
        if (\Illuminate\Support\Facades\Lang::has($key, $translationLocale)) {
            return trans_choice($key, $count, locale: $translationLocale);
        }

        // Fallback: simple Laravel pluralization (ENGLISH ONLY)
        return Str::plural($string, $count);
    }
}
