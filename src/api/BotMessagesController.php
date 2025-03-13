<?php

namespace src\api;

class BotMessagesController
{
    public static function sendMessage($UserMessage)
    {
        if($UserMessage !== 'Menu')
        {
            var_dump("Peguei o valor : ", $UserMessage);
        }
    }
}