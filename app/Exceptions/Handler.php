<?php

namespace App\Exceptions;

use Exception;

class Handler extends \NF\Exceptions\Handler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [

    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render(Exception $exception)
    {
        return parent::render($exception);
    }
}
