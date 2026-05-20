<?php

/*
|--------------------------------------------------------------------------
| Plurals
|--------------------------------------------------------------------------
|
| This file defines language-specific pluralization rules for the current
| application locale. It is used exclusively by TextHelper::plural().
|
| You may add as many words as needed, following this pattern:
|
|   'products' => '{1} product|[2,*] products',
|
| Rules:
| - {1}   -> form used when the count is exactly 1
| - {0}   -> form used when the count is 0 (optional)
| - [2,*] -> form used when the count is 2 or greater
|
| Examples:
|
|   'comments' => '{0} no comments|{1} comment|[2,*] comments',
|   'person'   => '{1} person|[2,*] people',
|
| This file starts with only the entries needed by the application.
|
*/

return [
    'comments' => '{0} No Comments|{1} comment|[2,*] comments',
    'visits' => '{0} views|{1} view|[2,*] views',
];
