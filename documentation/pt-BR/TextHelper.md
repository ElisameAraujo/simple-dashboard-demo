# 📝 **TextHelper**

O **TextHelper** fornece funções utilitárias para manipulação, limpeza e normalização de textos.  
Ele inclui recursos como truncamento, contagem de palavras, remoção de acentos, capitalização inteligente por locale e pluralização multilíngue baseada em arquivos de tradução.

---

# 📂 Funções disponíveis

## `limitByCharacters(string $text, int $limit): string`

Trunca um texto para um número máximo de caracteres, adicionando reticências quando necessário.

### Exemplos

```php
TextHelper::limitByCharacters('Lorem ipsum dolor sit amet', 10);
// "Lorem ipsu..."
```

---

## `limitByWords(string $text, int $limit): string`

Limita o texto por quantidade de palavras.

### Exemplos

```php
TextHelper::limitByWords('Lorem ipsum dolor sit amet', 3);
// "Lorem ipsum dolor..."
```

---

## `countWords(string $text): int`

Conta o número de palavras, ignorando tags HTML.

### Exemplo

```php
TextHelper::countWords('<p>Olá mundo!</p>');
// 2
```

---

## `countCharacters(string $text, bool $ignoreSpaces = false): int`

Conta caracteres, com opção de ignorar espaços.

### Exemplos

```php
TextHelper::countCharacters('Olá mundo');
// 9

TextHelper::countCharacters('Olá mundo', true);
// 8
```

---

## `removePunctuation(string $text): string`

Remove pontuações como vírgulas, pontos, exclamações, etc.

### Exemplo

```php
TextHelper::removePunctuation('Olá, mundo! Tudo bem?');
// "Olá mundo Tudo bem"
```

---

## `stripHTML(string $text): string`

Remove todas as tags HTML.

```php
TextHelper::stripHTML('<strong>Olá</strong> mundo');
// "Olá mundo"
```

---

## `cleanText(string $text): string`

Remove espaços duplicados, quebras de linha e tabulações.

```php
TextHelper::cleanText("Olá   \n   mundo");
// "Olá mundo"
```

---

## `removeLineBreaks(string $text): string`

Remove quebras de linha e substitui por espaço simples.

```php
TextHelper::removeLineBreaks("Olá\nmundo");
// "Olá mundo"
```

---

## `removeAccents(string $text): string`

Remove acentos e normaliza caracteres especiais.

```php
TextHelper::removeAccents('ação');
// "acao"
```

---

## `convertSpecialCharacters(string $string): string`

Substitui caracteres especiais com base no array protegido `$specialCharMap`.

### Exemplo

```php
TextHelper::convertSpecialCharacters('Rock & Roll');
// "Rock and Roll"
```

---

## `capitalizeNames(string $name, ?string $locale = null): string`

Capitaliza nomes respeitando conectores específicos do locale.

### Exemplo (pt_BR)

```php
TextHelper::capitalizeNames('joão da silva');
// "João da Silva"
```

### Exemplo (en_US)

```php
TextHelper::capitalizeNames('john mcdonald', 'en_US');
// "John Mcdonald"
```

---

## `sanitize(string $text): string`

Limpa texto para uso seguro:

-   Remove HTML
-   Remove quebras de linha
-   Remove espaços duplicados

### Exemplo

```php
TextHelper::sanitize("<p>Olá<br> mundo</p>");
// "Olá mundo"
```

---

## `normalizeNames(string $text, ?string $locale = null): string`

Limpeza completa + capitalização de nomes.

### Exemplo

```php
TextHelper::normalizeNames("<p>joão   da   silva</p>");
// "João da Silva"
```

---

## `onlyNumbers(string $text): string`

Extrai apenas números de uma string.

```php
TextHelper::onlyNumbers('Tel: (61) 99999-0000');
// "61999990000"
```

---

# `plural(string $string, int|array $count, ?string $locale = null): string`

A função **plural()** é a mais poderosa do `TextHelper`.  
Ela pluraliza palavras com base no idioma da aplicação, usando:

1. **Pluralização inteligente via arquivos de idioma**
2. **Fallback automático para `Str::plural()`**

---

## 📂 Como funciona a pluralização multilíngue

Você cria um arquivo:

```
resources/lang/seu_idioma/plurals.php
```

E adiciona apenas os plurais que quiser:

```php
return [
    'produtos' => '{1} produto|[2,*] produtos',
    'comentarios' => '{0} nenhum comentário|{1} comentário|[2,*] comentários',
];
```

---

### 🎯 Exemplo de uso

```php
TextHelper::plural('produtos', 1);
// "produto"

TextHelper::plural('produtos', 5);
// "produtos"
```

---

## 🌍 Suporte a múltiplos idiomas

Se você tiver:

```
resources/lang/en_US/plurals.php
```

```php
return [
    'produtos' => '{1} product|[2,*] products',
];
```

Então:

```php
TextHelper::plural('produtos', 1, 'en_US');
// "product"

TextHelper::plural('produtos', 5, 'en_US');
// "products"
```

---

## 🧠 Fallback automático

Se a palavra **não existir** no arquivo `plurals.php`:

```php
TextHelper::plural('car', 2);
// "cars" (fallback do Str::plural)
```

Nunca quebra.

---

# Conclusão

O **TextHelper** oferece:

-   Limpeza e normalização de texto
-   Capitalização inteligente por idioma
-   Remoção de acentos
-   Extração de números
-   Truncamento por caracteres e palavras
-   **Pluralização multilíngue avançada**
-   Integração total com o sistema de locale da aplicação

Ele é simples, poderoso e totalmente expansível.
