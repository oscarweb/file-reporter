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
 * Your custom function
 */
function recursive($route, $app){
	$app->setDir($route);
	$result = $app->getReport();

	foreach($result->content as $item){
		if($item->is->dir){
			recursive($item->data->route, $app);
		}
	}
}

recursive($app->getDir(), $app);

exit(var_dump($app->getControl()));