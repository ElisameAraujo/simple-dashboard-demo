<?php

namespace App\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionIntersectionType;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionType;
use ReflectionUnionType;

class HelperDemoCatalog
{
    private const HELPERS = [
        'date-helper' => ['alias' => 'DateHelper', 'icon' => 'fa-regular fa-calendar-days'],
        'disk-helper' => ['alias' => 'DiskHelper', 'icon' => 'fa-regular fa-hard-drive'],
        'html-helper' => ['alias' => 'HTMLHelper', 'icon' => 'fa-solid fa-code'],
        'media-helper' => ['alias' => 'MediaHelper', 'icon' => 'fa-regular fa-image'],
        'notification-helper' => ['alias' => 'NotificationHelper', 'icon' => 'fa-regular fa-bell'],
        'number-helper' => ['alias' => 'NumberHelper', 'icon' => 'fa-solid fa-arrow-down-1-9'],
        'pagination-helper' => ['alias' => 'PaginationHelper', 'icon' => 'fa-solid fa-ellipsis'],
        'route-helper' => ['alias' => 'RouteHelper', 'icon' => 'fa-solid fa-route'],
        'rule-helper' => ['alias' => 'RuleHelper', 'icon' => 'fa-solid fa-scale-balanced'],
        'text-helper' => ['alias' => 'TextHelper', 'icon' => 'fa-solid fa-font'],
        'user-helper' => ['alias' => 'UserHelper', 'icon' => 'fa-regular fa-user'],
    ];

    public static function all(): array
    {
        return collect(self::HELPERS)
            ->map(fn(array $definition, string $slug) => self::build($slug, $definition))
            ->values()
            ->all();
    }

    public static function find(string $slug): ?array
    {
        if (!array_key_exists($slug, self::HELPERS)) {
            return null;
        }

        return self::build($slug, self::HELPERS[$slug]);
    }

    private static function build(string $slug, array $definition): array
    {
        $class = config("helpers.global.{$definition['alias']}");
        $content = trans("pages/helpers.helpers.{$slug}");
        $documentation = HelperDocumentationRepository::for($slug);

        return [
            'slug' => $slug,
            'alias' => $definition['alias'],
            'class' => $class,
            'icon' => $definition['icon'],
            'name' => $documentation['name'] ?? $content['name'] ?? $definition['alias'],
            'description' => $documentation['description'] ?? $content['description'] ?? '',
            'works' => $documentation['works'] ?? $content['works'] ?? [],
            'example' => self::normalizeExample($documentation['example'] ?? $content['example'] ?? []),
            'methods' => $class ? self::methodsFor($class, $documentation['methods'] ?? []) : [],
            'url' => route('helpers.show', $slug),
        ];
    }

    private static function methodsFor(string $class, array $documentation = []): array
    {
        if (!class_exists($class)) {
            return [];
        }

        $reflection = new ReflectionClass($class);

        return Collection::make($reflection->getMethods(ReflectionMethod::IS_PUBLIC))
            ->filter(fn(ReflectionMethod $method) => $method->getDeclaringClass()->getName() === $class)
            ->sortBy(fn(ReflectionMethod $method) => $method->getStartLine())
            ->map(function (ReflectionMethod $method) use ($class, $documentation) {
                $methodDocumentation = $documentation[$method->getName()] ?? [];

                return [
                    'name' => $method->getName(),
                    'signature' => self::signatureFor($method),
                    'summary' => $methodDocumentation['description'] ?? self::summaryFor($method),
                    'parameters' => self::parametersFor($method, $methodDocumentation['parameters'] ?? []),
                    'return' => self::formatType($method->getReturnType()) ?: 'mixed',
                    'example' => self::exampleFor($class, $method, $methodDocumentation['example'] ?? []),
                ];
            })
            ->values()
            ->all();
    }

    private static function signatureFor(ReflectionMethod $method): string
    {
        $parameters = collect($method->getParameters())
            ->map(fn(ReflectionParameter $parameter) => self::formatParameter($parameter))
            ->implode(', ');

        return $method->getName() . "({$parameters})";
    }

