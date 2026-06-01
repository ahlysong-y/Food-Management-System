<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
echo $app->getCachedServicesPath();
