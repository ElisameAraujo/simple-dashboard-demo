<?php

namespace Tests\Feature\Localization;

use App\Helpers\DateHelper;
use App\Helpers\DiskHelper;
use App\Helpers\HTMLHelper;
use App\Helpers\MediaHelper;
use App\Helpers\NumberHelper;
use App\Helpers\NotificationHelper;
use App\Helpers\Support\LocaleResolver;
use App\Support\HelperDemoCatalog;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\Yaml\Yaml;
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

    public function test_helper_documentation_metadata_is_translated(): void
    {
        app()->setLocale('en');

        $this->assertSame('DateHelper', __('docs/helpers/date-helper.name'));
        $this->assertSame('Date formatting, relative dates, and localized output.', __('docs/helpers/date-helper.description'));

        app()->setLocale('pt_BR');

        $this->assertSame('DateHelper', __('docs/helpers/date-helper.name'));
        $this->assertSame('Formatação de datas, datas relativas e saídas localizadas.', __('docs/helpers/date-helper.description'));
    }

    public function test_helper_yaml_documentation_is_translated_and_matches_public_methods(): void
    {
        foreach ([
            'date-helper' => DateHelper::class,
            'disk-helper' => DiskHelper::class,
            'html-helper' => HTMLHelper::class,
            'media-helper' => MediaHelper::class,
            'notification-helper' => NotificationHelper::class,
        ] as $slug => $class) {
            $english = Yaml::parseFile(resource_path("docs/helpers/en/{$slug}.yaml"));
            $portuguese = Yaml::parseFile(resource_path("docs/helpers/pt_BR/{$slug}.yaml"));

            $this->assertSame(
                array_keys(Arr::dot($english)),
                array_keys(Arr::dot($portuguese)),
                "The [{$slug}] helper YAML docs should expose matching translation keys."
            );

            $publicMethods = collect((new ReflectionClass($class))->getMethods(ReflectionMethod::IS_PUBLIC))
                ->filter(fn(ReflectionMethod $method) => $method->getDeclaringClass()->getName() === $class)
                ->pluck('name')
                ->values()
                ->all();

            $this->assertSame($publicMethods, array_keys($english['methods']));
            $this->assertSame($publicMethods, array_keys($portuguese['methods']));
        }
    }

    public function test_helper_catalog_prefers_yaml_documentation_when_available(): void
    {
        app()->setLocale('pt_BR');

        $dateHelper = HelperDemoCatalog::find('date-helper');
        $diskHelper = HelperDemoCatalog::find('disk-helper');
        $htmlHelper = HelperDemoCatalog::find('html-helper');
        $mediaHelper = HelperDemoCatalog::find('media-helper');
        $notificationHelper = HelperDemoCatalog::find('notification-helper');

        $simpleDate = collect($dateHelper['methods'])->firstWhere('name', 'simpleDate');
        $updateFile = collect($diskHelper['methods'])->firstWhere('name', 'updateFile');
        $heading = collect($htmlHelper['methods'])->firstWhere('name', 'heading');
        $showMedia = collect($mediaHelper['methods'])->firstWhere('name', 'showMedia');
        $latestNotifications = collect($notificationHelper['methods'])->firstWhere('name', 'latestNotifications');

        $this->assertSame('Formata uma data simples com dia, mês e ano.', $simpleDate['summary']);
        $this->assertSame('Data que será formatada.', $simpleDate['parameters'][0]['description']);

        $this->assertSame('Salva um novo arquivo e remove o arquivo antigo do mesmo disco e das mesmas subpastas.', $updateFile['summary']);
        $this->assertSame('Nome do arquivo antigo, ou caminho relativo quando subfolders não for usado.', $updateFile['parameters'][1]['description']);
        $this->assertSame(['feminino/marco/imagem-20260521183000.jpg'], $updateFile['example']['output']);

        $this->assertSame(['HTMLHelper::make()->heading(2)->generate();'], $heading['example']['usage']);
        $this->assertSame(['<h2>Título de Exemplo</h2>'], $heading['example']['output']);
        $this->assertNotContains('HTMLHelper instance', $heading['example']['output']);

        $this->assertSame('Retorna a URL pública da mídia existente ou a URL de um placeholder quando o arquivo não existe.', $showMedia['summary']);
        $this->assertSame('Caminho relativo da mídia dentro do disco.', $showMedia['parameters'][0]['description']);
        $this->assertSame(['/storage/products/demo.jpg'], $showMedia['example']['output']);

        $this->assertSame('Lista as notificações mais recentes do usuário autenticado, lidas ou não lidas, para resumos como dropdowns.', $latestNotifications['summary']);
        $this->assertSame('Quantidade máxima de notificações retornadas. Quando null ou menor que 1, retorna todas.', $latestNotifications['parameters'][0]['description']);
        $this->assertSame(['Collection com as 10 notificações mais recentes.'], $latestNotifications['example']['output']);
    }

    private function flattenTranslations(string $locale): array
    {
        $translations = [];

        foreach (File::allFiles(lang_path($locale)) as $file) {
            $relativePath = str_replace('\\', '/', $file->getRelativePathname());

            if (str_starts_with($relativePath, 'vendor/')) {
                continue;
            }

            $translationGroup = str_replace('/', '.', substr($relativePath, 0, -4));
            $translations[$translationGroup] = require $file->getPathname();
        }

        return Arr::dot($translations);
    }
}
