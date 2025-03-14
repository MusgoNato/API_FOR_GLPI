<?php

/**
 * Precisa estar habilitado o allow_url_fopen() no .ini
 */

namespace src\api;

use Dotenv\Dotenv;

require '../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(dirname(__DIR__, 1));
$dotenv->load();

// Inicia a sessao no glpi
$response = InitSessionController::getSessionToken($_ENV['GLPI_API_INIT_URL'], $_ENV['GLPI_APP_TOKEN'], $_ENV['GLPI_USER'], $_ENV['GLPI_PASS']);
$session_token = $response->session_token;

// Conecta ao bot no telegram (Executar apenas uma vez, depois comentar)
// $responseTelegram = InitConectionTelegramController::initWebhookTelegram();

// Recebe os dados enviados pelo Telegram
$raw = file_get_contents("php://input");

// No navegador não é tempo real, entao somente por logs consigo ver o que esta acontecendo nas requisições
$InputUserTelegramData = json_decode($raw);
if(!empty($InputUserTelegramData))
{
    if(isset($InputUserTelegramData->callback_query))
    {
        error_log("\n--------------- Atualização do bot (callbacks): \n" . json_encode($InputUserTelegramData->callback_query) . "\n-------------\n");
        BotMessagesController::sendMessage( null, $InputUserTelegramData->callback_query->data, $InputUserTelegramData->callback_query->message->chat->id);
    }
    error_log("\n--------------- Mensagem separada: \n" . json_encode($InputUserTelegramData) . "\n------------\n");
}

// Aqui retornaria menu
if(isset($InputUserTelegramData->message->text))
{
    BotMessagesController::sendMessage($InputUserTelegramData->message->text, null, $InputUserTelegramData->message->from->id);
}




// error_log(json_encode($InputUserTelegramData->message->from->id));


?>