    private static function formatParameter(ReflectionParameter $parameter): string
    {
        $type = self::formatType($parameter->getType());
        $name = ($parameter->isVariadic() ? '...' : '') . '$' . $parameter->getName();
        $default = '';

        if ($parameter->isOptional() && $parameter->isDefaultValueAvailable()) {
            $default = ' = ' . self::formatDefault($parameter->getDefaultValue());
        }

        return trim(implode(' ', array_filter([$type, $name]))) . $default;
    }

    private static function summaryFor(ReflectionMethod $method): string
    {
        $lines = self::cleanDocblockLines($method);
        $summary = collect($lines)
            ->reject(fn(string $line) => str_starts_with($line, '@'))
            ->reject(fn(string $line) => preg_match('/^`?' . preg_quote($method->getName(), '/') . '`?:?$/', $line))
            ->first();

        if ($summary) {
            return $summary;
        }

        return trans('pages/helpers.methods.fallback_summary', [
            'method' => $method->getName(),
        ]);
    }

    private static function parametersFor(ReflectionMethod $method, array $documentation = []): array
    {
        $descriptions = self::parameterDescriptionsFor($method);

        return collect($method->getParameters())
            ->map(function (ReflectionParameter $parameter) use ($descriptions, $documentation) {
                $type = self::formatType($parameter->getType()) ?: 'mixed';
                $name = $parameter->getName();

                return [
                    'name' => '$' . $name,
                    'type' => $type,
                    'default' => $parameter->isOptional() && $parameter->isDefaultValueAvailable()
                        ? self::formatDefault($parameter->getDefaultValue())
                        : null,
                    'description' => $documentation[$name] ?? $descriptions[$name] ?? self::parameterDescriptionFallback($name),
                ];
            })
            ->values()
            ->all();
    }

    private static function parameterDescriptionFallback(string $name): string
    {
        $key = "pages/helpers.parameter_descriptions.{$name}";

        return trans()->has($key)
            ? trans($key)
            : trans('pages/helpers.methods.fallback_parameter', ['parameter' => '$' . $name]);
    }

    private static function parameterDescriptionsFor(ReflectionMethod $method): array
    {
        return collect(self::cleanDocblockLines($method))
            ->filter(fn(string $line) => str_starts_with($line, '@param '))
            ->mapWithKeys(function (string $line) {
                if (!preg_match('/^@param\s+\S+\s+\$(\w+)\s*(.*)$/', $line, $matches)) {
                    return [];
                }

                return [$matches[1] => trim($matches[2])];
            })
            ->all();
    }

    private static function cleanDocblockLines(ReflectionMethod $method): array
    {
        $doc = $method->getDocComment();

        if (!$doc) {
            return [];
        }

        return collect(preg_split('/\R/', $doc))
            ->map(fn(string $line) => trim(preg_replace('/^\s*\/?\*+\s?|\s*\*\/$/', '', $line)))
            ->filter()
            ->values()
            ->all();
    }

    private static function exampleFor(string $class, ReflectionMethod $method, array $documentation = []): array
    {
        if ($documentation !== []) {
            return self::normalizeExample($documentation);
        }

        $alias = array_search($class, config('helpers.global', []), true) ?: class_basename($class);
        $arguments = collect($method->getParameters())
            ->map(fn(ReflectionParameter $parameter) => self::sampleArgumentFor($parameter))
            ->implode(', ');

        $usage = self::usageFor($alias, $method, $arguments);

        return [
            'usage' => [$usage],
            'output' => [self::outputFor($method)],
        ];
    }

    private static function normalizeExample(array $example): array
    {
        return [
            'usage' => self::linesFrom($example['code'] ?? $example['usage'] ?? []),
            'output' => self::linesFrom($example['output'] ?? []),
        ];
    }

    private static function linesFrom(string|array|null $value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if ($value === null || $value === '') {
            return [];
        }

        return preg_split('/\R/', rtrim($value));
    }

    private static function usageFor(string $alias, ReflectionMethod $method, string $arguments): string
    {
        if ($alias === 'HTMLHelper') {
            if ($method->isStatic()) {
                return "{$alias}::{$method->getName()}({$arguments});";
            }

            $call = "{$method->getName()}({$arguments})";

            return $method->getName() === 'generate'
                ? "{$alias}::make()->heading(2)->generate();"
                : "{$alias}::make()->{$call}->generate();";
        }

        return "{$alias}::{$method->getName()}({$arguments});";
    }

