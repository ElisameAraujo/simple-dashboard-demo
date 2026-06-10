# Simple Dashboard Demo

Este repositório contém a demo funcional do Simple Dashboard. Ele existe para mostrar, testar e documentar os helpers globais e os módulos extras disponíveis no painel administrativo.

A demo é independente do repositório base. Se você quer a base limpa para iniciar um projeto, use o [`simple-dashboard`](https://github.com/ElisameAraujo/simple-dashboard). Se você quer explorar os recursos funcionando, use este repositório de demo.

## Stack

- Laravel 13
- Livewire 4
- Tailwind CSS 4
- DaisyUI 5
- FontAwesome 7
- Vite 8
- SQLite para desenvolvimento local

## O Que Existe Na Demo

### Helpers

Os helpers ficam em `app/Helpers` e são documentados pela própria UI da demo.

| Helper               | Foco                                                             |
| -------------------- | ---------------------------------------------------------------- |
| `DateHelper`         | Datas, períodos e textos relativos.                              |
| `DiskHelper`         | Upload, troca, remoção e URL de arquivos em disks Laravel.       |
| `HTMLHelper`         | Geração de HTML fake para demos, previews e documentação.        |
| `MediaHelper`        | Resolução, exibição, download e MIME type de mídias.             |
| `NotificationHelper` | Leitura de notificações Laravel do usuário autenticado.          |
| `NumberHelper`       | Formatação de números, moedas, áreas e ordinais por locale.      |
| `RouteHelper`        | Importação organizada de arquivos e pastas de rotas.             |
| `RuleHelper`         | Extração de valores de regras Laravel.                           |
| `TextHelper`         | Limpeza, normalização, pluralização, slugs e textos de UI.       |
| `UserHelper`         | Acesso seguro a dados básicos do usuário e extras de permissões. |

### Módulos

Os módulos ficam na área **Módulos / Extras** dentro do painel.

| Módulo             | O que demonstra                                                                |
| ------------------ | ------------------------------------------------------------------------------ |
| `ImagePreview`     | Preview desacoplado de imagens em formulários Livewire de create e edit.       |
| `Visits`           | Registro standalone de visitas e escopos de popularidade para models Eloquent. |
| `Notifications UI` | Interface visual de notificações administrativas com dados mockados.           |
| `Maintenance Mode` | Modo de manutenção no estilo WordPress, sem derrubar o painel admin.           |
| `Search Engine`    | Motor de busca para Spotlight, web search, models, statics e tabelas Livewire. |
| `Rich Text Media`  | Upload, commit e limpeza de imagens embutidas em editores WYSIWYG.             |

## Instalação

Clone o repositório:

```bash
git clone https://github.com/ElisameAraujo/simple-dashboard-demo.git
cd simple-dashboard-demo
```

Instale dependências PHP e JavaScript:

```bash
composer install
npm install
```

Crie o `.env`, gere a chave e prepare o banco:

```bash
cp .env.example .env
php artisan key:generate
php artisan migrate
```

Se quiser recriar os dados demonstrativos:

```bash
composer demo:fresh
```

Gere os assets:

```bash
npm run build
```

## Rodando A Demo

Use o script de desenvolvimento:

```bash
composer run dev
```

Ele inicia servidor Laravel, Vite e queue listener.

Depois acesse:

```text
http://127.0.0.1:8000
```

## Fluxo Manual De Teste

### Painel

1. Acesse `/`.
2. Troque o idioma pelo seletor do header ou do menu mobile.
3. Troque o tema claro/escuro.
4. Abra o Spotlight com `Ctrl+K` ou pelo campo de busca.
5. Pesquise por termos como `manutenção`, `visitas`, `mídia`, `produto` ou `post`.

### Helpers

1. Acesse `/helpers`.
2. Abra cada helper pelo menu lateral.
3. Confira exemplos, métodos e documentação gerada por YAML.

### Módulos

1. Acesse `/modules`.
2. Abra `ImagePreview` e teste os estados create/edit.
3. Abra `Notifications UI` e teste dropdown, modal e estados de notificação.
4. Abra `Maintenance Mode`, ative a manutenção e teste `/site-preview`.
5. Abra `Search Engine` e navegue pelas subpáginas de arquitetura, Spotlight, web e Livewire.
6. Abra `Rich Text Media` e veja os exemplos de integração com TinyMCE, CKEditor, Quill, Froala, Tiptap e Lexical.

### Busca Web

1. Acesse `/site-preview`.
2. Use o dropdown de busca no navbar.
3. Acesse `/site-preview/search?q=midia` para ver a página de resultados.

### Mobile

1. Reduza a largura do navegador.
2. Abra o menu mobile.
3. Teste idioma, tema, notificações e manutenção.
4. Confira se dropdowns e modais não ficam presos dentro da sidebar.

## Comandos De Validação

Build de assets:

```bash
npm run build
```

Testes dos módulos:

```bash
php artisan test tests/Feature/Modules
```

Testes do Search Engine:

```bash
php artisan test --filter=SearchEngineTest
```

Testes do modo de manutenção:

```bash
php artisan test --filter=MaintenanceModeTest
```

Testes de tradução e documentação dos helpers:

```bash
php artisan test tests/Feature/Localization
```

## Documentação Interna

A documentação exibida pela UI vem de YAML:

```text
resources/docs/helpers/{locale}
resources/docs/modules/{locale}
```

Ao alterar o contrato público de um helper ou módulo, atualize os arquivos em `en` e `pt_BR` e rode os testes de documentação correspondentes.

## Diferença Entre Demo E Base

[`simple-dashboard-demo`](https://github.com/ElisameAraujo/simple-dashboard-demo) contém telas vivas, dados fake, exemplos visuais e testes de comportamento.

[`simple-dashboard`](https://github.com/ElisameAraujo/simple-dashboard) é a base limpa para uso real: core, componentes reutilizáveis e documentação de implementação, sem dados fake ou páginas demonstrativas desnecessárias.

## Observações

- O pacote `wire-elements/modal` é usado para modais Livewire que precisam de estado/validação.
- Modais simples de confirmação usam DaisyUI.
- O Search Engine e o Rich Text Media são configuráveis por projeto e não impõem uma UI final.
- A demo pode ter mais código didático que o projeto base porque seu objetivo é ensinar e validar os fluxos.
