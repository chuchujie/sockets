<?php
// Created by dealloc. All rights reserved.

/**
 * This file is loaded by PHPUnit to setup the Composer autoloader and do some magic to get those pesky tests running.
 */

require __DIR__ . '/../vendor/autoload.php';

use Carbon\Carbon;

date_default_timezone_set('UTC');
Carbon::setTestNow(Carbon::now());