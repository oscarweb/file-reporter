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
 * You can create searches
 * @param string $name_key
 * @param string $operator|$value
 * @param string|int $value
 * @example $app->filterReport()->search('ext', '=', 'jpg'); or 
 * @example $app->filterReport()->search('ext', 'jpg');
 * @return object - default
 * -
 * Operators: '=', 'like', '<', '>'
 */

$search = $app->filterReport()->search('name', 'like', 'lorem');

var_dump($search);