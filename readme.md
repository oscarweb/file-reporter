# Filter Reporter

It is a simple library to create a report of the existing files in an established directory.
It has some filters to identify for example: duplicate files or files with the same name.

#### Install vía [Composer](https://packagist.org/packages/oscarweb/file-reporter "Composer")

```sh
composer require oscarweb/file-reporter
```
#### Basic use

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

#### Report JSON Structure

| Attribute | Type | Description |
|:-------|:------|:-----|
| `route` | `string` | Directory path |
| `name` | `string` | Directory name |
| `created` | `integer` | `strtotime('now')` |
| `updated` | `integer` | `strtotime('now')` |
| `count` | `array`   | Number of files and folders: |
| `content` | `array` | |



You can see more examples [here](https://github.com/oscarweb/microcli/tree/main/examples "More Examples").