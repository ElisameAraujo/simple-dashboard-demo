<?php

namespace App\Helpers;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Carbon\Translator;
use App\Helpers\Support\LocaleResolver;

class DateHelper
{
    /**
     * Internal translator cache by locale.
     */
    protected static array $translatorCache = [];

    /**
     * Applies locale, timezone, and translations to the Carbon object.
     */
    protected static function applyLocale(CarbonInterface $date, ?string $locale = null): CarbonInterface
    {
        $locale = LocaleResolver::resolveLocale($locale);
        $translationLocale = LocaleResolver::resolveTranslationLocale($locale);
        $timezone = config('app.timezone', 'UTC');

        // Carrega traduções do arquivo dates.php
        $translations = trans("dates", locale: $translationLocale);

        // Cacheia o translator para evitar reprocessamento
        if (!isset(self::$translatorCache[$locale])) {
            $translator = Translator::get($locale);

            // Aplica traduções se existirem
            if (is_array($translations)) {
                $translator->setTranslations($translations);
            }

            self::$translatorCache[$locale] = $translator;
        }

        return $date
            ->locale($locale)
            ->setTimezone($timezone);
    }

    /**
     * `currentYear`:
     * Returns the current year in "YYYY" format
     */
    public static function currentYear(): string
    {
        return now()->format('Y');
    }

    /**
     * `currentDate`:
     * Returns the current date in the format "dd/mm/yyyy"
     * @param string|null $locale Locale to use for formatting
     * @return string Formatted current date
     */
    public static function currentDate(?string $locale = null): string
    {
        $locale = LocaleResolver::resolveLocale($locale);
        $translationLocale = LocaleResolver::resolveTranslationLocale($locale);
        $format = trans("dates.formats.date", locale: $translationLocale);

        return self::applyLocale(Carbon::now(), $locale)->format($format);
    }

    /**
     * `fullCurrentDate`:
     * Returns the current date in full extended format with translated day of the week and month.
     * @param string|null $locale Locale to use for formatting
     * @return string Formatted full current date
     */
    public static function fullCurrentDate(?string $locale = null): string
    {
        return self::fullExtendedDate(Carbon::today()->toDateString(), $locale);
    }

    /**
     * `fullExtendedDate`:
     * Returns a date in full format with day of the week and full month name
     * @param string $date Date string to format
     * @param string|null $locale Locale to use for formatting
     * @return string Formatted full current extended date
     */
    public static function fullExtendedDate(string $date, ?string $locale = null): string
    {
        $locale = LocaleResolver::resolveLocale($locale);
        $translationLocale = LocaleResolver::resolveTranslationLocale($locale);
        $format = trans("dates.formats.full_weekday", locale: $translationLocale);

        return self::applyLocale(Carbon::parse($date), $locale)
            ->translatedFormat($format);
    }

    /**
     * `currentFullDateWithHours`:
     * Returns the date in full with the time
     * @param string $date Date string to format
     * @param string|null $locale Locale to use for formatting
     * @return string Formatted full current date with hours
     */
    public static function currentFullDateWithHours(string $date, ?string $locale = null): string
    {
        $locale = LocaleResolver::resolveLocale($locale);
        $translationLocale = LocaleResolver::resolveTranslationLocale($locale);
        $format = trans("dates.formats.date_time_extended", locale: $translationLocale);

        return self::applyLocale(Carbon::parse($date), $locale)
            ->translatedFormat($format);
    }

    /**
     * `diffDatesHuman`:
     * Returns the difference between the current date and the present time in a humanized format.
     * @param string $date Date string to format
     * @param string|null $locale Locale to use for formatting
     * @return string Formatted date difference in human format
     */
    public static function diffDatesHuman(string $date, ?string $locale = null): string
    {
        $locale = LocaleResolver::resolveLocale($locale);

        $target = CarbonImmutable::parse($date);
        $now = CarbonImmutable::now();

        if ($target->equalTo($now)) {
            return trans('dates.diff.now', locale: LocaleResolver::resolveTranslationLocale($locale));
        }

        $isFuture = $target->greaterThan($now);

        $unit = self::diffUnit($target, $now);

        return self::relativeDiff($unit, $isFuture, $locale);
    }
    /**
     * `dateWithHoursAndSeconds`:
     * Returns the date with time with seconds
     * @param string $date Date string to format
     * @param string|null $locale Locale to use for formatting
     * @return string Formatted date with hours and seconds
     */
    public static function dateWithHoursAndSeconds(string $date, ?string $locale = null): string
    {
        $locale = LocaleResolver::resolveLocale($locale);
        $translationLocale = LocaleResolver::resolveTranslationLocale($locale);
        $format = trans("dates.formats.date_time_short_seconds", locale: $translationLocale);

        return self::applyLocale(Carbon::parse($date), $locale)->format($format);
    }

    /**
     * `dateExcel`:
     * Returns the date in Excel locale format
     * @param string $date Date string to format
     * @param string|null $locale Locale to use for formatting
     * @return string Formatted date in Excel locale format
     */
    public static function dateExcel(string $date, ?string $locale = null): string
    {
        $locale = LocaleResolver::resolveLocale($locale);
        $translationLocale = LocaleResolver::resolveTranslationLocale($locale);
        $format = trans("dates.formats.date_excel", locale: $translationLocale);

        return self::applyLocale(Carbon::parse($date), $locale)->format($format);
    }

