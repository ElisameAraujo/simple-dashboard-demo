<p align="center" style="display: flex; justify-content:center; gap: 10px; width: 100%">
<img alt="Static Badge" src="https://img.shields.io/badge/Laravel%2013-version?style=plastic&logo=laravel&logoColor=white&labelColor=%23FF2D20&color=black">
<img alt="Static Badge" src="https://img.shields.io/badge/DaisyUI%205-version?style=plastic&logo=daisyui&logoColor=white&labelColor=%231AD1A5&color=black">
<img alt="Static Badge" src="https://img.shields.io/badge/Livewire%204-version?style=plastic&logo=livewire&logoColor=white&labelColor=%234E56A6&color=black">
<img alt="Static Badge" src="https://img.shields.io/badge/FontAwesome%207-version?style=plastic&logo=fontawesome&logoColor=white&labelColor=%23538DD7&color=black">
</p>

<div style="display: flex; justify-content:center; gap: .4em; width: 100%">
<a href="https://github.com/ElisameAraujo/simple-dashboard">
Readme (English)
</a>
|
<a href="https://github.com/ElisameAraujo/simple-dashboard/blob/main/README.pt-br.md">
Readme (Português do Brasil)
</a>
</div>

# Simple Dashboard

Um painel administrativo simples, moderno e funcional, construído com:

-   **Laravel 13+**
-   **Livewire 4+**
-   **Tailwind CSS 4+**
-   **DaisyUI 5+**
-   **FontAwesome 7+**

O objetivo deste projeto é servir como **base inicial** para criação de dashboards, oferecendo uma estrutura limpa, organizada e com um conjunto de _helpers_ prontos para uso.

Este projeto é fornecido **AS IS** — como está.  
Atualizações podem ocorrer ocasionalmente, caso eu considere necessário.

---

# 🚀 Instalação Rápida

```bash
git clone https://github.com/ElisameAraujo/simple-dashboard.git
cd simple-dashboard

composer install
npm install
npm run build

cp .env.example .env
php artisan key:generate

composer run dev
```

---

# 📦 Requisitos

-   **PHP 8.3+**
-   **Laravel 13+**
-   **Node 20+**
-   **Composer 2+**

---

# 🗂 Estrutura do Projeto (Resumo)

```
.
├── app/
│   ├── ...
│   ├── Helpers (Global Helpers)/
│   │   ├── Support/
│   │   │   └── LocaleResolver.php
│   │   ├── DateHelper.php
│   │   ├── DiskHelper.php
|   |   ├── HTMLHelper.php
│   │   ├── MediaHelper.php
│   │   ├── NotificationHelper.php
│   │   ├── NumberHelper.php
│   │   ├── PaginationHelper.php
│   │   ├── RouteHelper.php
│   │   ├── RuleHelper.php
│   │   ├── TextHelper.php
│   │   └── UserHelper.php
│   ├── ...
│   └── Providers/
│       ├── ...
│       └── HelperServiceProvider.php (Service Provider for Helpers)
├── ...
├── config/
│   ├── ...
│   └── helpers.php (Helpers Registry)
├── ...
├── documentation/
│   ├── en-US/
│   │   ├── DateHelper.md
│   │   ├── DiskHelper.md
│   │   ├── HTMLHelper.md
│   │   ├── MediaHelper.md
│   │   ├── NotificationHelper.md
│   │   ├── NumberHelper.md
│   │   ├── PaginationHelper.md
│   │   ├── RouteHelper.md
│   │   ├── RuleHelper.md
│   │   ├── TextHelper.md
│   │   └── UserHelper.md
│   └── pt-BR/
│       ├── DateHelper.md
│       ├── DiskHelper.md
│       ├── HTMLHelper.md
│       ├── MediaHelper.md
│       ├── NotificationHelper.md
│       ├── NumberHelper.md
│       ├── PaginationHelper.md
│       ├── RouteHelper.md
│       ├── RuleHelper.md
│       ├── TextHelper.md
│       └── UserHelper.md
├── lang/
│   ├── en/
│   │   ├── dates.php
│   │   ├── error_messages.php
│   │   ├── plurals.php
│   │   └── ui.php
│   └── pt-BR/
│       ├── dates.php
│       ├── error_messages.php
│       ├── plurals.php
│       └── ui.php
├── ...
├── resources/
│   ├── css/
│   │   ├── admin/
│   │   │   └── components/
│   │   │       ├── dark.css
│   │   │       ├── header.css
│   │   │       ├── profile-options.css
│   │   │       └── sidebar.css
│   │   ├── global/
│   │   │   ├── theme.css
│   │   │   └── utilities.css
│   │   └── web/
│   │       ├── style.css
│   │       └── web.css
│   ├── js/
│   │   ├── admin/
│   │   │   ├── admin.js
│   │   │   ├── mobile-menu.js
│   │   │   └── submenu.js
│   │   └── web/
│   │       └── web.js
│   └── views/
│       ├── admin/
│       │   ├── dashboard/
│       │   │   └── index.blade.php
│       │   └── profile/
│       │       ├── my-profile.blade.php
│       │       ├── notifications.blade.php
│       │       └── security.blade.php
│       ├── components/
│       │   └── admin/
│       │       ├── header.blade.php
│       │       ├── menu-structrure.blade.php
│       │       ├── side-menu.blade-mobile.php
│       │       └── side-menu.blade.php
│       ├── layouts/
│       │    └── admin.blade.php
|       └── web/
├── routes/
│   ├── admin/
│   │   ├── dashboard/
│   │   │   └── dashboard-routes.php
│   │   └── profile/
│   │       └── profile-routes.php
|   └── web/
└── ...
```

