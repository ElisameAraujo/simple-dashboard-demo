# 🚦 RouteHelper

O **RouteHelper** é uma classe utilitária que facilita a organização e importação de arquivos de rota no Laravel.  
Ele oferece duas formas principais de carregar rotas:

-   **importRouteFile** → Importa um arquivo específico
-   **importRoutesFromFolder** → Importa todos os arquivos de uma pasta (com subpastas opcionais e exclusões)

Além disso, inclui um método para listar todas as rotas registradas na aplicação.

---

# 📌 Estrutura Geral

O helper trabalha sempre dentro da pasta:

```
routes/
```

E permite navegar por subpastas de forma simples e previsível.

---

# 📂 Funções Disponíveis

---

## `importRouteFile(string $filename, string|array|null $folders = null)`

Importa **um único arquivo de rota**, localizado em `routes/` ou em subpastas.

### **Exemplo 1 — arquivo na raiz**

```php
RouteHelper::importRouteFile('admin');
```

Carrega:

```
routes/admin.php
```

---

### **Exemplo 2 — arquivo em uma subpasta**

```php
RouteHelper::importRouteFile('users', 'admin');
```

Carrega:

```
routes/admin/users.php
```

---

### **Exemplo 3 — arquivo em múltiplos níveis de subpastas**

```php
RouteHelper::importRouteFile('orders', ['ecommerce', 'v2']);
```

Carrega:

```
routes/ecommerce/v2/orders.php
```

---

## `importRoutesFromFolder(string $rootFolder, string|array|null $subfolders = null, string|array|null $except = null)`

Importa **todos os arquivos `.php`** dentro de uma pasta específica, com suporte a:

-   subpastas opcionais
-   exclusão de arquivos específicos

Essa função é ideal quando você organiza suas rotas por módulos ou seções.

---

## Estrutura

-   `$rootFolder`: Pasta raiz dentro de `routes/`
-   `$subfolders` _(opcional)_: Recebe uma string ou um array de subpastas em que o arquivo de rotas está localizado. Se ignorado, ele irá importar os arquivos de rota apenas na raiz da pasta que está dentro de `routes/`. Se passado algum valor, irá buscar apenas os arquivos que estão dentro da subpasta.
-   `$except` _(opcional)_: Também é opcional. Aqui você pode definir quais arquivos deseja ignorar dentro da pasta raiz. Se nenhum valor for passado, ele irá importar todos os arquivos.

---

# 🧪 Exemplos de Uso

---

## ✔ Importar todos os arquivos da pasta raiz

```php
RouteHelper::importRoutesFromFolder('admin');
```

Carrega:

```
routes/admin/*.php
```

---

## ✔ Importar arquivos de uma subpasta

```php
RouteHelper::importRoutesFromFolder('admin', 'v2');
```

Carrega:

```
routes/admin/v2/*.php
```

---

## ✔ Importar arquivos de múltiplas subpastas (hierarquia)

```php
RouteHelper::importRoutesFromFolder('admin', ['ecommerce', 'v2']);
```

Carrega:

```
routes/admin/ecommerce/v2/*.php
```

---

## ✔ Importar tudo, exceto alguns arquivos

```php
RouteHelper::importRoutesFromFolder('admin', null, ['auth', 'debug']);
```

Ignora:

```
routes/admin/auth.php
routes/admin/debug.php
```

---

## ✔ Importar de subpastas e ignorar arquivos específicos

```php
RouteHelper::importRoutesFromFolder('admin', ['ecommerce', 'v2'], 'experimental');
```

Ignora:

```
routes/admin/ecommerce/v2/experimental.php
```

---

## `listAllRoutes()`

Retorna uma lista com todas as rotas registradas na aplicação.

### Exemplo:

```php
$routes = RouteHelper::listAllRoutes();
```

Retorno:

```php
[
    [
        'uri' => 'admin/users',
        'name' => 'admin.users.index',
        'method' => 'GET|HEAD',
        'action' => 'App\Http\Controllers\Admin\UserController@index',
    ],
    ...
]
```

Útil para:

-   debug
-   geração de documentação
-   inspeção de rotas em ambiente de desenvolvimento
