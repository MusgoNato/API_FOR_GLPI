<?php

namespace src\api;

class InitSessionController
{

    public static function getSessionToken()
    {
        $USER = $_ENV['GLPI_USER'];
        $PASS = $_ENV['GLPI_PASS'];
        $URL_API = $_ENV['GLPI_API_INIT_URL'];
        $APP_TOKEN = $_ENV['GLPI_APP_TOKEN'];

        // Codifica login e senha em Base64
        $auth = base64_encode("$USER:$PASS");
        
        $ch = curl_init($URL_API);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "App-Token: $APP_TOKEN",
            "Authorization: Basic $auth"
        ]);

        curl_setopt($ch, CURLOPT_VERBOSE, true);

        $response = curl_exec($ch);

        if($response === false)
        {
            var_dump(curl_error($ch));
        }
        curl_close($ch);

        return json_decode($response);
    }
}