    private static function sampleArgumentFor(ReflectionParameter $parameter): string
    {
        $locale = app()->getLocale() === 'pt_BR' ? 'pt-BR' : 'en_US';

        return match ($parameter->getName()) {
            'column', 'name' => "'name'",
            'className' => "'hljs'",
            'count' => '3',
            'currency' => "'BRL'",
            'customName' => "'relatorio.pdf'",
            'date' => "'2026-05-19'",
            'disk' => "'public'",
            'eachSide' => '2',
            'email' => "'john.doe@example.com'",
            'endDate' => "'2026-05-22'",
            'field' => "'title'",
            'file' => "'avatars/user.jpg'",
            'filename' => "'dashboard-routes'",
            'folders', 'subfolders' => "'demo'",
            'gender' => "'m'",
            'height' => '480',
            'id' => "'id'",
            'limit' => '3',
            'locale' => "'{$locale}'",
            'newFile' => '$file',
            'notificationId' => "'12345-uuid'",
            'number' => '1299.9',
            'oldFile' => "'avatars/old-user.jpg'",
            'path' => "'avatars/user.jpg'",
            'permission' => "'posts.edit'",
            'placeholder' => "'img/placeholders/avatars/default-avatar.jpg'",
            'position' => "'middle'",
            'provider' => "'youtube'",
            'role' => "'admin'",
            'ruleName' => "'max'",
            'rulesSource' => "['title' => 'required|string|max:120']",
            'startDate' => "'2026-05-19'",
            'string' => "'products'",
            'subfolder' => "'User'",
            'text' => app()->getLocale() === 'pt_BR' ? "'<p>Olá mundo</p>'" : "'<p>Hello world</p>'",
            'type' => "'NewMessageNotification'",
            'value' => '82.5',
            'width' => '640',
            'withRandomLinks' => 'true',
            'charactersToMask' => '3',
            'cols' => '[1, 1, 1]',
            'default' => "'Guest'",
            'except' => "['debug']",
            'level' => '2',
            'paginator' => '$paginator',
            default => self::defaultArgumentFor($parameter),
        };
    }

    private static function defaultArgumentFor(ReflectionParameter $parameter): string
    {
        $type = self::formatType($parameter->getType());

        return match (true) {
            str_contains((string) $type, 'int') => '1',
            str_contains((string) $type, 'float') => '1.5',
            str_contains((string) $type, 'bool') => 'true',
            str_contains((string) $type, 'array') => '[]',
            default => "'example'",
        };
    }

    private static function outputFor(ReflectionMethod $method): string
    {
        $key = "pages/helpers.method_outputs.{$method->getName()}";

        if (trans()->has($key)) {
            return trans($key);
        }

        $return = self::formatType($method->getReturnType());

        return match (true) {
            $return === 'void' => 'void',
            $return === 'bool' => 'true',
            $return === 'int' => '3',
            $return === 'array' => "['id' => 1, 'name' => 'John Doe']",
            $return === 'static' => class_basename($method->getDeclaringClass()->getName()) . ' instance',
            str_contains((string) $return, 'Collection') => 'Collection([...])',
            str_contains((string) $return, 'string') => self::stringOutputFor($method->getName()),
            default => self::genericOutputFor($method->getName()),
        };
    }

