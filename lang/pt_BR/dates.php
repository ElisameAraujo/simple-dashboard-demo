<?php

return [
    'weekdays' => [
        'sunday' => 'Domingo',
        'monday' => 'Segunda',
        'tuesday' => 'Terça',
        'wednesday' => 'Quarta',
        'thursday' => 'Quinta',
        'friday' => 'Sexta',
        'saturday' => 'Sábado',
    ],

    'weekdays_short' => [
        'sunday' => 'dom',
        'monday' => 'seg',
        'tuesday' => 'ter',
        'wednesday' => 'qua',
        'thursday' => 'qui',
        'friday' => 'sex',
        'saturday' => 'sáb',
    ],

    'weekdays_short_capitalized' => [
        'Dom',
        'Seg',
        'Ter',
        'Qua',
        'Qui',
        'Sex',
        'Sáb',
    ],

    'weekdays_extended' => [
        'sunday' => 'domingo',
        'monday' => 'segunda-feira',
        'tuesday' => 'terça-feira',
        'wednesday' => 'quarta-feira',
        'thursday' => 'quinta-feira',
        'friday' => 'sexta-feira',
        'saturday' => 'sábado',
    ],

    'months' => [
        'january' => 'Janeiro',
        'february' => 'Fevereiro',
        'march' => 'Março',
        'april' => 'Abril',
        'may' => 'Maio',
        'june' => 'Junho',
        'july' => 'Julho',
        'august' => 'Agosto',
        'september' => 'Setembro',
        'october' => 'Outubro',
        'november' => 'Novembro',
        'december' => 'Dezembro',
    ],

    'months_short' => [
        'january_short' => 'Jan',
        'february_short' => 'Fev',
        'march_short' => 'Mar',
        'april_short' => 'Abr',
        'may_short' => 'Mai',
        'june_short' => 'Jun',
        'july_short' => 'Jul',
        'august_short' => 'Ago',
        'september_short' => 'Set',
        'october_short' => 'Out',
        'november_short' => 'Nov',
        'december_short' => 'Dez',
    ],

    'months_simple' => [
        'jan.',
        'fev.',
        'mar.',
        'abr.',
        'mai.',
        'jun.',
        'jul.',
        'ago.',
        'set.',
        'out.',
        'nov.',
        'dez.',
    ],

    'diff' => [
        'past' => ':time atrás',
        'future' => 'Daqui a :time',

        'year' => ['one' => '1 ano', 'many' => ':count anos'],
        'month' => ['one' => '1 mês', 'many' => ':count meses'],
        'week' => ['one' => '1 semana', 'many' => ':count semanas'],
        'day' => ['one' => '1 dia', 'many' => ':count dias'],
        'hour' => ['one' => '1 hora', 'many' => ':count horas'],
        'minute' => ['one' => '1 minuto', 'many' => ':count minutos'],
        'second' => ['one' => '1 segundo', 'many' => ':count segundos'],
        'now' => 'agora mesmo',
    ],

    'formats' => [
        'date' => 'd/m/Y',
        'date_time' => 'd \d\e F \d\e Y',
        'date_time_short' => 'd/m/Y \à\s H:i',
        'date_time_short_seconds' => 'd/m/Y \à\s H:i:s',
        'date_time_extended' => 'd \d\e F \d\e Y \à\s H:i',
        'full_weekday' => 'l, d \d\e F \d\e Y',
        'short_date' => 'd/m',
        'short_time' => 'H:i',
        'date_excel' => 'd/m/Y H:i',
    ],

    'email' => [
        'format' => ':weekday, :day de :month, :time',
        'wrapper' => '(:relative)',
    ],
];
