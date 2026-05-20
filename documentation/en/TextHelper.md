# 📝 **TextHelper**

The **TextHelper** provides utility functions for text manipulation, cleaning, and normalization.  
It includes features such as truncation, word counting, accent removal, intelligent capitalization by locale, and multilingual pluralization based on translation files.

---

# 📂 Available Functions

## `limitByCharacters(string $text, int $limit): string`

Truncates text to a maximum number of characters, adding ellipsis when necessary.

### Examples

```php
TextHelper::limitByCharacters('Lorem ipsum dolor sit amet', 10);
// "Lorem ipsu..."
```

---

## `limitByWords(string $text, int $limit): string`

Limits text by word count.

### Examples

```php
TextHelper::limitByWords('Lorem ipsum dolor sit amet', 3);
// "Lorem ipsum dolor..."
```

---

## `countWords(string $text): int`

Counts the number of words, ignoring HTML tags.

### Example

```php
TextHelper::countWords('<p>Hello world!</p>');
// 2
```

---

## `countCharacters(string $text, bool $ignoreSpaces = false): int`

Counts characters, with option to ignore spaces.

### Examples

```php
TextHelper::countCharacters('Hello world');
// 11

TextHelper::countCharacters('Hello world', true);
// 10
```

---

## `removePunctuation(string $text): string`

Removes punctuation marks like commas, periods, exclamation marks, etc.

### Example

```php
TextHelper::removePunctuation('Hello, world! How are you?');
// "Hello world How are you"
```

---

## `stripHTML(string $text): string`

Removes all HTML tags.

```php
TextHelper::stripHTML('<strong>Hello</strong> world');
// "Hello world"
```

---

## `cleanText(string $text): string`

Removes duplicate spaces, line breaks, and tabs.

```php
TextHelper::cleanText("Hello   \n   world");
// "Hello world"
```

---

## `removeLineBreaks(string $text): string`

Removes line breaks and replaces them with a single space.

```php
TextHelper::removeLineBreaks("Hello\nworld");
// "Hello world"
```

---

## `removeAccents(string $text): string`

Removes accents and normalizes special characters.

```php
TextHelper::removeAccents('ação');
// "acao"
```

---

## `convertSpecialCharacters(string $string): string`

Replaces special characters based on the protected array `$specialCharMap`.

### Example

```php
TextHelper::convertSpecialCharacters('Rock & Roll');
// "Rock and Roll"
```

---

## `capitalizeNames(string $name, ?string $locale = null): string`

Capitalizes names respecting locale-specific connectors.

### Example (pt_BR)

```php
TextHelper::capitalizeNames('joão da silva');
// "João da Silva"
```

### Example (en_US)

```php
TextHelper::capitalizeNames('john mcdonald', 'en_US');
// "John Mcdonald"
```

---

## `sanitize(string $text): string`

Cleans text for safe use:

-   Removes HTML
-   Removes line breaks
-   Removes duplicate spaces

### Example

```php
TextHelper::sanitize("<p>Hello<br> world</p>");
// "Hello world"
```

---

## `normalizeNames(string $text, ?string $locale = null): string`

Complete cleanup + name capitalization.

### Example

```php
TextHelper::normalizeNames("<p>joão   da   silva</p>");
// "João da Silva"
```

---

## `onlyNumbers(string $text): string`

Extracts only numbers from a string.

```php
TextHelper::onlyNumbers('Tel: (61) 99999-0000');
// "61999990000"
```

---

# `plural(string $string, int|array $count, ?string $locale = null): string`

The **plural()** function is the most powerful in `TextHelper`.  
It pluralizes words based on the application's language, using:

1. **Intelligent pluralization via language files**
2. **Automatic fallback to `Str::plural()`**

---

## 📂 How multilingual pluralization works

You create a file:

```
resources/lang/your_language/plurals.php
```

And add only the plurals you want:

```php
return [
    'produtos' => '{1} product|[2,*] products',
    'comentarios' => '{0} no comments|{1} comment|[2,*] comments',
];
```

---

### 🎯 Usage example

```php
TextHelper::plural('produtos', 1);
// "product"

TextHelper::plural('produtos', 5);
// "products"
```

---

## 🌍 Support for multiple languages

If you have:

```
resources/lang/en_US/plurals.php
```

```php
return [
    'produtos' => '{1} product|[2,*] products',
];
```

Then:

```php
TextHelper::plural('produtos', 1, 'en_US');
// "product"

TextHelper::plural('produtos', 5, 'en_US');
// "products"
```

---

## 🧠 Automatic fallback

If the word **doesn't exist** in the `plurals.php` file:

```php
TextHelper::plural('car', 2);
// "cars" (fallback from Str::plural)
```

Never breaks.

---

# Conclusion

The **TextHelper** offers:

-   Text cleaning and normalization
-   Intelligent capitalization by language
-   Accent removal
-   Number extraction
-   Truncation by characters and words
-   **Advanced multilingual pluralization**
-   Full integration with the application's locale system

It is simple, powerful, and fully expandable.