    private static function stringOutputFor(string $method): string
    {
        $locale = app()->getLocale();

        return match ($method) {
            'currentYear' => '2026',
            'currentDate', 'simpleDate' => $locale === 'pt_BR' ? '19/05/2026' : '05/19/2026',
            'fullCurrentDate', 'fullExtendedDate' => $locale === 'pt_BR' ? 'terça-feira, 19 de maio de 2026' : 'Tuesday, May 19, 2026',
            'currentFullDateWithHours' => $locale === 'pt_BR' ? '19 de maio de 2026 às 10:30' : 'May 19, 2026 at 10:30',
            'dateWithHoursAndSeconds' => $locale === 'pt_BR' ? '19/05/2026 às 10:30:15' : '05/19/2026 10:30:15',
            'dateExcel' => $locale === 'pt_BR' ? '19/05/2026' : '05/19/2026',
            'dateWithHours' => $locale === 'pt_BR' ? '19/05/2026 às 10:30' : '05/19/2026 10:30',
            'shortDate' => $locale === 'pt_BR' ? '19/05' : 'May 19',
            'shortTime' => '10:30',
            'diffDatesHuman' => $locale === 'pt_BR' ? '2 minutos atrás' : '2 minutes ago',
            'emailDate' => $locale === 'pt_BR' ? 'Ter, 19 de mai., 10:30 (2 minutos atrás)' : 'Tue, may. 19, 10:30 (2 minutes ago)',
            'compactNumber' => $locale === 'pt_BR' ? '1,3 mil' : '1.3 K',
            'priceFormat' => $locale === 'pt_BR' ? 'R$ 1.299,90' : '$1,299.90',
            'areaFormat' => $locale === 'pt_BR' ? '82,5 m²' : '888.02 ft²',
            'ordinal' => $locale === 'pt_BR' ? '1º' : '1st',
            'limitByCharacters' => 'Lorem ipsu...',
            'limitByWords' => 'Lorem ipsum dolor...',
            'removePunctuation' => $locale === 'pt_BR' ? 'Olá mundo' : 'Hello world',
            'stripHTML', 'cleanText', 'removeLineBreaks', 'sanitize' => $locale === 'pt_BR' ? 'Olá mundo' : 'Hello world',
            'removeAccents' => 'acao',
            'convertSpecialCharacters' => 'Rock and Roll',
            'capitalizeNames', 'normalizeNames' => $locale === 'pt_BR' ? 'Maria da Silva' : 'John Doe',
            'onlyNumbers' => '61999990000',
            'plural' => $locale === 'pt_BR' ? 'produtos' : 'products',
            'maskEmail' => 'john***e@example.com',
            'sanitizeEmail' => 'john.doe@example.com',
            'userFirstName' => 'John',
            'userShortName' => 'John D.',
            'emailDomain' => 'example.com',
            'userAvatar', 'showMedia', 'fileUrl' => '/storage/avatars/user.jpg',
            'userAvatarPath', 'saveFile', 'updateFile' => 'avatars/user-20260519103000.jpg',
            'fileSize' => '256 KB',
            'mediaFullPath' => '/storage/public/avatars/user.jpg',
            'mediaMimeType' => 'image/jpeg',
            'userShortSummary' => 'John D. - john.doe@example.com',
            'generate' => '<h2>Example title</h2>',
            default => Str::of($method)->headline()->lower()->toString(),
        };
    }

    private static function genericOutputFor(string $method): string
    {
        return match ($method) {
            'userLogged', 'userIsActive', 'isTodayCheck', 'removeFile', 'mediaExists', 'markAsRead', 'markAsUnread', 'deleteNotification', 'userHasRole', 'userHasPermission' => 'true',
            'daysDifference', 'countWords', 'countCharacters', 'allUnreadNotificationsCount', 'unreadNotificationsByTypeCount' => '3',
            'userId' => '1',
            'username', 'userEmail', 'info' => 'John Doe',
            'userAvatarFallback' => "['initials' => 'J', 'color' => '#3498db']",
            'userSummary' => "['id' => 1, 'name' => 'John Doe', 'email' => 'john.doe@example.com']",
            'userRoles' => "['admin', 'editor']",
            'userPermissions' => "['posts.create', 'posts.edit']",
            'allRoles', 'allPermissions' => "collect(['admin', 'editor'])",
            'build' => '[1, 2, 3, 8, 9, 10]',
            'listAllRoutes' => "[['uri' => 'helpers', 'name' => 'helpers.index']]",
            'downloadMedia' => 'BinaryFileResponse',
            default => 'mixed',
        };
    }

    private static function formatType(?ReflectionType $type): ?string
    {
        return match (true) {
            $type instanceof ReflectionNamedType => ($type->allowsNull() && $type->getName() !== 'mixed' ? '?' : '') . $type->getName(),
            $type instanceof ReflectionUnionType => collect($type->getTypes())->map(fn(ReflectionType $item) => self::formatType($item))->implode('|'),
            $type instanceof ReflectionIntersectionType => collect($type->getTypes())->map(fn(ReflectionType $item) => self::formatType($item))->implode('&'),
            default => null,
        };
    }

    private static function formatDefault(mixed $value): string
    {
        return match (true) {
            $value === null => 'null',
            $value === true => 'true',
            $value === false => 'false',
            is_string($value) => "'{$value}'",
            is_array($value) => '[]',
            default => (string) $value,
        };
    }
}
