<?php

namespace App\Helpers;

use App\Helpers\Support\LocaleResolver;
use NumberFormatter;

class NumberHelper
{
    /**
     * Suffix mapping for compact numbers by locale
     */
    protected static array $compactNumberMap = [
        'pt_BR' => [
            'hundred'   => null,
            'thousands' => 'mil',
            'millions'  => 'mi',
            'billions'  => 'bi',
        ],
        'en_US' => [
            'hundred'   => null,
            'thousands' => 'K',
            'millions'  => 'M',
            'billions'  => 'B',
        ],
    ];

    /**
     * Currency mapping by locale
     */
    protected static array $localeCurrencyMap = [
        'pt_BR' => 'BRL',
        'en_US' => 'USD',
        'en_GB' => 'GBP',
        'fr_FR' => 'EUR',
        'de_DE' => 'EUR',
        'ja_JP' => 'JPY',
    ];

    /**
     * Mapping currency symbols by code
     */
    protected static array $currencySymbolMap = [
        'BRL' => 'R$',
        'USD' => '$',
        'EUR' => '€',
        'GBP' => '£',
        'JPY' => '¥',
    ];

    /**
     * Mapping of area units by location
     */
    protected static array $localeAreaUnitMap = [
        'pt_BR' => 'm²',
        'en_US' => 'ft²',
        'en_GB' => 'm²',
        'ja_JP' => 'm²',
    ];

    /**
     * Mapping of ordinal suffixes by locale
     */
    protected static array $localeOrdinalSuffixMap = [
        'pt_BR' => 'º',
        'en_US' => ['st', 'nd', 'rd', 'th'],
        'en_GB' => ['st', 'nd', 'rd', 'th'],
    ];

    /**
     * Resolves the locale, normalizing to the standard with underscore and uppercase letters.
     */
    protected static function resolveLocale(?string $locale): string
    {
        return LocaleResolver::resolveLocale($locale);
    }

    /**
     * `compactNumber`
     * Compacta números de acordo com o locale
     * @param int|float $number Number to be formatted
     * @param string $locale Locale code (e.g., 'pt_BR', 'en_US')
     * @return string
     */
    public static function compactNumber(int|float $number, ?string $locale = null): string
    {
        $locale = self::resolveLocale($locale);
        $map = self::$compactNumberMap[$locale] ?? self::$compactNumberMap['en_US'];

        if ($number < 1000) {
            return number_format($number, 0, ',', '.');
        }

        if ($number < 1000000) {
            $thousands = $number / 1000;
            $decimals = self::setDecimals($number, 1000);
            return number_format($thousands, $decimals, ',', '') . ' ' . $map['thousands'];
        }

        if ($number < 1000000000) {
            $millions = $number / 1000000;
            $decimals = self::setDecimals($number, 1000000);
            return number_format($millions, $decimals, ',', '') . ' ' . $map['millions'];
        }

        $billions = $number / 1000000000;
        $decimals = self::setDecimals($number, 1000000000);
        return number_format($billions, $decimals, ',', '') . ' ' . $map['billions'];
    }

    /**
     * `priceFormat`:
     * Formats price according to location and currency.
     * @param float|int $number Number to be formatted
     * @param string $locale Locale code (e.g., 'pt_BR', 'en_US')
     * @param string $currency Currency code (e.g., 'BRL', 'USD')
     * @return string
     */
    public static function priceFormat(float|int $number, ?string $locale = null, ?string $currency = null): string
    {
        $locale = self::resolveLocale($locale);
        $currency = $currency ?? self::$localeCurrencyMap[$locale] ?? 'USD';

        $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);
        return $formatter->formatCurrency($number, $currency);
    }

    /**
     * `currencySymbol`:
     * Returns the currency symbol based on the locale.
     * @param string $locale Locale code (e.g., 'pt_BR', 'en_US')
     * @return string
     */
    public static function currencySymbol(?string $locale = null): string
    {
        $locale = self::resolveLocale($locale);
        $currency = self::$localeCurrencyMap[$locale] ?? 'USD';

        return self::$currencySymbolMap[$currency] ?? $currency;
    }

    /**
     * `currencyFormat`:
     * Format monetary value with plus symbol + value
     * @param float|int $number
     * @param string $locale Locale code (e.g., 'pt_BR', 'en_US')
     * @param string $currency Currency code (e.g., 'BRL', 'USD')
     * @return string
     */
    public static function currencyFormat(float|int $number, ?string $locale = null, ?string $currency = null): string
    {
        $locale = self::resolveLocale($locale);
        $symbol = self::currencySymbol($locale);
        $formatted = self::priceFormat($number, $locale, $currency);

        return "{$symbol} {$formatted}";
    }

    /**
     * `areaFormat`:
     * Formata área de acordo com locale
     * @param float|int|null $value Area value
     * @param string $locale Locale code (e.g., 'pt_BR', 'en_US')
     * @return string
     */
    public static function areaFormat(float|int|null $value, ?string $locale = null): string
    {
        if ($value === null) {
            return '—';
        }

        $locale = self::resolveLocale($locale);
        $unit = self::$localeAreaUnitMap[$locale] ?? 'm²';

        $formatter = new NumberFormatter($locale, NumberFormatter::DECIMAL);
        $formatted = $formatter->format($value);

        return "{$formatted} {$unit}";
    }

    /**
     * `ordinal`:
     * Returns a generic ordinal based on language (e.g. 1º, 2nd)
     * @param int $number Number to be formatted
     * @param string $locale Locale code (e.g., 'pt_BR', 'en_US')
     * @return string
     */
    public static function ordinal(int $number, ?string $locale = null): string
    {
        $locale = self::resolveLocale($locale);

        if (str_starts_with($locale, 'pt')) {
            return $number . self::$localeOrdinalSuffixMap['pt_BR'];
        }

        $suffixes = self::$localeOrdinalSuffixMap['en_US'];

        $suffix = match (true) {
            in_array($number % 100, [11, 12, 13]) => $suffixes[3],
            $number % 10 === 1 => $suffixes[0],
            $number % 10 === 2 => $suffixes[1],
            $number % 10 === 3 => $suffixes[2],
            default => $suffixes[3],
        };

        return $number . $suffix;
    }

    /**
     * `setDecimals`:
     * Define decimal places for compacted numbers
     * @param int|float $number
     * @param int $divider
     * @return int
     */
    private static function setDecimals(int|float $number, int $divider): int
    {
        $remainder = $number % $divider;

        if ($remainder === 0) {
            return 0;
        }

        $hundred = intval(($remainder / ($divider / 10)) % 10);
        $dozen  = intval(($remainder / ($divider / 100)) % 10);

        if ($dozen === 0) {
            return 1; // Show only hundred
        }

        return 2; // Show hundred and dozen
    }
}
