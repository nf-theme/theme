<?php

namespace NF\Database;

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;

class DBManager
{
    /**
     * "Capsule" manager instance. Capsule aims to make configuring
     *
     * @var Illuminate\Database\Capsule\Manager
     */
    private $capsule;

    public function __construct()
    {
        $this->capsule = new Capsule;

        $this->capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => DB_HOST,
            'database'  => DB_NAME,
            'username'  => DB_USER,
            'password'  => DB_PASSWORD,
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]);
        $this->capsule->setEventDispatcher(new Dispatcher(new Container));
        $this->capsule->setAsGlobal();
    }

    public function bootEloquent()
    {
        $this->capsule->bootEloquent();
    }
}
