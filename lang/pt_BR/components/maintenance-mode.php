<?php

return [
    'title' => 'Manutenção',
    'description' => 'Controle a disponibilidade pública do site.',
    'breadcrumbs' => [
        'settings' => 'Configurações',
    ],
    'status' => [
        'current' => 'Status atual',
        'down' => 'Em Manutenção',
        'up' => 'Site Online',
    ],
    'actions' => [
        'toggle' => 'Ativar/Desativar Manutenção',
        'enable' => 'Ativar',
        'disable' => 'Desativar',
        'cancel' => 'Cancelar',
        'enable_shortcut' => 'Ativar Modo de Manutenção',
        'disable_shortcut' => 'Desativar Modo de Manutenção',
    ],
    'message' => [
        'label' => 'Mensagem de Manutenção',
        'placeholder' => 'Nosso sistema atualmente se encontra em manutenção. Por favor, volte mais tarde.',
        'default' => 'Nosso sistema atualmente se encontra em manutenção. Por favor, volte mais tarde.',
    ],
    'header_shortcut' => [
        'label' => 'Atalho no Cabeçalho',
        'checkbox' => 'Exibir botão de ativar ou desativar manutenção',
        'description' => 'Permite adicionar um botão para ativar ou desativar rapidamente o modo de manutenção do site.',
    ],
    'online_alert' => [
        'label' => 'Alerta de Site Online',
        'checkbox' => 'Exibir alerta quando o modo de manutenção for desativado',
        'duration_prefix' => 'Exibir alerta por',
        'duration_suffix' => 'segundos',
        'description' => 'Use 0 para manter o alerta sempre visível.',
    ],
    'modal' => [
        'enable_title' => 'Ativar Modo de Manutenção',
        'enable_question' => 'Tem certeza que deseja ativar o Modo de Manutenção?',
        'enable_description' => 'Visitantes não poderão acessar o site durante esse período.',
        'disable_title' => 'Desativar Modo de Manutenção',
        'disable_question' => 'Tem certeza que deseja desativar o Modo de Manutenção?',
        'disable_description' => 'Seu site voltará a ficar disponível para visitantes.',
    ],
    'flash' => [
        'updated' => 'Configurações de manutenção atualizadas com sucesso.',
        'enabled' => 'Modo de manutenção ativado com sucesso.',
        'disabled' => 'Modo de manutenção desativado com sucesso.',
    ],
    'preview' => [
        'title' => 'Prévia pública',
        'kicker' => 'Rota pública de teste',
        'heading' => 'Site de Demonstração',
        'description' => 'Esta rota simula uma página pública protegida pelo middleware de manutenção.',
        'note' => 'Com o modo de manutenção ativo, visitantes anônimos recebem a página 503. Usuários autenticados continuam vendo esta página para validar ajustes.',
        'back' => 'Voltar ao painel',
    ],
];
