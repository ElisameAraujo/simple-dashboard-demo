<?php

return [
    'kicker' => 'Simple Dashboard',
    'intro' => [
        'title' => 'Resumo geral da demo',
        'description' => 'Esta tela centraliza o estado atual do painel, os caminhos já disponíveis para navegação e alguns links de referência para continuar a implementação.',
    ],
    'actions' => [
        'profile' => 'Ver perfil',
    ],
    'summary' => [
        'stack' => [
            'label' => 'Stack principal',
            'description' => 'Base com Livewire, Tailwind CSS, DaisyUI e FontAwesome.',
        ],
        'helpers' => [
            'label' => 'Helpers documentados',
            'description' => 'Classes utilitárias com documentação traduzível.',
        ],
        'locales' => [
            'label' => 'Idiomas preparados',
            'description' => 'Interface com arquivos em Português do Brasil e Inglês.',
        ],
        'pages' => [
            'label' => 'Páginas da demo',
            'description' => 'Dashboard, perfil, notificações e segurança.',
        ],
    ],
    'sections' => [
        'available_pages' => [
            'title' => 'Páginas disponíveis',
            'description' => 'Atalhos para as telas que já fazem parte da demo.',
        ],
        'next_steps' => [
            'title' => 'Próximos passos',
            'description' => 'Fila inicial para expandir a demo.',
        ],
        'useful_links' => [
            'title' => 'Links úteis',
            'description' => 'Referências rápidas para documentação e código.',
        ],
        'helper_docs' => [
            'title' => 'Documentação dos helpers',
            'description' => 'Metadados traduzidos em lang/pt_BR/docs/helpers/{helper}.php.',
        ],
    ],
    'demo_pages' => [
        'profile' => [
            'title' => 'Perfil',
            'description' => 'Exemplo de área administrativa para dados da conta e imagem de perfil.',
        ],
        'notifications' => [
            'title' => 'Notificações',
            'description' => 'Preferências, períodos de pausa e controles com checkboxes.',
        ],
        'security' => [
            'title' => 'Segurança',
            'description' => 'Fluxo visual para senha, recuperação de acesso e remoção de conta.',
        ],
    ],
    'status' => [
        'available' => 'Disponível',
    ],
    'useful_links' => [
        'readme' => [
            'label' => 'README do projeto',
            'description' => 'Visão geral, instalação e requisitos.',
        ],
        'repository' => [
            'label' => 'Repositório',
            'description' => 'Código-fonte e histórico público.',
        ],
    ],
    'next_steps' => [
        'Adicionar páginas administrativas com tabelas e filtros.',
        'Criar exemplos de formulários com validação real.',
        'Incluir componentes Livewire reutilizáveis na demo.',
    ],
];
