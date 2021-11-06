<?php

namespace App\Exceptions;

use Exception;

class MainException extends Exception
{
    private $errorDescription;
    public function __construct($message = "", $code = 0, $errorDescription)
    {
        parent::__construct($message, $code);
        $this->errorDescription = $errorDescription;
    }

    public function getErrorDescription()
    {
        return $this->errorDescription;
    }

    public function errorResponse()
    {
        return response()->json([
            "error" => $this->message,
            "description" => $this->errorDescription
        ], $this->code);
    }
}
