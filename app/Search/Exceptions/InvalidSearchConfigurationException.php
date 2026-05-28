<?php

namespace App\Search\Exceptions;

use RuntimeException;

class InvalidSearchConfigurationException extends RuntimeException
{
    public static function missingScope(string $scope): self
    {
        return new self("Invalid search configuration [{$scope}]. The scope does not exist in config/search.php.");
    }

    public static function missingStaticTitle(string $key): self
    {
        return new self("Invalid search configuration [{$key}]. Static search items must define [title] or [title_key].");
    }

    public static function missingStaticDestination(string $key): self
    {
        return new self("Invalid search configuration [{$key}]. Static search items must define [route] or [url].");
    }

    public static function missingStaticGroup(string $key): self
    {
        return new self("Invalid search configuration [{$key}]. Static search items must define [group].");
    }

    public static function missingGroupLabel(string $key): self
    {
        return new self("Invalid search configuration [{$key}]. Search groups must define [label] or [label_key].");
    }

    public static function invalidGroup(string $key, string $group): self
    {
        return new self("Invalid search configuration [{$key}]. The group [{$group}] does not exist.");
    }

    public static function invalidGroupOrder(string $key): self
    {
        return new self("Invalid search configuration [{$key}]. The [order] value must be numeric.");
    }

    public static function invalidRoute(string $key, string $route): self
    {
        return new self("Invalid search configuration [{$key}]. The route [{$route}] does not exist.");
    }

    public static function invalidKeywords(string $key, string $source): self
    {
        return new self("Invalid search configuration [{$key}]. The [{$source}] value must resolve to an array of keywords.");
    }

    public static function invalidWeight(string $key): self
    {
        return new self("Invalid search configuration [{$key}]. The [weight] value must be a positive number.");
    }

    public static function missingModelClass(string $key): self
    {
        return new self("Invalid search configuration [{$key}]. Model search items must define [model].");
    }

    public static function invalidModelClass(string $key, string $model): self
    {
        return new self("Invalid search configuration [{$key}]. The model [{$model}] must be an Eloquent model class.");
    }

    public static function missingModelTable(string $key, string $table): self
    {
        return new self("Invalid search configuration [{$key}]. The table [{$table}] does not exist.");
    }

    public static function missingModelGroup(string $key): self
    {
        return new self("Invalid search configuration [{$key}]. Model search items must define [group].");
    }

    public static function missingModelDestination(string $key): self
    {
        return new self("Invalid search configuration [{$key}]. Model search items must define [route] or [url].");
    }

    public static function missingModelField(string $key, string $field): self
    {
        return new self("Invalid search configuration [{$key}]. Model search items must define [{$field}].");
    }

    public static function invalidModelFields(string $key, string $field): self
    {
        return new self("Invalid search configuration [{$key}]. The [{$field}] value must be a non-empty array.");
    }

    public static function unknownModelField(string $key, string $field, string $table): self
    {
        return new self("Invalid search configuration [{$key}]. The field [{$field}] does not exist on table [{$table}].");
    }

    public static function invalidFieldWeight(string $key, string $field): self
    {
        return new self("Invalid search configuration [{$key}]. The field weight [{$field}] must point to a searchable field and use a positive number.");
    }

    public static function invalidConstraint(string $key): self
    {
        return new self("Invalid search configuration [{$key}]. Model constraints must define [field], [operator] and a valid [value] when the operator needs one.");
    }
}