    /**
     * `dateWithHours`:
     * Returns the date with time without seconds.
     * @param string $date Date string to format
     * @param string|null $locale Locale to use for formatting
     * @return string Formatted date with hours
     */
    public static function dateWithHours(string $date, ?string $locale = null): string
    {
        $locale = LocaleResolver::resolveLocale($locale);
        $translationLocale = LocaleResolver::resolveTranslationLocale($locale);
        $format = trans("dates.formats.date_time_short", locale: $translationLocale);

        return self::applyLocale(Carbon::parse($date), $locale)->format($format);
    }

    /**
     * `simpleDate`:
     * Returns a simple date
     * @param string $date Date string to format
     * @param string|null $locale Locale to use for formatting
     * @return string Formatted simple date
     */
    public static function simpleDate(string $date, ?string $locale = null): string
    {
        $locale = LocaleResolver::resolveLocale($locale);
        $translationLocale = LocaleResolver::resolveTranslationLocale($locale);
        $format = trans("dates.formats.date", locale: $translationLocale);

        return self::applyLocale(Carbon::parse($date), $locale)->format($format);
    }

    /**
     * `isTodayCheck`:
     * Check if the date is today
     * @param string $date Date string to check
     * @return bool True if the date is today, false otherwise
     */
    public static function isTodayCheck(string $date): bool
    {
        return Carbon::parse($date)->isToday();
    }

    /**
     * `daysDifference`:
     * Difference in days between two dates returned as an integer.
     */
    public static function daysDifference(string $startDate, string $endDate): int
    {
        return Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate));
    }

    /**
     * `shortDate`:
     * Returns a short date with day and month.
     */
    public static function shortDate(string $date, ?string $locale = null): string
    {
        $locale = LocaleResolver::resolveLocale($locale);
        $translationLocale = LocaleResolver::resolveTranslationLocale($locale);
        $format = trans("dates.formats.short_date", locale: $translationLocale);

        return self::applyLocale(Carbon::parse($date), $locale)->format($format);
    }

    /**
     * `shortTime`:
     * Return a short time in hours and minutes
     */
    public static function shortTime(string $date, ?string $locale = null): string
    {
        $locale = LocaleResolver::resolveLocale($locale);
        $translationLocale = LocaleResolver::resolveTranslationLocale($locale);
        $format = trans("dates.formats.short_time", locale: $translationLocale);

        return self::applyLocale(Carbon::parse($date), $locale)->format($format);
    }

    /**
     * `emailDate`:
     * Returns a date formatted for email display, including the relative time.
     * @param string $date Date string to format
     * @return string Formatted email date with relative time
     */

    public static function emailDate(string $date, ?string $locale = null): string
    {
        $locale = LocaleResolver::resolveLocale($locale);
        $date = Carbon::parse($date);

        $formattedDate = self::formatEmailDate($date, $locale);
        $relative = self::diffDatesHuman($date->toDateTimeString(), $locale);

        return $formattedDate . ' ' . trans('dates.email.wrapper', ['relative' => $relative,], locale: LocaleResolver::resolveTranslationLocale($locale));
    }

    /** === Private Functions === **/
    private static function formatEmailDate(Carbon $date, string $locale): string
    {
        $translationLocale = LocaleResolver::resolveTranslationLocale($locale);
        $weekdays = trans('dates.weekdays_short_capitalized', locale: $translationLocale);
        $months   = trans('dates.months_simple', locale: $translationLocale);

        return trans('dates.email.format', [
            'weekday' => $weekdays[$date->dayOfWeek],
            'day'     => $date->day,
            'month'   => $months[$date->month - 1],
            'time'    => $date->format('H:i'),
        ], locale: $translationLocale);
    }

    private static function diffUnit(CarbonImmutable $target, CarbonImmutable $now): array
    {
        $diff = $target->diff($now);

        return [
            'year'   => $diff->y,
            'month'  => $diff->m,
            'day'    => $diff->d,
            'hour'   => $diff->h,
            'minute' => $diff->i,
            'second' => $diff->s,
        ];
    }

    private static function relativeDiff(array $units, bool $isFuture, string $locale): string
    {
        foreach ($units as $unit => $value) {
            if ($value > 0) {
                $time = self::formatTimeUnit($unit, $value, $locale);
                return self::wrapRelativeTime($time, $isFuture, $locale);
            }
        }

        return trans('dates.diff.now', locale: LocaleResolver::resolveTranslationLocale($locale));
    }

    private static function formatTimeUnit(string $unit, int $value, string $locale): string
    {
        $key = $value === 1 ? 'one' : 'many';

        return str_replace(':count', $value, trans("dates.diff.$unit.$key", locale: LocaleResolver::resolveTranslationLocale($locale)));
    }

    private static function wrapRelativeTime(string $time, bool $isFuture, string $locale): string
    {
        $wrapperKey = $isFuture ? 'future' : 'past';

        return str_replace(':time', $time, trans("dates.diff.$wrapperKey", locale: LocaleResolver::resolveTranslationLocale($locale)));
    }
}
