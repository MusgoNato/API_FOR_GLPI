<?php

namespace src\api;

class BotMessagesController
{
    public static function sendMessage($InputUserMessage, $chatId)
    {
        // Url da api telegram envio de chat
        error_log("Mensagem de usuario: " . $InputUserMessage . " Id do chat: " . $chatId);


        if($InputUserMessage === "fazer_pedido")
        {
            $responseBot = "Ótimo! Vamos fazer seu pedido.\nEscolha um sabor:";

            $keyboard = 
            [
                'inline_keyboard' =>
                [
                    [
                        ['text' => 'Calabresa', 'callback_data' => 'sabor_calabresa']
                    ]
                ] 
            ];
            error_log("Opa deu certo aqui, [1]");
        }
        else
        {
            $responseBot = "Bem vindo ao atendimento do BOT da pizza🍕🤖\nInsira uma das opções abaixo\n[1] - Fazer Pedido\n[2] - Acompanhar pedido\n";

            // Botoes
            $keyboard = 
            [
                'inline_keyboard' =>
                [ 
                    [
                        ['text' => '1 - Fazer pedido', 'callback_data' => 'fazer_pedido'],
                        ['text' => '2 - Acompanhar pedido', 'callback_data' => 'acompanhar_pedido']
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