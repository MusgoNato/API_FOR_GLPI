<?php

namespace src\api;

use Exception;

define("TOKEN_BOT_TELEGRAM", "7929845389:AAHJLJ5O0fzzTupg1cikJAkSPY4PNl3xrgY");
define("URL_BOT_TELEGRAM", "https://api.telegram.org/bot" . TOKEN_BOT_TELEGRAM . "/");

class InitConectionTelegramController
{
    public static function initWebhookTelegram()
    {
        // Url completa com o token embutido
        $URL_BOT_TELEGRAM = $_ENV['TELEGRAM_API_URL'] . $_ENV['TELEGRAM_BOT_TOKEN'] . "/";

        // Caminho para webhook do telegram
        $url = $URL_BOT_TELEGRAM . "setWebhook?url=" . urlencode($_ENV['URL_APP_WEBHOOK']);
        $response = file_get_contents($url);
        $data = json_decode($response, true);

        if($data['ok'])
        {
            return $data;
        }
        else
        {
            throw new Exception
            (
                "Houve algum problema na configuração do Webhook Talvez: HTTP"
            );
        }

    }
}