---

# 🧰 Helpers

Esse projeto já tem criado uma pequena lista de helpers com funções estáticas que podem ser acessadas globalmente via `NomeDaClasse::funcao()`.

Você pode criar novos helpers dentro da pasta `App\Helpers` e registrá-los dentro do arquivo `config\helpers.php` na chave `global`.

Você pode conferir o que cada helper e função faz na pasta [**`/documentation`**](https://github.com/ElisameAraujo/simple-dashboard/tree/main/documentation). Lá você vai encontrar arquivos específicos para cada classe e também para as funções dentro de cada classe. Dentro das classes você também encontra comentários para as funções mais específicas.

Os helpers disponíveis atualmente são:

| Helper                 | Descrição                                    |
| ---------------------- | -------------------------------------------- |
| **DateHelper**         | Manipulação e formatação de datas            |
| **DiskHelper**         | Gerenciamento de discos e caminhos           |
| **HTMLHelper**         | Criação de HTML para factories               |
| **MediaHelper**        | Exibição e manipulação de mídias             |
| **NotificationHelper** | Gerenciamento de notificações do Laravel     |
| **NumberHelper**       | Formatação numérica multilíngue              |
| **PaginationHelper**   | Criação de paginação com múltiplas partes    |
| **RoutesHelper**       | Importa as rotas da aplicação de forma fácil |
| **RuleHelper**         | Extração de valores de regras ou classes DTO |
| **TextHelper**         | Limpeza, normalização e pluralização         |
| **UserHelper**         | Acesso rápido a dados do usuário             |

---

# 🎨 Temas (DaisyUI)

Este painel utiliza o [**DaisyUI 5+**](https://daisyui.com/), que oferece suporte nativo a temas e contém uma biblioteca de [componentes](https://daisyui.com/components/) prontos para uso.

🔗 **Lista de Temas Oficiais:**

[https://daisyui.com/docs/themes/](https://daisyui.com/docs/themes/)

🔗 **Gerador de Temas:**

[https://daisyui.com/theme-generator/](https://daisyui.com/theme-generator/)

Definindo um tema:

```html
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
    <head>
        ...
    </head>
</html>
```

Se você quiser substituir ou editar o tema atual, basta editar o arquivo `theme.css` dentro de `resources/global`.

---

# 🧩 Pacotes Inclusos

### 📦 NPM

-   **[Theme Change](https://github.com/saadeghi/theme-change)** — alternância de temas com persistência via Cookie

### 📦 Composer

-   **[Spatie Media Library](https://github.com/spatie/laravel-medialibrary)** — gerenciamento de mídias que estão ligadas as Models do Eloquent
-   **[Spatie Laravel Permission](https://github.com/spatie/laravel-permission)** — Gerenciamento de cargos e permissões
-   **[Log Viewer](https://log-viewer.opcodes.io/)** — Permite ler os seus logs do Laravel de maneira mais clara e organizada

---

# ❓ FAQ

### **Posso usar este painel em produção?**

Sim, mas ele é fornecido _AS IS_. Ajuste conforme suas necessidades.

### **Posso remover os pacotes que já vem instalados?**

Sim, fique a vontade! O projeto base é apenas um norte na hora de você montar seu painel de administração, então se os pacotes não são necessários ou não são do seu gosto basta usar os comandos do Composer ou NPM para removê-los.

### **Posso adicionar meus próprios helpers?**

Sim. Basta criar em `app/Helpers` e registrar em `config/helpers.php`.

### **O painel recebe atualizações frequentes?**

Eu posso atualizar o projeto para ele suportar versões mais novas dos pacotes já disponíveis aqui, como também remover ou adicionar novos pacotes. Mas isso, pode ocorrer apenas ocasionalmente, caso eu ache necessário.

### **Posso criar forks e variantes?**

Sim, fique à vontade.

---

# 🤝 Contribuição

Contribuições são bem-vindas, especialmente para:

-   Traduções da interface
-   Expansão do dicionário de plurais
-   Melhorias nos helpers
-   Correções gerais

Para contribuir:

```bash
git checkout -b minha-melhoria
git commit -m "Melhoria X"
git push origin minha-melhoria
```

Depois abra um **Pull Request**.
