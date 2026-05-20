<?php

namespace Tests\Feature\Localization;

use App\Helpers\DateHelper;
use App\Helpers\NumberHelper;
use App\Helpers\Support\LocaleResolver;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Tests\TestCase;

class TranslationTest extends TestCase
{
    /**
     * Every supported locale should expose the same translation keys.
     */
    public function test_translation_files_share_the_same_keys(): void
    {
        $baseLocale = 'en';
        $baseKeys = array_keys($this->flattenTranslations($baseLocale));

        foreach (['pt_BR'] as $locale) {
            $localeKeys = array_keys($this->flattenTranslations($locale));

            $this->assertSame(
                [],
                array_values(array_diff($baseKeys, $localeKeys)),
                "The [{$locale}] locale is missing translation keys."
            );

            $this->assertSame(
                [],
                array_values(array_diff($localeKeys, $baseKeys)),
                "The [{$locale}] locale has extra translation keys."
            );
        }
    }

    /**
     * Blade and Laravel's translator should respect the current app locale.
     */
    public function test_interface_strings_are_resolved_by_the_current_app_locale(): void
    {
        app()->setLocale('en');

        $this->assertSame('Search', __('ui.search'));
        $this->assertSame('Notifications', __('ui.notifications'));

        app()->setLocale('pt_BR');

        $this->assertSame('Pesquisar', __('ui.search'));
        $this->assertSame('Notificações', __('ui.notifications'));
    }

    public function test_locale_resolver_supports_intl_and_translation_locale_formats(): void
    {
        $this->assertSame('pt_BR', LocaleResolver::resolveLocale('pt-BR'));
        $this->assertSame('pt_BR', LocaleResolver::resolveLocale('PT-BR'));
        $this->assertSame('pt_BR', LocaleResolver::resolveLocale('pt_BR'));

        $this->assertSame('pt_BR', LocaleResolver::resolveTranslationLocale('pt-BR'));
        $this->assertSame('pt_BR', LocaleResolver::resolveTranslationLocale('PT-BR'));
        $this->assertSame('pt_BR', LocaleResolver::resolveTranslationLocale('pt_BR'));
        $this->assertSame('en', LocaleResolver::resolveTranslationLocale('en_US'));
    }

    public function test_date_helper_uses_locale_specific_date_translations(): void
    {
        $this->assertSame('19/05/2026', DateHelper::simpleDate('2026-05-19', 'pt-BR'));
        $this->assertSame('terça-feira, 19 de maio de 2026', DateHelper::fullExtendedDate('2026-05-19', 'pt-BR'));

        $this->assertSame('05/19/2026', DateHelper::simpleDate('2026-05-19', 'en_US'));
        $this->assertSame('Tuesday, May 19, 2026', DateHelper::fullExtendedDate('2026-05-19', 'en_US'));
    }

    public function test_relative_date_and_email_date_translations_are_available(): void
    {
        Carbon::setTestNow('2026-05-19 12:00:00');

        try {
            $this->assertSame('2 minutos atrás', DateHelper::diffDatesHuman('2026-05-19 11:58:00', 'pt-BR'));
            $this->assertSame('2 minutes ago', DateHelper::diffDatesHuman('2026-05-19 11:58:00', 'en_US'));

            $this->assertSame('Ter, 19 de mai., 11:58 (2 minutos atrás)', DateHelper::emailDate('2026-05-19 11:58:00', 'pt-BR'));
            $this->assertSame('Tue, may. 19, 11:58 (2 minutes ago)', DateHelper::emailDate('2026-05-19 11:58:00', 'en_US'));
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_number_helper_uses_resolved_portuguese_locale(): void
    {
        $this->assertSame('1º', NumberHelper::ordinal(1, 'pt-BR'));
        $this->assertSame('2nd', NumberHelper::ordinal(2, 'en_US'));
    }

    private function flattenTranslations(string $locale): array
    {
        $translations = [];

        foreach (glob(lang_path("{$locale}/*.php")) as $file) {
            $translations[basename($file, '.php')] = require $file;
        }

        return Arr::dot($translations);
    }
}
