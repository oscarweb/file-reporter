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
 * If you set cache, it will filter on all reports.
 */
$app->setCacheDir(__DIR__.DIRECTORY_SEPARATOR.'cache');

/**
 * HASH Files - sha1
 * @return object - default
 */
$cache_duplicate_files = $app->filterCache()->repeatsByHash();

/**
 * NAMEs Files
 * @return object - default
 */
//$cache_duplicate_names = $app->filterCache()->repeatsByName();

var_dump($cache_duplicate_files);
