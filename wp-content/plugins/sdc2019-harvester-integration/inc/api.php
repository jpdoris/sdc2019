<?php

require_once __DIR__ . "/../vendor/autoload.php";

use App\HarvesterClient;

$harvester = new HarvesterClient();
$harvester->refreshCachefile();
$harvester->refreshPresentersCache();
