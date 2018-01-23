Spyserp API Client
------------

This client assists in making calls to spyserp.com API.

Installation
------------

The preferred way to install this extension is through composer.

Either run

```
php composer.phar require --dev --prefer-dist spyserp/phpclient
```

or add

```
"spyserp/phpclient": "~1.0.0"
```

to the require-dev section of your composer.json file.

Usage
-----

```php
<?php
require_once 'SpySerp.php';

$apiCode = 'your-api-code';
$aparser = new SpySerp($apiCode);


// Return array of all used search engines from SpySerp.com
$result = $aparser->searchEngines();

$projectId = 12345;
// Return data categories of project with id = 12345 if you can view this project
$result = $aparser->projectCategories($projectId);

```
