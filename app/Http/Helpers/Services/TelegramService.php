<?php

namespace App\Http\Helpers\Services;

use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;

class TelegramService
{
    protected $telegram;

    /**
     * @throws TelegramSDKException
     */
    public function __construct()
    {
        $this->telegram = new Api(config('services.telegram.bot_token'));
    }

    public function sendMessage($message)
    {
        $this->telegram->sendMessage([
            'chat_id' => config('services.telegram.chat_id'),
            'text' => $message,
        ]);
    }

    public function sendDocument($filePath)
    {
        $this->telegram->sendDocument([
            'chat_id' => config('services.telegram.chat_id'),
            'document' => fopen($filePath, 'r')
        ]);
    }
}
