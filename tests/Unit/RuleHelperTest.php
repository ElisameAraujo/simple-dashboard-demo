<?php

namespace Tests\Unit;

use App\Helpers\RuleHelper;
use InvalidArgumentException;
use Tests\TestCase;

class RuleHelperTest extends TestCase
{
    public function test_extract_value_reads_rules_from_direct_array(): void
    {
        $rules = [
            'title' => 'required|string|min:3|max:120',
            'summary' => ['nullable', 'between:10,240'],
        ];

        $this->assertSame('120', RuleHelper::extractValue('title', 'max', $rules));
        $this->assertSame('3', RuleHelper::extractValue('title', 'min', $rules));
        $this->assertSame('10,240', RuleHelper::extractValue('summary', 'between', $rules));
    }

    public function test_extract_value_returns_null_when_field_or_rule_is_missing(): void
    {
        $rules = [
            'title' => ['required', 'max:120'],
        ];

        $this->assertNull(RuleHelper::extractValue('missing', 'max', $rules));
        $this->assertNull(RuleHelper::extractValue('title', 'min', $rules));
    }

    public function test_extract_value_ignores_non_textual_rule_objects(): void
    {
        $rules = [
            'title' => ['required', new RuleHelperObjectRule(), 'max:120'],
        ];

        $this->assertSame('120', RuleHelper::extractValue('title', 'max', $rules));
        $this->assertNull(RuleHelper::extractValue('title', 'unique', $rules));
    }

    public function test_extract_value_reads_rules_from_object_form_rules(): void
    {
        $source = new RuleHelperObjectRules();

        $this->assertSame('64', RuleHelper::extractValue('name', 'max', $source));
    }

    public function test_extract_value_reads_rules_from_static_form_rules_class(): void
    {
        $this->assertSame('255', RuleHelper::extractValue('email', 'max', RuleHelperStaticMethodRules::class));
    }

    public function test_extract_value_reads_rules_from_public_rules_constants(): void
    {
        $this->assertSame('32', RuleHelper::extractValue('username', 'max', RuleHelperRulesConstant::class));
        $this->assertSame('500', RuleHelper::extractValue('body', 'max', RuleHelperFormRulesConstant::class));
        $this->assertSame('20', RuleHelper::extractValue('name', 'min', RuleHelperPortugueseRulesConstant::class));
    }

    public function test_extract_value_rejects_invalid_rule_names(): void
    {
        $this->expectException(InvalidArgumentException::class);

        RuleHelper::extractValue('title', 'max:120', ['title' => ['max:120']]);
    }

    public function test_extract_value_rejects_unknown_rule_source_class(): void
    {
        $this->expectException(InvalidArgumentException::class);

        RuleHelper::extractValue('title', 'max', 'MissingRulesSource');
    }

    public function test_extract_value_rejects_rule_sources_without_supported_rules(): void
    {
        $this->expectException(InvalidArgumentException::class);

        RuleHelper::extractValue('title', 'max', RuleHelperUnsupportedRulesSource::class);
    }

    public function test_extract_value_rejects_rule_sources_that_do_not_return_arrays(): void
    {
        $this->expectException(InvalidArgumentException::class);

        RuleHelper::extractValue('title', 'max', RuleHelperInvalidStaticMethodRules::class);
    }
}

class RuleHelperObjectRule
{
}

class RuleHelperObjectRules
{
    public function formRules(): array
    {
        return [
            'name' => ['required', 'max:64'],
        ];
    }
}

class RuleHelperStaticMethodRules
{
    public static function formRules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255'],
        ];
    }
}

class RuleHelperRulesConstant
{
    public const RULES = [
        'username' => ['required', 'max:32'],
    ];
}

class RuleHelperFormRulesConstant
{
    public const FORM_RULES = [
        'body' => ['required', 'max:500'],
    ];
}

class RuleHelperPortugueseRulesConstant
{
    public const REGRAS = [
        'name' => ['required', 'min:20'],
    ];
}

class RuleHelperUnsupportedRulesSource
{
}

class RuleHelperInvalidStaticMethodRules
{
    public static function formRules(): string
    {
        return 'max:120';
    }
}
