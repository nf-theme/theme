<?php

namespace NF\Exceptions;

use Exception;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Http\Response;
use NF\Foundation\Application;
use NF\View\Facades\View;
use Symfony\Component\Console\Application as ConsoleApplication;

class Handler
{
    /**
     * The container implementation.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [];

    /**
     * Create a new exception handler instance.
     *
     * @param  \NF\Facades\App  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $e
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $e)
    {
        if ($this->shouldntReport($e)) {
            return;
        }

        try {
            $logger = $this->app->make('log');
        } catch (Exception $ex) {
            throw $e; // throw the original exception
        }

        $logger->error($e);
    }

    /**
     * Determine if the exception should be reported.
     *
     * @param  \Exception  $e
     * @return bool
     */
    public function shouldReport(Exception $e)
    {
        return !$this->shouldntReport($e);
    }

    /**
     * Determine if the exception is in the "do not report" list.
     *
     * @param  \Exception  $e
     * @return bool
     */
    protected function shouldntReport(Exception $e)
    {
        $dontReport = array_merge($this->dontReport, [HttpResponseException::class]);

        foreach ($dontReport as $type) {
            if ($e instanceof $type) {
                return true;
            }
        }

        return false;
    }

    /**
     * Prepare exception for rendering.
     *
     * @param  \Exception  $e
     * @return \Exception
     */
    protected function prepareException(Exception $e)
    {
        return $e;
    }

    /**
     * Render an exception into a response.
     *
     * @param  \Exception  $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render(Exception $e)
    {
        $e = $this->prepareException($e);

        return $this->prepareResponse($e);
    }

    /**
     * Prepare response containing exception render.
     *
     * @param  \Exception $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function prepareResponse(Exception $e)
    {
        return $this->toWpResponse($e);
    }

    /**
     * Render Wordpress error page
     *
     * @param  \Exception  $e
     * @return mixed
     */
    protected function toWpResponse(Exception $e)
    {
        echo View::render('error', ['e' => $e, 'app_env' => defined('APP_ENV') ? APP_ENV : 'production']);
    }

    /**
     * Render an exception to the console.
     *
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @param  \Exception  $e
     * @return void
     */
    public function renderForConsole($output, Exception $e)
    {
        (new ConsoleApplication)->renderException($e, $output);
    }

}
