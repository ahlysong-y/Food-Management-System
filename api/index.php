<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// លាក់ Error កុំឱ្យលោតបង្ហាញពេលដាក់ Live (ដើម្បីសុវត្ថិភាព)
// បើចង់មើល Error ពេលវាគាំង អាចដោះសញ្ញា // ខាងក្រោមនេះវិញ
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

require __DIR__ . '/../vendor/autoload.php';

// បង្កើត Folder នៅក្នុង /tmp ព្រោះ Vercel អនុញ្ញាតឱ្យសរសេរឯកសារបានតែនៅទីនេះប៉ុណ្ណោះ
$storagePath = '/tmp/storage';
$directories = [
    $storagePath . '/app/public',
    $storagePath . '/framework/cache/data',
    $storagePath . '/framework/sessions',
    $storagePath . '/framework/testing',
    $storagePath . '/framework/views',
    $storagePath . '/logs',
];

foreach ($directories as $directory) {
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
    }
}

$_ENV['APP_STORAGE'] = $storagePath;
putenv('APP_STORAGE=' . $storagePath);

// កំណត់ HTTPS អោយដើរស្រួលលើ Vercel
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $_SERVER['HTTPS'] = 'on';
    $_SERVER['SERVER_PORT'] = 443;
}

/** @var \Illuminate\Foundation\Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->useStoragePath($storagePath);

$request = Request::capture();

if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $request->server->set('HTTPS', 'on');
}

$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
