<?php

namespace src\api;

class BotMessagesController
{   
    /**
     * Summary of loadState
     * Carrega o pedido do usuario
     * @param mixed $chatId
     */
    private static function loadState($chatId)
    {
        $file = 'user_state.json';
        if (file_exists($file)) {
            $data = json_decode(file_get_contents($file), true);
            return $data[$chatId] ?? ['state' => 'inicio', 'order' => []];
        }
        return ['state' => 'inicio', 'order' => []];
    }

    /**
     * Summary of saveState
     * Salva o estado atual do pedido do usuario
     * @param mixed $chatId
     * @param mixed $state
     * @param mixed $order
     * @return void
     */
    private static function saveState($chatId, $state, $order)
    {
        $file = 'user_state.json';
        $data = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
        $data[$chatId] = ['state' => $state, 'order' => $order];
        file_put_contents($file, json_encode($data));
    }

    /**
     * Summary of sendMessage
     * Envia o fluxo a cada requisição para o usuario
     * @param mixed $InputUserMessage
     * @param mixed $callback
     * @param mixed $chatId
     * @return void
     */
    public static function sendMessage($InputUserMessage, $callback, $chatId)
    {
        // Carregando o arquivo
        $userData = self::loadState($chatId);
        $state = $userData['state'];
        $order = $userData['order'];

        // Para debug
        error_log("Início | ChatId: $chatId | Estado: $state | Callback: " . ($callback ?? 'null') . " | Input: " . ($InputUserMessage ?? 'null'));

        $responseBot = "";
        $keyboard = null;

        // Casos para os estados do usuario
        switch($state)
        {
            case 'inicio':
                // Para debug
                error_log("Case inicio | Callback: " . ($callback ?? 'null'));
                
                if($callback === 'fazer_pedido') 
                {
                    $responseBot = "Ótimo! Vamos fazer seu pedido.\nEscolha um sabor: 🤤";
                    $keyboard = 
                    [
                        'inline_keyboard' => 
                        [
                            [
                                ['text' => 'Calabresa', 'callback_data' => 'sabor_calabresa'],
                                ['text' => 'Bacon', 'callback_data' => 'sabor_bacon'],
                                ['text' => 'Queijo', 'callback_data' => 'sabor_queijo'],
                                ['text' => 'Mussarela', 'callback_data' => 'sabor_mussarela'],
                            ]
                        ]
                    ];
                    
                    $state = 'escolhendo_sabor';

                    // Para debug
                    error_log("Transição para escolhendo_sabor | Novo estado: $state");
                }else 
                {
                    $responseBot = "Bem Vindo ao atendimento BOT de Pizza🍕🤖\nInsira uma das opções abaixo para iniciarmos o seu atendimento!";
                    $keyboard = 
                    [
                        'inline_keyboard' => 
                        [
                            [
                                ['text' => 'Fazer Pedido', 'callback_data' => 'fazer_pedido'],
                            ]
                        ]
                    ];
                }
                break;

            case 'escolhendo_sabor':
                // Para debug
                error_log("Case escolhendo_sabor | Callback: " . ($callback ?? 'null'));
                
                if(strpos($callback, 'sabor_') === 0) 
                {
                    $sabor = str_replace('sabor_', '', $callback);
                    $order['sabor'] = ucfirst($sabor);
                    $responseBot = "Você escolheu " . $order['sabor'] . ".\nQual o tamanho da pizza? 📏";
                    $keyboard = 
                    [
                        'inline_keyboard' => 
                        [
                            [
                                ['text' => 'P (4 Fatias)', 'callback_data' => 'tamanho_p'],
                                ['text' => 'M (8 Fatias)', 'callback_data' => 'tamanho_m'],
                                ['text' => 'G (12 Fatias)', 'callback_data' => 'tamanho_g'],
                                ['text' => 'GG (24 Fatias)', 'callback_data' => 'tamanho_gg'],
                            ]
                        ]
                    ];
                    
                    $state = 'escolhendo_tamanho';

                    // Para debug
                    error_log("Sabor escolhido: $sabor | Novo estado: $state");
                }else 
                {
                    $responseBot = "Por favor, escolha um sabor!";
                    $keyboard = 
                    [
                        'inline_keyboard' => 
                        [
                            [
                                ['text' => 'Calabresa', 'callback_data' => 'sabor_calabresa'],
                                ['text' => 'Bacon', 'callback_data' => 'sabor_bacon'],
                                ['text' => 'Queijo', 'callback_data' => 'sabor_queijo'],
                                ['text' => 'Mussarela', 'callback_data' => 'sabor_mussarela'],
                            ]
                        ]
                    ];
                }
                break;

            case 'escolhendo_tamanho':
                // Para debug
                error_log("Case escolhendo_tamanho | Callback: " . ($callback ?? 'null'));
                
                if(strpos($callback, 'tamanho_') === 0) 
                {
                    $tamanho = strtoupper(str_replace('tamanho_', '', $callback));
                    $order['tamanho'] = $tamanho;
                    $responseBot = "Você escolheu tamanho " . $order['tamanho'] . ".\nInsira a forma de pagamento 💰";
                    $keyboard = 
                    [
                        'inline_keyboard' => 
                        [
                            [
                                ['text' => 'PIX', 'callback_data' => 'pagamento_pix'],
                                ['text' => 'DÉBITO', 'callback_data' => 'pagamento_debito'],
                                ['text' => 'CRÉDITO', 'callback_data' => 'pagamento_credito'],
                                ['text' => 'DINHEIRO', 'callback_data' => 'pagamento_dinheiro'],
                            ]
                        ]
                    ];
                    
                    $state = 'escolhendo_pagamento';

                    // Para debug
                    error_log("Tamanho escolhido: $tamanho | Novo estado: $state");
                }
                break;

            case 'escolhendo_pagamento':
                // Para debug
                error_log("Case escolhendo_pagamento | Callback: " . ($callback ?? 'null'));
                
                if(strpos($callback, 'pagamento_') === 0) 
                {
                    $pagamento = str_replace('pagamento_', '', $callback);
                    $order['pagamento'] = ucfirst($pagamento);
                    $responseBot = "**********Nota do Pedido**********\n" . "SABOR: " . $order['sabor'] . "\n" . "TAMANHO: " . $order['tamanho'] . "\n" . "PAGAMENTO: " . $order['pagamento'] . "\n\n" . "Seu pedido chegará ao estabelecimento, pedimos atenciosamente que aguarde enquanto o preparamos! 😊";
                    $state = 'finalizado';
                    
                    // Para debug
                    error_log("Pagamento escolhido: $pagamento | Novo estado: $state");
                }
                break;

            case 'finalizado':
                // Para debug
                error_log("Case finalizado | Callback: " . ($callback ?? 'null'));
                
                $responseBot = "Seu pedido já foi finalizado! Deseja fazer outro?";
                $keyboard = 
                [
                    'inline_keyboard' => 
                    [
                        [
                            ['text' => 'Fazer Novo Pedido', 'callback_data' => 'fazer_pedido'],
                        ]
                    ]
                ];
                if($callback === 'fazer_pedido') 
                {
                    $state = 'inicio';
                    $order = [];

                    // Para debug
                    error_log("Reiniciando pedido | Novo estado: $state");
                }
                break;

            default:
                // Para debug    
                error_log("Case default | Estado atual: $state | Callback: " . ($callback ?? 'null'));
                
                $responseBot = "Algo deu errado. Vamos começar de novo!";
                $state = 'inicio';
                $order = [];
                $keyboard = 
                [
                    'inline_keyboard' => 
                    [
                        [
                            ['text' => 'Fazer Pedido', 'callback_data' => 'fazer_pedido'],
                        ]
                    ]
                ];
                break;
        }

        // Apos todo o fluxo, salvo o estado do pedido
        self::saveState($chatId, $state, $order);

        $data = 
        [
            'chat_id' => $chatId,
            'text' => $responseBot,
        ];

        if($keyboard) 
        {
            $data['reply_markup'] = json_encode($keyboard, JSON_UNESCAPED_UNICODE);
        }

        // Coteudo do json a ser enviado
        $payload = json_encode($data, JSON_UNESCAPED_UNICODE);
        $context = stream_context_create(
[
            'http' => 
            [
                'method' => 'POST',
                'header' => "Content-Type: application/json\r\n" . "Content-Length: " . strlen($payload) . "\r\n",
                'content' => $payload
            ]
        ]);

        // URL de envio
        $url = $_ENV['TELEGRAM_API_URL'] . $_ENV['TELEGRAM_BOT_TOKEN'] . "/sendMessage";
        $response = file_get_contents($url, false, $context);
        
        // Para debug em resposta da requisição
        if($response === false) 
        {
            error_log("Erro ao enviar mensagem para o Telegram: " . $url);
        }else 
        {
            error_log("Resposta enviada com sucesso | Estado atual: $state");
        }
    }
}