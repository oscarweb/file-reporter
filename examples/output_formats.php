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
 * Output formats
 * @return object @example $app->setOutput(FileReporter::OUTPUT_OBJECT);
 * @return array  @example $app->setOutput(FileReporter::OUTPUT_ARRAY);
 * @return string @example $app->setOutput(FileReporter::OUTPUT_JSON);
 * @return string @example $app->setOutput(FileReporter::OUTPUT_SERIALIZE);
 */
$app->setOutput(FileReporter::OUTPUT_JSON);

/**
 * Return data
 * @return string - FileReporter::OUTPUT_JSON
 */
echo $app->getReport();