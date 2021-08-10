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
 * HASH Files - sha1
 * @return object - default
 */
$duplicate_files = $app->filterReport()->repeatsByHash();

/**
 * NAMEs Files
 * @return object - default
 */
#$duplicate_names = $app->filterReport()->repeatsByName();

var_dump($duplicate_files);