<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Client\Response;

class PetstoreException extends Exception
{
    public ?Response $response;

    public function __construct(string $message, ?Response $response = null)
    {
        parent::__construct($message);
        $this->response = $response;
    }
}
