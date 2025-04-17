<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class Handler extends Exception
{
    protected array $dontReport = [];

    protected array $dontFlash = [
        'password',
        'password_confirmation',
    ];

    public function report(Exception $exception): void
    {
        parent::getTrace($exception);
    }

    public function render($request, Exception $exception)
    {
        if ($exception instanceof  AccessDeniedHttpException){
            return abort(404);
        }

        if ($exception instanceof  RequestException){
            $exception -> flashError();
            return  redirect() -> back();
        }

        if ($exception instanceof  TokenMismatchException){
            session() -> flash('errormessage','Token mismatch');
            return  redirect() -> back();
        }

        return  parent::getPrevious($request,$exception);
    }
}
