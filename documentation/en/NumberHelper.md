# 🔢 NumberHelper

The **NumberHelper** class provides utility functions for locale-based numeric formatting, including number compaction, currency formatting, area units, and ordinal suffixes.  
It uses flexible internal mappings, allowing developers to easily expand support for new languages, currencies, and patterns.

---

## 📌 Locale

All functions use the locale defined in:

-   `config('app.locale')`, or
-   `APP_LOCALE` in `.env`, or
-   the locale passed directly as an argument.

The locale is always **normalized** to the `XX_YY` format (e.g., `pt_BR`, `en_US`).

---

# 📂 Available Functions

### `compactNumber(int|float $number, ?string $locale = null): string`

Compacts large numbers using locale-specific suffixes.

### Examples

```php
NumberHelper::compactNumber(950);
// "950"

NumberHelper::compactNumber(12500, 'pt_BR');
// "12,5 mil"

NumberHelper::compactNumber(12500, 'en_US');
// "12.5 K"

NumberHelper::compactNumber(2500000, 'pt_BR');
// "2,5 mi"

NumberHelper::compactNumber(2500000, 'en_US');
// "2.5 M"
```

---

### `priceFormat(float|int $number, ?string $locale = null, ?string $currency = null): string`

Formats monetary values using PHP's `NumberFormatter`.

### Examples

```php
NumberHelper::priceFormat(1234.56, 'pt_BR');
// "R$ 1.234,56"

NumberHelper::priceFormat(1234.56, 'en_US');
// "$1,234.56"

NumberHelper::priceFormat(1234.56, 'fr_FR');
// "1 234,56 €"
```

---

### `currencySymbol(?string $locale = null): string`

Returns **only the currency symbol** based on the locale.

### Examples

```php
NumberHelper::currencySymbol('pt_BR');
// "R$"

NumberHelper::currencySymbol('en_US');
// "$"

NumberHelper::currencySymbol('fr_FR');
// "€"
```

---

### `currencyFormat(float|int $number, ?string $locale = null, ?string $currency = null): string`

Returns **symbol + formatted value**, ready for display.

### Examples

```php
NumberHelper::currencyFormat(1234.56, 'pt_BR');
// "R$ 1.234,56"

NumberHelper::currencyFormat(1234.56, 'en_US');
// "$ 1,234.56"
```

---

### `areaFormat(float|int|null $value, ?string $locale = null): string`

Formats area values with the appropriate unit for the locale.

### Examples

```php
NumberHelper::areaFormat(82.5, 'pt_BR');
// "82,5 m²"

NumberHelper::areaFormat(82.5, 'en_US');
// "82.5 ft²"

NumberHelper::areaFormat(null);
// "—"
```

---

### `ordinal(int $number, ?string $locale = null): string`

Returns the number with the appropriate ordinal suffix for the locale.

### Examples

```php
NumberHelper::ordinal(1, 'pt_BR');
// "1º"

NumberHelper::ordinal(1, 'en_US');
// "1st"

NumberHelper::ordinal(22, 'en_US');
// "22nd"

NumberHelper::ordinal(13, 'en_US');
// "13th"
```

---

# 🧩 Internal Mappings

The class uses protected arrays to allow easy expansion:

-   `$compactNumberMap` → compaction suffixes
-   `$localeCurrencyMap` → currency by locale
-   `$currencySymbolMap` → symbol by currency
-   `$localeAreaUnitMap` → area unit by locale
-   `$localeOrdinalSuffixMap` → ordinal suffixes by locale

You can add new locales, currencies, or units simply by editing these arrays.

---

# 🎯 Important Notes

-   All functions respect the locale configured in the project.
-   The locale is always normalized to avoid inconsistencies (`pt-BR` → `PT_BR`).
-   The class is fully extensible via protected arrays.
-   `compactNumber` uses the internal `setDecimals` function to decide how many decimal places to display.
-   `currencyFormat` combines `currencySymbol` + `priceFormat` for easy display.
