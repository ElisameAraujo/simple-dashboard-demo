<?php

namespace App\Helpers;

class RuleHelper
{
    /**
     * Extracts the value of a rule of type "rule:value".
     *
     * Example:
     *   extractValue('product_description', 'max', CreateProductDTO::class)
     *   → 5000
     *
     *   extractValue('comment', 'max', $this->formRules())
     *   → 5000
     */
    public static function extractValue(string $field, string $ruleName, string|array $rulesSource): ?string
    {
        $rules = self::rulesFrom($rulesSource);

        if (!isset($rules[$field])) {
            return null;
        }

        $rule = collect(self::normalizeFieldRules($rules[$field]))
            ->first(fn($r) => is_string($r) && str_starts_with($r, "{$ruleName}:"));

        if (!$rule) {
            return null;
        }

        // Extract the value after the ":"
        return substr($rule, strlen($ruleName) + 1);
    }

    private static function rulesFrom(string|array $rulesSource): array
    {
        if (is_array($rulesSource)) {
            return $rulesSource;
        }

        if (!class_exists($rulesSource) || !method_exists($rulesSource, 'formRules')) {
            return [];
        }

        return $rulesSource::formRules();
    }

    private static function normalizeFieldRules(mixed $fieldRules): array
    {
        if (is_string($fieldRules)) {
            return explode('|', $fieldRules);
        }

        return is_array($fieldRules) ? $fieldRules : [];
    }
}
