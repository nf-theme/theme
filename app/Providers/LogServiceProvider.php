<?php

namespace App\Providers;

use Illuminate\Log\Writer;
use Monolog\Handler\ElasticSearchHandler;
use Monolog\Logger as Monolog;
use NF\Logs\Facades\VCLog;
use NF\Logs\VicodersLog;

class LogServiceProvider extends \Illuminate\Log\LogServiceProvider
{
    /**
     * Create the logger.
     *
     * @return \Illuminate\Log\Writer
     */
    public function createLogger()
    {
        $log = new Writer(
            new Monolog($this->channel())
        );
        $this->configureHandler($log);
        return $log;
    }

    /**
     * Get the name of the log "channel".
     *
     * @return string
     */
    protected function channel()
    {
        return defined('APP_ENV') ? APP_ENV : 'production';
    }

    /**
     * Configure the Monolog handlers for the application.
     *
     * @param  \Illuminate\Log\Writer  $log
     * @return void
     */
    protected function configureHandler(Writer $log)
    {
        $this->{'configure' . ucfirst($this->handler()) . 'Handler'}($log);
    }

    /**
     * Get the default log handler.
     *
     * @return string
     */
    protected function handler()
    {
        if (defined('USE_VCLOGGER') && USE_VCLOGGER === true) {
            return 'vclogger';
        } else {
            return 'single';
        }
    }

    /**
     * Configure the Monolog handlers for the application.
     *
     * @param  \Illuminate\Log\Writer  $log
     * @return void
     */
    protected function configureSingleHandler(Writer $log)
    {
        $log->useFiles(
            $this->app->storagePath() . '/logs/nf-theme.log',
            $this->logLevel()
        );
    }

    /**
     * Configure the Monolog to use vicoders handler.
     *
     * @param  \Illuminate\Log\Writer  $log
     * @return void
     */
    protected function configureVcloggerHandler(Writer $log)
    {

        $log->getMonolog()->pushHandler(new ElasticSearchHandler(VicodersLog::getInstance()->getClient(), VicodersLog::getInstance()->getOption(), $this->logLevel()));
    }

    /**
     * Get the log level for the application.
     *
     * @return string
     */
    protected function logLevel()
    {
        return defined('APP_LOG_LEVEL') ? APP_LOG_LEVEL : 'error';
    }
}
