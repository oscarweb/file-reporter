<?php
/* enter correct path */
require __DIR__.'/../vendor/autoload.php';

use FileReporter\FileReporter;

/**
 * Set Directory
 * @param string
 */
$app = new FileReporter(__DIR__.DIRECTORY_SEPARATOR.'docs');

/**
 * Set Cache Directory
 * @param string
 */
$app->setCacheDir(__DIR__.DIRECTORY_SEPARATOR.'cache');

/**
 * @return bool
 */
var_dump($app->deleteReport());