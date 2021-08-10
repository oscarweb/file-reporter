<?php
/* enter correct path */
require __DIR__.'/../vendor/autoload.php';

use FileReporter\FileReporter;

$app = new FileReporter();

/**
 * Set Cache Directory
 * @param string
 */
$app->setCacheDir(__DIR__.DIRECTORY_SEPARATOR.'cache');

/**
 * @return bool
 */
var_dump($app->deleteCache());