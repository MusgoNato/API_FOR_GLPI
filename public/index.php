<?php

namespace src\api;

use Dotenv\Dotenv;

require '../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(dirname(__DIR__, 1));
$dotenv->load();

// Inicia a sessao no glpi
$response = InitSessionController::getSessionToken($_ENV['GLPI_API_INIT_URL'], $_ENV['GLPI_APP_TOKEN'], $_ENV['GLPI_USER'], $_ENV['GLPI_PASS']);
$session_token = $response->session_token;

// Conecta ao bot no telegram (Executar apenas uma vez, depois comentar)
$responseTelegram = InitConectionTelegramController::initWebhookTelegram();

// Recebe os dados enviados pelo Telegram
$raw = file_get_contents("php://input");

// No navegador não é tempo real, entao somente por logs consigo ver o que esta acontecendo nas requisições
$update = json_decode($raw);
if(!empty($update))
{
    error_log("Mensagem separada: " . json_encode($update->message->text));
}

// Aqui retornaria menu
if(isset($update->message->text))
{
    BotMessagesController::sendMessage($update->message->text);
}
else
{
    die("erorrrr");
}

 

?>