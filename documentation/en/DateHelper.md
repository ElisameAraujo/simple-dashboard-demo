# 📆 DateHelper

The **DateHelper** provides utility functions for data formatting fully integrated with the **application language** and the **timezone configured in the `.env` file**.

It uses:

-   `LocaleHelper::resolveLocale()` to determine the language
-   `config('app.timezone')` to apply the timezone
-   Translation files `resources/lang/{locale}/dates.php`

(where months, days of the week, formats, and humanized text are stored)

Then, **all information is automatically displayed in the correct language**, without the need for manual adjustments.

---

## 📂 Available Functions

---

### `currentYear()`

Returns the current year in the format `YYYY`.

```php
DateHelper::currentYear();
// 2025
```

---

### `currentDate()`

Returns the current date using the format defined in `dates.php` for the active language.

Example _**(pt_BR)**_:

```php
DateHelper::currentDate();
// 28/10/2025
```

Example _**(en_US)**_:

```php
DateHelper::currentDate();
// 10/28/2025
```

---

### `fullCurrentDate()`

Returns the current date in full, with the day of the week and month translated.

```php
DateHelper::fullCurrentDate();
// domingo, 18 de outubro de 2025
```

---

### `fullExtendedDate(string $date)`

Formats a date received as a string using the full language format.

```php
DateHelper::fullExtendedDate('2025-10-16');
// domingo, 16 de outubro de 2025
```

---

### `currentFullDateWithHours(string $date)`

Formats a date in words + time, using the format defined in the language.

```php
DateHelper::currentFullDateWithHours('2025-10-18 13:26');
// 18 de outubro de 2025 às 13:26
```

---

### `diffDatesHuman(string $date)`

Returns the difference between the current date and the present time in a humanized format.

```php
DateHelper::diffDatesHuman('2025-10-18 18:36:41');
// 20 segundos atrás

DateHelper::diffDatesHuman('2025-08-18 18:36:41');
// 2 meses atrás

DateHelper::diffDatesHuman('2025-12-18 18:36:41');
// daqui a 2 meses
```

---

### `dateWithHoursAndSeconds(string $date)`

Formats a date with time and seconds, using the format defined in the language.

```php
DateHelper::dateWithHoursAndSeconds('2025-08-18 18:36:41');
// 18/08/2025 às 18:36:41
```

---

### `dateWithHours(string $date)`

Format a date with time (without seconds).

```php
DateHelper::dateWithHours('2025-08-18 18:36:41');
// 18/08/2025 às 18:36
```

---

### `simpleDate(string $date)`

Formats a simple date (day, month, and year) according to the language.

```php
DateHelper::simpleDate('2025-08-18');
// 18/08/2025
```

---

### `isTodayCheck(string $date)`

Check if the date entered is the current day.

```php
DateHelper::isTodayCheck('2025-08-18');
// true
```

---

### `daysDifference(string $startDate, string $endDate)`

Returns the difference in days between two dates.

```php
DateHelper::daysDifference('2025-12-15', '2025-12-18');
// 3
```

---

### `shortDate(string $date)`

It displays only the day and month, using the short format for the language.

```php
DateHelper::shortDate('2025-12-15');
// 15/12
```

---

### `shortTime(string $date)`

It displays only the hour and minute, using the short format for the language.

```php
DateHelper::shortTime('2025-12-15 14:36:52');
// 14:36
```
