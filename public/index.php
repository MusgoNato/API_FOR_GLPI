<?php

/**
 * Habilitar o allow_url_fopen() no .ini
 * Descomentar a linha 22, apos executar, comentar novamente
 * Rodar o servidor localmente com o comando: php -S localhost:8000 -t public
 * Deixar o localhost exposto remotamente com o uso do ngrok: ngrok http 8000
 * 
 * 
 * A cada inicialização deve ser configurada uma nova porta gerada pelo ngrok no arquivo .env:
 * URL_APP_WEBHOOK=sua_porta_ngrok
 * 
 * O programa não funcionará sem a integração com o GLPI, para isso execute o glpi localmente e pegue o TOKEN de acesso a API, o seu usuario e senha, apos isso insira no arquivo .env em:
 * GLPI_APP_TOKEN=seu_token_glpi
 * GLPI_USER=usuario_glpi
 * GLPI_PASS=senha_glpi
 * 
 * |----------------------------------------------|
 *  Ainda em desenvolvimento a integração com GLPI
 * |----------------------------------------------|
 */

namespace src\api;

use Dotenv\Dotenv;

require '../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(dirname(__DIR__, 1));
$dotenv->load();

// Inicia a sessao no glpi
$response = InitSessionController::getSessionToken();
$session_token = $response->session_token;

// Conecta a webhook (Executar apenas uma vez, depois comentar)
// $responseTelegram = InitConectionTelegramController::initWebhookTelegram();

// Recebe os dados enviados pelo Telegram
$raw = file_get_contents("php://input");    

// No navegador não é tempo real, entao somente por logs consigo ver o que esta acontecendo nas requisições
$InputUserTelegramData = json_decode($raw);
if(!empty($InputUserTelegramData))
{
    if(isset($InputUserTelegramData->callback_query))
    {
        // error_log("\n--------------- Atualização do bot (callbacks): \n" . json_encode($InputUserTelegramData->callback_query) . "\n-------------\n");
        BotMessagesController::sendMessage( null, $InputUserTelegramData->callback_query->data, $InputUserTelegramData->callback_query->message->chat->id);
    }
    // error_log("\n--------------- Mensagem separada: \n" . json_encode($InputUserTelegramData) . "\n------------\n");
}

// Aqui retorna o menu
if(isset($InputUserTelegramData->message->text))
{
    BotMessagesController::sendMessage($InputUserTelegramData->message->text, null, $InputUserTelegramData->message->from->id);
}

// error_log(json_encode($InputUserTelegramData->message->from->id));


?>