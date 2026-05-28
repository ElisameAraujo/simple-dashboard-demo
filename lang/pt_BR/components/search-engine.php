<?php

return [
    'spotlight' => [
        'placeholder' => 'Pesquisar no painel...',
        'close' => 'Fechar pesquisa',
        'suggestions' => 'Sugestões',
        'results' => 'Resultados',
        'group_filters' => 'Filtros de grupo da pesquisa',
        'all_groups' => 'Tudo',
        'minimum_chars' => 'Digite pelo menos :count caracteres.',
        'count' => '{0} Nenhum item|{1} :count item|[2,*] :count itens',
        'empty' => 'Nenhum resultado encontrado.',
    ],
    'groups' => [
        'posts' => 'Posts',
        'products' => 'Produtos',
    ],
    'badges' => [
        'post' => 'Post',
        'product' => 'Produto',
    ],
    'demo_edit' => [
        'note' => 'Esta página é uma rota demonstrativa. Em um projeto real, ela seria substituída pela tela de edição do model encontrado no Spotlight.',
        'back' => 'Voltar ao módulo',
        'posts' => [
            'type' => 'Post demo',
            'title' => 'Edição demo de post',
            'description' => 'Destino usado para validar a action de edição dos resultados de posts.',
        ],
        'products' => [
            'type' => 'Produto demo',
            'title' => 'Edição demo de produto',
            'description' => 'Destino usado para validar a action de edição dos resultados de produtos.',
        ],
    ],
    'admin' => [
        'dashboard' => [
            'summary' => 'Visão geral do painel demo.',
            'keywords' => ['inicio', 'home', 'painel', 'dashboard', 'admin'],
        ],
        'helpers' => [
            'title' => 'Resumo dos Helpers',
            'summary' => 'Documentação e exemplos dos helpers globais do painel.',
            'keywords' => ['helpers', 'funcoes', 'utilitarios', 'documentacao', 'core'],
        ],
        'modules' => [
            'title' => 'Resumo dos Módulos',
            'summary' => 'Módulos extras disponíveis no fluxo administrativo.',
            'keywords' => ['modulos', 'extras', 'componentes', 'demo'],
        ],
        'image-preview' => [
            'summary' => 'Preview de imagem para fluxos de criação e edição.',
            'keywords' => ['imagem', 'preview', 'upload', 'create', 'edit'],
        ],
        'visits' => [
            'summary' => 'Registro standalone de visitas e métricas de popularidade.',
            'keywords' => ['visitas', 'popularidade', 'views', 'metricas', 'ranking'],
        ],
        'notifications-ui' => [
            'summary' => 'Interface visual para notificações administrativas.',
            'keywords' => ['notificacoes', 'alertas', 'sino', 'dropdown', 'modal'],
        ],
        'maintenance-mode' => [
            'summary' => 'Controle a disponibilidade pública do site.',
            'keywords' => ['manutencao', 'site offline', '503', 'wordpress', 'site online'],
        ],
        'site-preview' => [
            'summary' => 'Rota pública de exemplo protegida pelo modo de manutenção.',
            'keywords' => ['site', 'preview', 'web', 'publico', 'manutencao'],
        ],
        'profile' => [
            'summary' => 'Gerencie as informações básicas da conta.',
            'keywords' => ['perfil', 'conta', 'usuario', 'email', 'avatar'],
        ],
        'account-notifications' => [
            'summary' => 'Preferências de notificações da conta.',
            'keywords' => ['notificacoes', 'preferencias', 'conta', 'alertas'],
        ],
        'security' => [
            'summary' => 'Configurações de segurança da conta.',
            'keywords' => ['seguranca', 'senha', 'login', 'fingerprint', 'conta'],
        ],
    ],
];
