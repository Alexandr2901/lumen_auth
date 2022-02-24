<?php
/** @var \Laravel\Lumen\Application $app */

$app->configure('tinker');
$app->register(\Laravel\Tinker\TinkerServiceProvider::class);
