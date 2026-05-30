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

// ដំណើរការទៅកាន់ទំព័រធម្មតា
require __DIR__ . '/../public/index.php';
