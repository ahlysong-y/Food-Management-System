<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// ១. បិទការបង្ហាញសេចក្តីព្រមាន និង Error ទាំងអស់លើអេក្រង់សម្រាប់ Production
error_reporting(0);
ini_set('display_errors', 0);

// ២. ទាញយក Composer Autoloader
require __DIR__ . '/../vendor/autoload.php';

// ៣. បង្កើតឯកសារ SQLite ក្នុង /tmp បើវាមិនទាន់មាន (ដោះស្រាយបញ្ហា Read-only លើ Vercel)
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

// ៦. ទាញយក App Instance និងកំណត់ផ្លូវ Storage ទៅកាន់ /tmp ផ្លូវការ
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->useStoragePath($storagePath);

$app->handleRequest(Request::capture());
