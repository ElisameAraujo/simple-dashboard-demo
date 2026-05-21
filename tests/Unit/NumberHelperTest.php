<?php

namespace Tests\Unit;

use App\Helpers\NumberHelper;
use ReflectionClass;
use Tests\TestCase;

class NumberHelperTest extends TestCase
{
    public function test_compact_number_uses_locale_decimal_separator(): void
    {
        $this->assertSame('12,5 mil', NumberHelper::compactNumber(12500, 'pt-BR'));
        $this->assertSame('12.5 K', NumberHelper::compactNumber(12500, 'en_US'));
        $this->assertSame('1.3 K', NumberHelper::compactNumber(1299.9, 'en_US'));
    }

    public function test_price_format_uses_intl_currency_formatting(): void
    {
        $this->assertSame('R$ 1.234,56', NumberHelper::priceFormat(1234.56, 'pt-BR'));
        $this->assertSame('$1,234.56', NumberHelper::priceFormat(1234.56, 'en_US'));
    }

    public function test_currency_helpers_do_not_duplicate_price_format(): void
    {
        $reflection = new ReflectionClass(NumberHelper::class);

        $this->assertFalse($reflection->hasMethod('currencyFormat'));
        $this->assertFalse($reflection->hasMethod('currencySymbol'));
    }

    public function test_area_format_converts_square_meters_to_locale_area_unit(): void
    {
        $this->assertSame('82,5 m²', NumberHelper::areaFormat(82.5, 'pt-BR'));
        $this->assertSame('888.02 ft²', NumberHelper::areaFormat(82.5, 'en_US'));
        $this->assertSame('—', NumberHelper::areaFormat(null, 'en_US'));
    }

    public function test_ordinal_formats_locale_suffixes(): void
    {
        $this->assertSame('1º', NumberHelper::ordinal(1, 'pt-BR'));
        $this->assertSame('1ª', NumberHelper::ordinal(1, 'pt-BR', 'f'));
        $this->assertSame('1º', NumberHelper::ordinal(1, 'pt-BR', 'invalid'));
        $this->assertSame('1st', NumberHelper::ordinal(1, 'en_US'));
        $this->assertSame('1st', NumberHelper::ordinal(1, 'en_US', 'f'));
        $this->assertSame('22nd', NumberHelper::ordinal(22, 'en_US'));
        $this->assertSame('13th', NumberHelper::ordinal(13, 'en_US'));
    }
}
