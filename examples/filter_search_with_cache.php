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
 * You can create searches
 * @param string $name_key
 * @param string $operator|$value
 * @param string|int $value
 * @example $app->filterReport()->seach('ext', '=', 'jpg'); or 
 * @example $app->filterReport()->seach('ext', 'jpg');
 * @return object - default
 * -
 * Operators: '=', 'like', '<', '>'
 */

$search = $app->filterCache()->search('name', 'like', 'lorem');

var_dump($search);
