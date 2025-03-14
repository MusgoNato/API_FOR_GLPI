<?php

namespace src\api;

class BotMessagesController
{
    public static function sendMessage($InputUserMessage, $callback, $chatId)
    {
        // Url da api telegram envio de chat
        error_log("Mensagem de usuario: " . $InputUserMessage . " Id do chat: " . $chatId);

        // Caso para escolhas do botão
        if($callback !== null)
        {
            $responseBot = "Ótimo! Vamos fazer seu pedido.\nEscolha um sabor:";

            switch($callback)
            {
                case 'fazer_pedido':
                    $keyboard = 
                    [
                        'inline_keyboard' =>
                        [
                            [
                                ['text' => 'Calabresa', 'callback_data' => 'sabor_calabresa'],
                                ['text' => 'Bacon', 'callback_data' => 'sabor_bacon'],
                                ['text' => 'Queijo', 'callback_data' => 'sabor_queijo'],
                                ['text' => 'Mussarela', 'callback_data' => 'sabor_mussarela']
                            ]
                        ] 
                    ];
                    break;
                
                case 'sabor_calabresa':
                case 'sabor_bacon':
                case 'sabor_queijo':
                case 'sabor_mussarela':

                    $responseBot = "Qual o tamanho da Pizza?";
                    $keyboard = 
                    [
                        'inline_keyboard' =>
                        [
                            [
                                ['text' => '4 Fatias (P)', 'callback_data' => 'tam_P'],
                                ['text' => '8 Fatias (M)', 'callback_data' => 'tam_M'],
                                ['text' => '12 Fatias (G)', 'callback_data' => 'tam_G'],
                                ['text' => '24 Fatias (GG)', 'callback_data' => 'tam_GG'],
                            ]
                        ] 
                    ];
                    break;

                case 'tam_P':
                case 'tam_M':
                case 'tam_G':
                case 'tam_GG':
                   
                    $responseBot = "Seu pedido foi entregue e deve chegar em breve 🏍️. Muito obrigado 🫂";
                    $keyboard = null;
                    break;
                default:
                    error_log("This is default message (Caabreasa)" . $callback);
                    break;
            }
        }
            
        // Caso para inputs do usuario
        if($InputUserMessage !== null)
        {
            $responseBot = "Bem vindo ao atendimento do BOT da pizza🍕🤖\nInsira uma das opções abaixo\n";

            // Botoes
            $keyboard = 
            [
                'inline_keyboard' =>
                [ 
                    [
                        ['text' => 'Fazer pedido', 'callback_data' => 'fazer_pedido'],
                    ]
                ]
            ];
        }

        
        // Prepara dados para requisicao 
        $data = [
            'chat_id' => $chatId,
            'text' => $responseBot,
        ];

        if($keyboard)
        {
            $data['reply_markup'] = json_encode($keyboard, JSON_UNESCAPED_UNICODE);
        }

        $payload = json_encode($data, JSON_UNESCAPED_UNICODE);
        
        // Contexto para o POST
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/json\r\n" .
                            "Content-Length: " . strlen($payload) . "\r\n",
                'content' => $payload
            ]
        ]);

        // URL da API para enviar mensagem
        $url = $_ENV['TELEGRAM_API_URL'] . $_ENV['TELEGRAM_BOT_TOKEN'] . "/sendMessage";
        $response = file_get_contents($url, false, $context);
        error_log("Valor response POST : " . $response);

        // logs
        if($response !== false)
        {
            error_log("Resposta do Telegram : " . $response);
        }

    }
}