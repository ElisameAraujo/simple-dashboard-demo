<?php

return [
    'index' => [
        'title' => 'Helpers',
        'kicker' => 'Estrutura base',
        'heading' => 'Helpers',
        'description' => 'Classes utilitárias reutilizáveis que deixam comportamentos comuns do dashboard explícitos, fáceis de encontrar e simples de chamar em controllers, views, componentes Livewire e services.',
        'method_count' => '{1} :count método|[2,*] :count métodos',
    ],
    'actions' => [
        'back' => 'Voltar para helpers',
    ],
    'sections' => [
        'how_it_works' => [
            'title' => 'Como funciona',
            'description' => 'Finalidade e fluxo normal de uso deste helper.',
        ],
        'methods' => [
            'title' => 'Métodos disponíveis',
            'description' => 'API pública refletida da classe do helper registrada atualmente em config/helpers.php.',
        ],
        'example' => [
            'title' => 'Exemplo de uso',
            'description' => 'Uma chamada direta que pode ser reaproveitada no dashboard.',
        ],
        'output' => [
            'title' => 'Saída',
            'description' => 'Resultado esperado para o exemplo acima.',
        ],
    ],
    'methods' => [
        'name' => 'Método',
        'signature' => 'Assinatura',
        'return' => 'Retorno',
        'parameters' => 'Parâmetros',
        'no_parameters' => 'Este método não recebe parâmetros.',
        'example' => 'Exemplo',
        'fallback_summary' => 'Executa o método :method deste helper.',
        'fallback_parameter' => 'Valor usado pelo parâmetro :parameter.',
    ],
    'parameter_descriptions' => [
        'charactersToMask' => 'Quantidade de caracteres que serão substituídos por asteriscos.',
        'className' => 'Classe CSS aplicada ao bloco de código gerado.',
        'cols' => 'Distribuição das colunas no grid gerado.',
        'column' => 'Coluna do usuário autenticado que será consultada.',
        'count' => 'Quantidade usada para gerar itens ou calcular pluralização.',
        'currency' => 'Código da moeda usada na formatação.',
        'customName' => 'Nome opcional para o arquivo baixado.',
        'date' => 'Data de entrada que será formatada ou comparada.',
        'default' => 'Valor retornado quando a informação principal não existe.',
        'disk' => 'Disco configurado em config/filesystems.php.',
        'email' => 'Endereço de e-mail que será tratado.',
        'endDate' => 'Data final usada no cálculo de diferença.',
        'except' => 'Arquivo ou lista de arquivos que devem ser ignorados na importação.',
        'field' => 'Campo de validação que será analisado.',
        'file' => 'Arquivo ou caminho relativo dentro do disco.',
        'filename' => 'Nome do arquivo de rotas, sem a extensão .php.',
        'folders' => 'Pasta ou lista de pastas dentro de routes/.',
        'gender' => 'Gênero usado em ordinais em português. Use m para masculino ou f para feminino.',
        'height' => 'Altura usada no HTML gerado.',
        'id' => 'Coluna que representa o identificador do usuário.',
        'level' => 'Nível da heading HTML, como 1, 2 ou 3.',
        'limit' => 'Limite numérico aplicado ao método.',
        'locale' => 'Locale usado para formatar a saída.',
        'name' => 'Coluna ou nome usado para montar a saída.',
        'newFile' => 'Novo arquivo que substituirá o arquivo antigo.',
        'notificationId' => 'ID da notificação que será atualizada ou removida.',
        'number' => 'Número que será formatado.',
        'oldFile' => 'Caminho do arquivo antigo que será removido.',
        'path' => 'Caminho relativo da mídia dentro do disco.',
        'permission' => 'Permissão que será verificada no usuário autenticado.',
        'placeholder' => 'Asset de fallback usado quando a mídia não existe.',
        'position' => 'Posição da máscara no texto, como start, middle ou end.',
        'provider' => 'Provedor usado no embed de vídeo.',
        'role' => 'Role que será verificada no usuário autenticado.',
        'ruleName' => 'Nome da regra que será procurada, como max ou min.',
        'rulesSource' => 'Array de regras ou classe que expõe formRules().',
        'startDate' => 'Data inicial usada no cálculo de diferença.',
        'string' => 'Texto ou chave de pluralização.',
        'subfolder' => 'Subpasta dentro do namespace App\\Notifications.',
        'subfolders' => 'Subpasta ou lista de subpastas dentro do disco.',
        'text' => 'Texto que será limpo, contado, limitado ou transformado.',
        'type' => 'Nome da classe de notificação.',
        'value' => 'Valor numérico que será formatado.',
        'width' => 'Largura usada no HTML gerado.',
        'withRandomLinks' => 'Define se os parágrafos gerados terão links fictícios.',
    ],
    'helpers' => [
        'date-helper' => [
            'name' => 'DateHelper',
            'description' => 'Formata datas, intervalos relativos e rótulos de data para e-mail com saída localizada.',
            'works' => [
                'Use DateHelper quando datas precisam ser exibidas para pessoas, não apenas armazenadas ou comparadas.',
                'O helper resolve o locale solicitado, carrega as traduções de datas do projeto e aplica o timezone da aplicação antes de formatar.',
            ],
            'example' => [
                'usage' => [
                    "DateHelper::simpleDate('2026-05-19', 'pt-BR');",
                ],
                'output' => [
                    '19/05/2026',
                ],
            ],
        ],
        'disk-helper' => [
            'name' => 'DiskHelper',
            'description' => 'Salva, substitui, remove, localiza e calcula tamanho de arquivos nos discos configurados do Laravel.',
            'works' => [
                'Use DiskHelper quando uma funcionalidade recebe uploads e precisa persistir apenas o caminho relativo.',
                'O disco vem primeiro e as subpastas opcionais servem apenas para organizar caminhos dentro desse disco.',
            ],
            'example' => [
                'usage' => [
                    "\$path = DiskHelper::saveFile(\$photo, 'public', 'avatars');",
                ],
                'output' => [
                    'avatars/profile-20260521103000.jpg',
                ],
            ],
        ],
        'html-helper' => [
            'name' => 'HTMLHelper',
            'description' => 'Monta blocos HTML fictícios para demos, previews de editor e placeholders de conteúdo.',
            'works' => [
                'HTMLHelper começa com make() e encadeia métodos construtores como heading(), paragraphs(), listas, imagens e tabelas.',
                'Chame generate() no fim da cadeia para retornar a string HTML final.',
            ],
            'example' => [
                'usage' => [
                    'echo HTMLHelper::make()',
                    '    ->heading(2)',
                    '    ->paragraphs(1)',
                    '    ->generate();',
                ],
                'output' => [
                    '<h2>Título de Exemplo</h2><p>Parágrafo gerado...</p>',
                ],
            ],
        ],
        'media-helper' => [
            'name' => 'MediaHelper',
            'description' => 'Verifica existência de mídia e resolve informações de exibição, download, caminho e MIME.',
            'works' => [
                'Use MediaHelper quando um caminho de mídia armazenado precisa virar URL, resposta de download ou caminho legível de asset.',
                'Ele protege a interface retornando placeholders ou erros traduzidos quando a mídia solicitada não está disponível.',
            ],
            'example' => [
                'usage' => [
                    "MediaHelper::showMedia('avatars/user.jpg', 'public', 'img/placeholders/avatars/default-avatar.jpg');",
                ],
                'output' => [
                    '/storage/public/avatars/user.jpg',
                ],
            ],
        ],
        'notification-helper' => [
            'name' => 'NotificationHelper',
            'description' => 'Lê, conta, marca e remove notificações do usuário autenticado.',
            'works' => [
                'Use NotificationHelper em headers, menus e painéis que precisam do estado das notificações do usuário logado.',
                'Todo método de leitura retorna uma collection vazia ou zero quando não existe usuário autenticado.',
            ],
            'example' => [
                'usage' => [
                    'NotificationHelper::allUnreadNotificationsCount();',
                ],
                'output' => [
                    '3',
                ],
            ],
        ],
        'number-helper' => [
            'name' => 'NumberHelper',
            'description' => 'Formata números compactos, preços, moedas, áreas e ordinais por locale.',
            'works' => [
                'Use NumberHelper quando um número faz parte da interface e precisa de símbolos ou unidades específicas por idioma.',
                'O helper normaliza entradas de locale como pt-BR ou en_US antes de aplicar seus mapas de formatação.',
            ],
            'example' => [
                'usage' => [
                    "NumberHelper::priceFormat(1299.9, 'pt-BR');",
                ],
                'output' => [
                    'R$ 1.299,90',
                ],
            ],
        ],
        'route-helper' => [
            'name' => 'RouteHelper',
            'description' => 'Importa arquivos de rotas por pasta e expõe um pequeno inventário das rotas registradas.',
            'works' => [
                'Use RouteHelper para manter rotas separadas por área administrativa ou recurso sem repetir require manualmente.',
                'A própria demo usa esse helper para carregar as rotas de dashboard, helpers e perfil administrativo.',
            ],
            'example' => [
                'usage' => [
                    "RouteHelper::importRoutesFromFolder('admin', 'helpers');",
                ],
                'output' => [
                    'routes/demo/helpers/*.php carregados',
                ],
            ],
        ],
        'rule-helper' => [
            'name' => 'RuleHelper',
            'description' => 'Extrai valores de regras de validação do Laravel, como max:120 ou min:3.',
            'works' => [
                'Use RuleHelper quando o texto da interface precisa exibir o mesmo limite numérico já definido nas regras de validação.',
                'Ele aceita tanto um array de regras quanto uma classe que exponha formRules().',
            ],
            'example' => [
                'usage' => [
                    "\$rules = ['title' => 'required|string|max:120'];",
                    "RuleHelper::extractValue('title', 'max', \$rules);",
                ],
                'output' => [
                    '120',
                ],
            ],
        ],
        'text-helper' => [
            'name' => 'TextHelper',
            'description' => 'Normaliza, limita, conta, sanitiza, pluraliza e transforma textos.',
            'works' => [
                'Use TextHelper quando a limpeza de strings precisa ser consistente em formulários, comentários, imports e saída pública.',
                'Alguns métodos são sensíveis ao locale, então nomes e plurais podem seguir as regras do idioma ativo.',
            ],
            'example' => [
                'usage' => [
                    "TextHelper::normalizeNames('  maria   da silva  ', 'pt-BR');",
                ],
                'output' => [
                    'Maria da Silva',
                ],
            ],
        ],
        'user-helper' => [
            'name' => 'UserHelper',
            'description' => 'Fornece atalhos para dados do usuário autenticado, avatar, resumos, roles e permissões.',
            'works' => [
                'Use UserHelper em views e componentes administrativos que precisam de uma pequena parte do usuário autenticado sem repetir verificações de Auth.',
                'Os métodos de permissão dependem de spatie/laravel-permission e retornam padrões seguros quando não há usuário logado.',
            ],
            'example' => [
                'usage' => [
                    "UserHelper::maskEmail('john.doe@example.com', 3, 'middle');",
                ],
                'output' => [
                    'john***e@example.com',
                ],
            ],
        ],
    ],
];
