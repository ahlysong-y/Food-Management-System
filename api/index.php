<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));
$request = Request::capture();

if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $request->server->set('HTTPS', 'on');
}

$app->handleRequest($request);
