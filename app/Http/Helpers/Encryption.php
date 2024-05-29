<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Crypt;

class Encryption
{
    static public function encryptMessage($message): string
    {
        return Crypt::encrypt($message);
    }

    static public function decryptMessage($encryptedMessage) {
       return Crypt::decrypt($encryptedMessage);
    }

}
