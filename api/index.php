<?php

// បិទការបង្ហាញសេចក្តីព្រមាន និង Error ទាំងអស់លើអេក្រង់សម្រាប់ Production
error_reporting(0);
ini_set('display_errors', 0);

// ទាញយក Composer Autoloader
require __DIR__ . '/../vendor/autoload.php';

// បង្កើតឯកសារ sqlite ក្នុង /tmp បើវាមិនទាន់មាន
if (!file_exists('/tmp/database.sqlite')) {
    touch('/tmp/database.sqlite');
}

// Redirect storage paths to /tmp since Vercel is read-only
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

// Ensure Laravel uses the custom storage path in Vercel
$_ENV['APP_STORAGE'] = $storagePath;
putenv('APP_STORAGE=' . $storagePath);

// Create a custom app instance overriding the storage path
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->useStoragePath($storagePath);

// Handle the request
$request = Illuminate\Http\Request::capture();
$response = $app->handleRequest($request);
$response->send();

