<?php

namespace App\Helpers;

use InvalidArgumentException;
use ReflectionClass;

class RuleHelper
{
    private const RULE_CONSTANTS = [
        'RULES',
        'FORM_RULES',
        'REGRAS',
    ];

    /**
     * Extracts the value of a textual validation rule like "max:120".
     * @param string $field Field whose rules will be inspected.
     * @param string $ruleName Rule name that should be extracted.
     * @param array|string|object $rulesSource Rules array, object with formRules(), class with static formRules(), or class with a rules constant.
     * @return string|null Extracted rule value, or null when the field/rule is not present.
     */
    public static function extractValue(string $field, string $ruleName, array|string|object $rulesSource): ?string
    {
        $ruleName = self::normalizeRuleName($ruleName);
        $rules = self::rulesFrom($rulesSource);

        if (!isset($rules[$field])) {
            return null;
        }

        $rule = collect(self::normalizeFieldRules($rules[$field]))
            ->first(fn($r) => is_string($r) && str_starts_with($r, "{$ruleName}:"));

        if (!$rule) {
            return null;
        }

        return substr($rule, strlen($ruleName) + 1);
    }

    /**
     * Resolve validation rules from the supported source shapes.
     * @param array|string|object $rulesSource Rules array, object, or class name.
     * @return array Resolved validation rules.
     */
    private static function rulesFrom(array|string|object $rulesSource): array
    {
        if (is_array($rulesSource)) {
            return $rulesSource;
        }

        $reflection = self::reflectionFrom($rulesSource);

        if ($reflection->hasMethod('formRules')) {
            $method = $reflection->getMethod('formRules');

            if ($method->isPublic() && (is_object($rulesSource) || $method->isStatic())) {
                return self::ensureRulesArray($method->invoke(is_object($rulesSource) ? $rulesSource : null));
            }
        }

        foreach (self::RULE_CONSTANTS as $constant) {
            if ($reflection->hasConstant($constant)) {
                $constantReflection = $reflection->getReflectionConstant($constant);

                if ($constantReflection?->isPublic()) {
                    return self::ensureRulesArray($constantReflection->getValue());
                }
            }
        }

        throw new InvalidArgumentException('Rules source must be an array, an object with public formRules(), a class with public static formRules(), or a class with a public rules constant.');
    }

    /**
     * Normalize a field rule definition into a list of rule items.
     * @param mixed $fieldRules Rule definition for a single field.
     * @return array Normalized list of rule items.
     */
    private static function normalizeFieldRules(mixed $fieldRules): array
    {
        if (is_string($fieldRules)) {
            return explode('|', $fieldRules);
        }

        return is_array($fieldRules) ? $fieldRules : [];
    }

    /**
     * Validate and normalize the requested rule name.
     */
    private static function normalizeRuleName(string $ruleName): string
    {
        $ruleName = trim($ruleName);

        if ($ruleName === '' || str_contains($ruleName, ':') || str_contains($ruleName, '|')) {
            throw new InvalidArgumentException('Rule name must be a non-empty validation rule name without ":" or "|".');
        }

        return $ruleName;
    }

    /**
     * Build reflection for an object or class name.
     */
    private static function reflectionFrom(string|object $rulesSource): ReflectionClass
    {
        if (is_object($rulesSource)) {
            return new ReflectionClass($rulesSource);
        }

        if (!class_exists($rulesSource)) {
            throw new InvalidArgumentException("Rules source class does not exist: {$rulesSource}");
        }

        return new ReflectionClass($rulesSource);
    }

    /**
     * Ensure resolved rules are returned as an array.
     */
    private static function ensureRulesArray(mixed $rules): array
    {
        if (!is_array($rules)) {
            throw new InvalidArgumentException('Rules source must resolve to an array.');
        }

        return $rules;
    }
}
