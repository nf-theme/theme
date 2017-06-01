<?php

use App\Repositories\TestRepository;
use App\Repositories\TestRepositoryEloquent;
use Illuminate\Support\Facades\Facade;
use NF\Facades\App;

$app = require_once __DIR__ . '/bootstrap/app.php';

App::bind(TestRepository::class, TestRepositoryEloquent::class);
var_dump(App::make(TestRepository::class)->test());die();
