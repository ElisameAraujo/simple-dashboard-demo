# 🔢 NumberHelper

A classe **NumberHelper** fornece funções utilitárias para formatação numérica baseada em _locale_, incluindo compactação de números, formatação monetária, unidades de área e sufixos ordinais.  
Ela utiliza mapeamentos internos flexíveis, permitindo que o desenvolvedor expanda facilmente suporte para novos idiomas, moedas e padrões.

---

## 📌 Locale

Todas as funções utilizam o locale definido em:

-   `config('app.locale')`, ou
-   `APP_LOCALE` no `.env`, ou
-   o locale passado diretamente como argumento.

O locale é sempre **normalizado** para o formato `XX_YY` (ex.: `pt_BR`, `en_US`).

---

# 📂 Funções disponíveis

### `compactNumber(int|float $number, ?string $locale = null): string`

Compacta números grandes usando sufixos específicos do locale.

### Exemplos

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

Formata valores monetários usando o `NumberFormatter` do PHP.

### Exemplos

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

Retorna **apenas o símbolo da moeda** baseado no locale.

### Exemplos

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

Retorna **símbolo + valor formatado**, já pronto para exibição.

### Exemplos

```php
NumberHelper::currencyFormat(1234.56, 'pt_BR');
// "R$ 1.234,56"

NumberHelper::currencyFormat(1234.56, 'en_US');
// "$ 1,234.56"
```

---

### `areaFormat(float|int|null $value, ?string $locale = null): string`

Formata valores de área com unidade apropriada ao locale.

### Exemplos

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

Retorna o número com sufixo ordinal apropriado ao locale.

### Exemplos

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

# 🧩 Mapeamentos internos

A classe utiliza arrays protegidos para permitir fácil expansão:

-   `$compactNumberMap` → sufixos de compactação
-   `$localeCurrencyMap` → moeda por locale
-   `$currencySymbolMap` → símbolo por moeda
-   `$localeAreaUnitMap` → unidade de área por locale
-   `$localeOrdinalSuffixMap` → sufixos ordinais por locale

Você pode adicionar novos locais, moedas ou unidades simplesmente editando esses arrays.

---

# 🎯 Observações importantes

-   Todas as funções respeitam o locale configurado no projeto.
-   O locale é sempre normalizado para evitar inconsistências (`pt-BR` → `PT_BR`).
-   A classe é totalmente extensível via arrays protegidos.
-   `compactNumber` usa a função interna `setDecimals` para decidir quantas casas decimais exibir.
-   `currencyFormat` combina `currencySymbol` + `priceFormat` para facilitar exibição.
