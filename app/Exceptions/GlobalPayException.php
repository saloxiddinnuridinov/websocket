<?php

namespace App\Exceptions;

use Exception;

class GlobalPayException extends Exception
{

    public function getError(){
        return [
            "message" => $this->getMessage(),
            "error" => true,
        ];
    }
}
