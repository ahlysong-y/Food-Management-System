<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// ១. បើកការបង្ហាញ Error ដើម្បីងាយស្រួលតាមដានលើ Vercel Logs
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ២. ទាញយក Composer Autoloader
require __DIR__ . '/../vendor/autoload.php';

// ៣. បង្កើតឯកសារ SQLite ក្នុង /tmp បើវាមិនទាន់មាន (សម្រាប់ Vercel)
if (!file_exists('/tmp/database.sqlite')) {
    touch('/tmp/database.sqlite');
}

// ៤. បង្កើត Folder សម្រាប់ទាញយក Cache, Sessions និង Views ទៅក្នុង /tmp
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

// ៥. បង្ខំឱ្យ Laravel ប្រើប្រាស់ផ្លូវ Storage ថ្មីនៅក្នុង /tmp ជំនួសផ្លូវចាស់
$_ENV['APP_STORAGE'] = $storagePath;
putenv('APP_STORAGE=' . $storagePath);

// ៦. កូដដោះស្រាយបញ្ហា Proxy & Protocol លើ Vercel មុនពេល Capture Request
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $_SERVER['HTTPS'] = 'on';
    $_SERVER['SERVER_PORT'] = 443;
}

// ៧. ចាប់យក Request Object
$request = Request::capture();

if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $request->server->set('HTTPS', 'on');
}

// ៨. ⚠️ ដោះស្រាយបញ្ហា [view] does not exist៖ ប្រើប្រាស់ផ្លូវទាញ Kernel ពេញលេញរបស់ Laravel 11
/** @var \Illuminate\Foundation\Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';

// កំណត់ផ្លូវ Storage ទៅកាន់ /tmp ផ្លូវការ
$app->useStoragePath($storagePath);

// បង្ខំឱ្យ Kernel ចាប់ផ្តើមចុះឈ្មោះ (Register/Boot) រាល់ Service Providers ទាំងអស់ (រាប់ទាំង view)
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

// ដំណើរការ Request តាមរយៈ Kernel ផ្លូវការ
$response = $kernel->handle($request);

// បញ្ជូនលទ្ធផលទៅកាន់ Browser
$response->send();

$kernel->terminate($request, $response);
