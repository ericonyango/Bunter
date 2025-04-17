<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class RedirectException extends Exception
{


    public function __construct(private string $route,string $message,int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message,$code,$previous);
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function flashError(): void
    {
        session() -> flash('errormessage',$this ->message);
    }
}
