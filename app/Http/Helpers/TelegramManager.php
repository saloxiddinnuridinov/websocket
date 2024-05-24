<?php

namespace App\Http\Helpers;

class TelegramManager
{
    static public function sendTelegram($data)
    {
       //file_get_contents("https://api.telegram.org/bot" . env('TELEGRAM_TOKEN') . "/sendmessage?parse_mode=html&chat_id=-1001882802783&message_thread_id=" . $topicID . "&text=" . $data);
        // file_get_contents("https://api.telegram.org/bot" . env('TELEGRAM_TOKEN') . "/sendmessage?parse_mode=html&chat_id=950348637&text=" . $data);

        $text = json_encode($data, JSON_PRETTY_PRINT);
        file_get_contents("https://api.telegram.org/bot".env('TELEGRAM_BOT_TOKEN')."/sendmessage?&chat_id=".env('TELEGRAM_CHAT_ID')."&text=$text");

    }

}
