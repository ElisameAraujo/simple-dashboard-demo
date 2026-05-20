# 📆 DateHelper

O **DateHelper** fornece funções utilitárias para formatação de datas totalmente integradas ao **idioma da aplicação** e ao **timezone configurado no `.env`**.

Ele utiliza:

-   `LocaleHelper::resolveLocale()` para determinar o idioma
-   `config('app.timezone')` para aplicar o fuso horário
-   Arquivos de tradução `resources/lang/{locale}/dates.php`  
    (onde ficam meses, dias da semana, formatos e textos humanizados)

Assim, **todas as datas são exibidas automaticamente no idioma correto**, sem necessidade de ajustes manuais.

---

## 📂 Funções disponíveis

---

### `currentYear()`

Retorna o ano atual no formato `YYYY`.

```php
DateHelper::currentYear();
// 2025
```

---

### `currentDate()`

Retorna a data atual usando o formato definido em `dates.php` para o idioma ativo.

Exemplo (pt_BR):

```php
DateHelper::currentDate();
// 28/10/2025
```

Exemplo (en_US):

```php
DateHelper::currentDate();
// 10/28/2025
```

---

### `fullCurrentDate()`

Retorna a data atual por extenso, com dia da semana e mês traduzidos.

```php
DateHelper::fullCurrentDate();
// domingo, 18 de outubro de 2025
```

---

### `fullExtendedDate(string $date)`

Formata uma data recebida via string usando o formato completo do idioma.

```php
DateHelper::fullExtendedDate('2025-10-18');
// domingo, 18 de outubro de 2025
```

---

### `currentFullDateWithHours(string $date)`

Formata uma data por extenso + hora, usando o formato definido no idioma.

```php
DateHelper::currentFullDateWithHours('2025-10-18 13:26');
// 18 de outubro de 2025 às 13:26
```

---

### `diffDatesHuman(string $date)`

Retorna a diferença entre a data e agora em formato humanizado, com textos traduzidos.

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

Formata uma data com hora e segundos, usando o formato definido no idioma.

```php
DateHelper::dateWithHoursAndSeconds('2025-08-18 18:36:41');
// 18/08/2025 às 18:36:41
```

---

### `dateWithHours(string $date)`

Formata uma data com hora (sem segundos).

```php
DateHelper::dateWithHours('2025-08-18 18:36:41');
// 18/08/2025 às 18:36
```

---

### `simpleDate(string $date)`

Formata uma data simples (dia, mês e ano), conforme o idioma.

```php
DateHelper::simpleDate('2025-08-18');
// 18/08/2025
```

---

### `isTodayCheck(string $date)`

Verifica se a data informada é o dia atual.

```php
DateHelper::isTodayCheck('2025-08-18');
// true
```

---

### `daysDifference(string $startDate, string $endDate)`

Retorna a diferença em dias entre duas datas.

```php
DateHelper::daysDifference('2025-12-15', '2025-12-18');
// 3
```

---

### `shortDate(string $date)`

Exibe apenas o dia e mês, usando o formato curto do idioma.

```php
DateHelper::shortDate('2025-12-15');
// 15/12
```

---

### `shortTime(string $date)`

Exibe apenas hora e minuto, usando o formato curto do idioma.

```php
DateHelper::shortTime('2025-12-15 14:36:52');
// 14:36
```
