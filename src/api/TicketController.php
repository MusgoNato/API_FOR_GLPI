<?php

namespace src\api;

class TicketController
{   
    /**
     * Summary of post
     * Post para o ticket
     * @return void
     */
    public static function getAllTickets()
    {
        $url = $_ENV['GLPI_API_TICKET_URL'];
        $app_token = $_ENV['GLPI_APP_TOKEN'];

        $session_token = InitSessionController::getSessionToken();

        error_log("Retorno bruto de getSessiontoken : " . print_r($session_token, true));

        // tratamento caso seja um objeto
        $sessionToken = is_object($session_token) ? $session_token->session_token : $session_token;

        $context = stream_context_create(
[
            'http' => 
            [
                'method' => 'GET',
                'header' => "Content-Type: application/json\r\n" . 
                "App-Token: $app_token\r\n" . 
                "Session-Token: $sessionToken\r\n"
            ]
        ]);

        $response = file_get_contents($url, false, $context);

        // $tickets = json_decode($response, true);
        // error_log($tickets);
        if($response === false)
        {
            error_log("\n**********CONTEXT**********\n" . $context . "\n");
            error_log("\n**********CONTEXT**********\n" . $response . "\n");
        }
        else
        {
            error_log("\n**********CONTEXT**********\n" . $context . "\n");
            error_log("\n**********CONTEXT**********\n" . $response . "\n");
        }


    }
}