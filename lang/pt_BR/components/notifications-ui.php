<?php

return [
    'title' => 'Notificações',
    'trigger_label' => 'Abrir preview de notificações',
    'backend_free' => 'Apenas UI',
    'opened' => 'Aberta: :title',
    'unread_count' => '{1}:count notificação não lida|[2,*]:count notificações não lidas',
    'actions' => [
        'mark_all_read' => 'Marcar todas como lidas',
        'mark_read' => 'Marcar como lida',
        'view_all' => 'Ver todas as notificações',
        'delete_read' => 'Excluir lidas',
        'delete' => 'Excluir notificação',
        'close' => 'Fechar notificações',
    ],
    'filters' => [
        'label' => 'Filtros de notificações',
        'unread' => 'Não lidas',
        'all' => 'Todas',
        'read' => 'Lidas',
    ],
    'modal' => [
        'title' => 'Notificações',
        'description' => 'Notificações administrativas mockadas para o fluxo da demo.',
        'footer' => 'Conecte essas ações ao back-end do seu projeto.',
    ],
    'empty' => [
        'dropdown' => 'Nenhuma notificação nova.',
        'modal' => 'Nenhuma notificação para exibir.',
    ],
    'fallback' => [
        'title' => 'Notificação',
        'author' => 'Sistema',
        'label' => 'Notificação',
    ],
    'fake' => [
        'order' => [
            'title' => 'Pedido aprovado',
            'description' => 'O pedido mais recente foi aprovado e está pronto para separação.',
            'author' => 'Vendas',
            'label' => 'Pedido',
            'time' => '2 minutos atrás',
        ],
        'message' => [
            'title' => 'Nova mensagem',
            'description' => 'Um cliente enviou uma nova mensagem pelo formulário de contato.',
            'author' => 'Caixa de entrada',
            'label' => 'Mensagem',
            'time' => '18 minutos atrás',
        ],
        'comment' => [
            'title' => 'Comentário pendente',
            'description' => 'Um novo comentário está aguardando moderação no painel.',
            'author' => 'Blog',
            'label' => 'Comentário',
            'time' => '41 minutos atrás',
        ],
        'backup' => [
            'title' => 'Backup concluído',
            'description' => 'O backup agendado foi concluído com sucesso.',
            'author' => 'Sistema',
            'label' => 'Sistema',
            'time' => '2 horas atrás',
        ],
    ],
];
