<?php

namespace NF\Models;

use NF\Database\DBManager;
use NF\Facades\App;

class Model extends \Illuminate\Database\Eloquent\Model
{
    public function __construct()
    {
        $manager = App::make(DBManager::class);
        $manager->bootEloquent();
    }
}
