<?php

namespace App\Helpers\Support;

class LocaleResolver
{
    /**
     * Internal locale cache resolved.
     */
    protected static ?string $resolvedLocale = null;
    protected static ?string $resolvedSourceLocale = null;
    protected static string $defaultLocaleKey = 'app.locale';

    /**
     *`resolveLocale`:
     * Normalizes the locale to the "xx_YY" pattern (e.g., pt_BR, en_US).
     *
     * @param string|null $locale Locale informado manualmente
     * @return string Locale resolvido
     */
    public static function resolveLocale(?string $locale = null): string
    {
        //1. Manual locale always takes priority.
        if ($locale) {
            return self::normalize($locale);
        }

        $appLocale = app()->getLocale() ?: config(self::$defaultLocaleKey, env('APP_LOCALE', 'en_US'));

        // 2. If already resolved it before for the same app locale, return it from the cache.
        if (self::$resolvedLocale !== null && self::$resolvedSourceLocale === $appLocale) {
            return self::$resolvedLocale;
        }

        // 3. Normalizes and saves to cache.
        self::$resolvedSourceLocale = $appLocale;
        self::$resolvedLocale = self::normalize($appLocale);

        return self::$resolvedLocale;
    }

    public static function resolveTranslationLocale(?string $locale = null): string
    {
        $normalizedLocale = self::resolveLocale($locale);
        $hyphenLocale = str_replace('_', '-', $normalizedLocale);
        $languageLocale = explode('_', $normalizedLocale, 2)[0];

        foreach ([$normalizedLocale, $hyphenLocale, $languageLocale] as $candidate) {
            if (is_dir(lang_path($candidate))) {
                return $candidate;
            }
        }

        return $normalizedLocale;
    }

    public static function flushResolvedLocale(): void
    {
        self::$resolvedLocale = null;
        self::$resolvedSourceLocale = null;
    }

    /**
     * `normalize`:
     * Converts "pt-br" → "pt_BR", "EN-us" → "en_US"
     */
    protected static function normalize(string $locale): string
    {
        $locale = str_replace('-', '_', $locale);
        $locale = strtolower($locale);

        if (str_contains($locale, '_')) {
            [$lang, $region] = explode('_', $locale, 2);
            return strtolower($lang) . '_' . strtoupper($region);
        }

        return strtolower($locale);
    }
}
