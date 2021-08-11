# Filter Reporter

It is a simple library to create a report of the existing files in an established directory.
It has some filters to identify for example: duplicate files or files with the same name.

#### Install vía [Composer](https://packagist.org/packages/oscarweb/file-reporter "Composer")

```sh
composer require oscarweb/file-reporter
```
#### &#8212; Basic use

```php
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
 * Return data
 * @return object - default
 */
$response = $app->getReport();

var_dump($response);
```
Output example [here](https://raw.githubusercontent.com/oscarweb/file-reporter/main/examples/cache/report.15ae3f61f9f41738356cee922fe2e655.json "Output Json")

Report JSON Structure: [report.md](https://github.com/oscarweb/file-reporter/tree/main/examples/report.md "Report JSON Structure")

------------
#### &#8212; Cache

To enable cache, with the previous example you must add the directory path where json files will be saved

```php
/**
 * Set Cache Directory
 * @param string
 */
$app->setCacheDir(__DIR__.DIRECTORY_SEPARATOR.'cache');

/**
 * Return data
 * @return object - default
 */
$response = $app->getReport();

```

See example [here](https://github.com/oscarweb/file-reporter/tree/main/examples/basic_cache.php "Basic Cache")

------------

#### &#8212; control.json

Every time you make a new report and have cache enabled, part of the information will be saved in a control file.
You can read the information with the following method.

```php
$app = new FileReporter();

/**
 * @return object - default
 */
$control = $app->getControl();
```
Output example [here](https://raw.githubusercontent.com/oscarweb/file-reporter/main/examples/cache/control.json "Output Json")

Control JSON Structure: [control.md](https://github.com/oscarweb/file-reporter/tree/main/examples/control.md "Report JSON Structure")

------------

#### &#8212; Recursive

You can create a custom recursive function.

```php
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
 * Adjust the output of the json file: JSON_PRETTY_PRINT
 */
$app->setJsonPrettyPrint();

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

```

See example [here](https://github.com/oscarweb/file-reporter/tree/main/examples/basic_recursive.php "Basic Recursive")

------------

#### &#8212; Filters

You can filter and create file searches based on cached data.
It can retrieve repeated files based on the `hash` value.

```php
$app = new FileReporter(__DIR__.DIRECTORY_SEPARATOR.'docs');

/**
 * If you set cache, it will filter on all reports.
 */
$app->setCacheDir(__DIR__.DIRECTORY_SEPARATOR.'cache');

/**
 * HASH Files - sha1
 * @return object - default
 */
$duplicate_files = $app->filterCache()->repeatsByHash();

```
See example [here](https://github.com/oscarweb/file-reporter/tree/main/examples/filter_search_with_cache.php "Repeats Files")

------------

You can see more examples [here](https://github.com/oscarweb/file-reporter/tree/main/examples "More Examples").