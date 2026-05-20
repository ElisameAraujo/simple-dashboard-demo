<?php

/*
|--------------------------------------------------------------------------
| Plurals
|--------------------------------------------------------------------------
|
| Este arquivo define regras de pluralização específicas para o idioma
| atual da aplicação. Ele é utilizado exclusivamente pela função
| TextHelper::plural().
|
| Você pode adicionar quantas palavras quiser, seguindo o padrão:
|
|   'produtos' => '{1} produto|[2,*] produtos',
|
| Regras:
| - {1}  → forma usada quando a contagem for exatamente 1
| - [0]  → forma usada quando a contagem for 0 (opcional)
| - [2,*] → forma usada quando a contagem for 2 ou mais
|
| Exemplos:
|
|   'comentarios' => '{0} nenhum comentário|{1} comentário|[2,*] comentários',
|   'pessoa'      => '{1} pessoa|[2,*] pessoas',
|
| O arquivo começa vazio para que você adicione apenas o que precisar.
|
*/

return [
    'comments' => '{0} Nenhum Comentário|{1} comentário|[2,*] comentários',
    'visits' => '{0} visitas|{1} visita|[2,*] visitas',
];
