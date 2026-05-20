# 🧩 UserHelper

O UserHelper fornece funções utilitárias para acessar informações do usuário autenticado, gerar dados derivados (como iniciais, avatar fallback, nome abreviado), manipular email e integrar com o pacote Spatie/Laravel Permission.

Ele centraliza operações comuns relacionadas ao usuário, reduz duplicação de código e mantém o painel mais limpo e consistente.

---

# 📂 Funções disponíveis

---

## `userLogged(): bool`

Verifica se existe um usuário autenticado.

### Exemplo

```php
if (UserHelper::userLogged()) {
    // Usuário autenticado
}
```

---

## `info(string $column, $default = null)`

Retorna qualquer coluna do usuário autenticado.

### Parâmetros

| Parâmetro  | Tipo   | Descrição                                |
| ---------- | ------ | ---------------------------------------- |
| `$column`  | string | Nome da coluna no model User             |
| `$default` | mixed  | Valor retornado caso a coluna não exista |

### Exemplo

```php
UserHelper::info('created_at');
//2025-08-17 12:36:44
```

---

## `userIsActive(string $column = 'active'): bool`

Verifica se uma coluna booleana indica que o usuário está ativo.

### Exemplo

```php
UserHelper::userIsActive(); // usa "active"
UserHelper::userIsActive('status'); // coluna customizada
```

---

## `userId(string $column = 'id')`

Retorna o ID do usuário.

---

## `username(string $column = 'name')`

Retorna o nome completo do usuário.

---

## `userFirstName(string $column = 'name')`

Retorna apenas o primeiro nome.

### Exemplo

```php
UserHelper::userFirstName(); // "João"
```

---

## `userShortName(string $column = 'name')`

Retorna nome + sobrenome abreviado.

### Exemplo

```php
UserHelper::userShortName();
//"João S."
```

---

## `userAvatar(string $column = 'avatar', string $disk = 'public')`

Retorna a URL final do avatar usando a classe `MediaHelper`.

---

## `userAvatarPath(string $column = 'avatar')`

Retorna apenas o caminho salvo no banco.

---

## `userAvatarFallback(string $column = 'name'): array`

Gera dados para um avatar fallback para quando não há foto do usuário definida.

```php

UserHelper::userAvatarFallback(); // João Paulo

// Retorno
[
    'initials' => 'JP',
    'color' => '#3498db'
]
```

#### Exemplo de uso no Front-End

```html
<div style="background: {{ $avatar['color'] }}">{{ $avatar['initials'] }}</div>
```

---

## `userEmail(string $column = 'email')`

Retorna o email do usuário.

---

## `emailDomain(string $column = 'email')`

Retorna o domínio do email.

```php
UserHelper::emailDomain(); // "gmail.com"
```

---

## `maskEmail(string $email, ?int $charactersToMask = null, ?string $position = null)`

Mascara o email para exibição segura, permitindo personalizar **quantos caracteres** serão mascarados e **onde** a máscara será aplicada.

A função é completa e permite definir:

-   Se **nenhum parâmetro** for passado → **mascara todo o email antes do @**
-   Se `charactersToMask = 0` → **ignora o parâmetro e mascara todo o email antes do @**
-   Se `charactersToMask` for maior que o tamanho disponível → **mascara tudo**
-   `position` só funciona quando `charactersToMask > 0`
-   Posições possíveis:
    -   `start` → máscara no começo
    -   `middle` → máscara no meio
    -   `end` (padrão) → máscara no final

### Exemplos

#### 🔹 Sem parâmetros (mascara tudo)

```php
UserHelper::maskEmail('meuemail@gmail.com');
// ********@gmail.com
```

#### 🔹 charactersToMask = 0 (ignora e mascara tudo)

```php
UserHelper::maskEmail('meuemail@gmail.com', 0);
// ********@gmail.com
```

#### 🔹 Mascara no começo (`start`)

```php
UserHelper::maskEmail('meuemaildoprojeto@gmail.com', 4, 'start');
// ****maildoprojeto@gmail.com
```

#### 🔹 Mascara no final (`end`)

```php
UserHelper::maskEmail('meuemaildoprojeto@gmail.com', 4);
// meuemaildopro****@gmail.com
```

#### 🔹 Mascara no meio (`middle`)

```php
UserHelper::maskEmail('meuemaildoprojeto@gmail.com', 4, 'middle');
// meuemaild****jeto@gmail.com
```

#### 🔹 Quando `charactersToMask` excede o limite de caracteres disponíveis, ele irá mascarar todos os caracteres disponíveis antes de `@`

```php
UserHelper::maskEmail('email@gmail.com', 10);
// ****@gmail.com
```

---

## `sanitizeEmail(string $email)`

Limpa um email removendo caracteres inválidos e deixando minúsculo.

### Exemplo

```php
UserHelper::sanitizeEmail(" JOAO@GMAIL.COM  ");
// "joao@gmail.com"
```

---

## `userSummary()`

Retorna um array simples com:

```php
UserHelper::userSummary();

// Retorno
[
    'id' => 1,
    'name' => 'João da Silva',
    'email' => 'joao@gmail.com'
]
```

---

## `userShortSummary()`

Retorna uma string curta:

```php
UserHelper::userShortSummary();
// João S. — joao@gmail.com
```

---

# Integração com `spatie/laravel-permission`

Se você pretende usar o pacote `spatie/laravel-permission` que está incluso nesse template, esse helper já traz algumas funções para acelerar seu trabalho com ele.

---

## `userHasRole(string $role): bool`

Verifica se o usuário possui um cargo.

---

## `userHasPermission(string $permission): bool`

Verifica se o usuário possui uma permissão.

---

## `userRoles(): array`

Retorna um array com todos os cargos do usuário.

### Exemplo

```php
UserHelper::userRoles();

// ["admin", "editor"]
```

---

## `userPermissions(): array`

Retorna um array de todas as permissões do usuário.

```
UserHelper::userPermissions();

// ["create", "edit", "delete"]
```

---

## `allRoles()`

Retorna todas as permissões existentes na model `Permission` do _Laravel Permission_, útil para ser usado em `selects` na hora de atribuir um cargo para algum usuário.

---

## `allPermissions()`

Retorna todos os cargos existentes na model `Role` do _Laravel Permission_, útil para ser usado em `selects` na hora de atribuir uma ou mais permissões para algum usuário.
