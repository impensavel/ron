# Burgundy
The `Burgundy` class is responsible for reading News Stories (feeds).

## Usage
This document contains usage examples, along with a description of configuration options.

## Reading News Stories
The `read()` method accepts two arguments.

The first being the input, which can be the URL of a news feed, or the feed data itself in the form of a `string`, a `resource` or an `SplFileInfo` object.

The (**optional**) second one must be an `array`, used to pass custom configurations to the XML parser.

Know more about the `XML` Essence configurations in the [documentation](https://github.com/impensavel/essence/blob/master/docs/XML.md#options).

## Instantiation
The class uses [**HTTPlug**](http://httplug.io), so it isn't coupled to a specific HTTP client implementation.
Following, are some examples of how to create a `Burgundy` instance using different HTTP adapters.

### Guzzle 6 HTTP Adapter

**Dependencies:**
``` bash
composer require "impensavel/ron"
composer require "php-http/message"
composer require "php-http/guzzle6-adapter"
```

**Example:**
```php
<?php

require 'vendor/autoload.php';

use Http\Adapter\Guzzle6\Client as HttpClient;
use Http\Message\MessageFactory\GuzzleMessageFactory as MessageFactory;

use Impensavel\Ron\Burgundy;
use Impensavel\Ron\RonException;

try
{
    $client = new HttpClient;
    $message = new MessageFactory;

    $burgundy = Burgundy::create($client, $message);

    $input = 'http://feeds.bbci.co.uk/news/technology/rss.xml';

    $stories = $burgundy->read($input);

    foreach ($stories as $story) {
        var_dump($story->toArray());
    }
} catch (RonException $e) {
    // Handle exceptions
}
```

### cURL client for PHP-HTTP + Guzzle PSR-7 message implementation

**Dependencies:**
``` bash
composer require "impensavel/ron"
composer require "php-http/message"
composer require "php-http/curl-client"
composer require "guzzlehttp/psr7"
```

**Example:**
```php
<?php

require 'vendor/autoload.php';

use Http\Client\Curl\Client as HttpClient;
use Http\Message\MessageFactory\GuzzleMessageFactory as MessageFactory;
use Http\Message\StreamFactory\GuzzleStreamFactory as StreamFactory;

use Impensavel\Ron\Burgundy;
use Impensavel\Ron\RonException;

try
{
    $client = new HttpClient(new MessageFactory, new StreamFactory);
    $message = new MessageFactory;

    $burgundy = Burgundy::create($client, $message);

    $input = 'http://feeds.bbci.co.uk/news/technology/rss.xml';

    $stories = $burgundy->read($input);

    foreach ($stories as $story) {
        var_dump($story->toArray());
    }
} catch (RonException $e) {
    // Handle exceptions
}
```

>**Attention:** A `RonException` will be thrown when trying to read a remote feed without an `HttpClient` + `MessageFactory` being set.

### Reading local data
When the feeds are locally available, there's no need to pass an `HttpClient` or `MessageFactory`.

**Dependencies:**
``` bash
composer require "impensavel/ron"
```

**String example:**
```php
<?php

require 'vendor/autoload.php';

use Impensavel\Ron\Burgundy;
use Impensavel\Ron\RonException;

try
{
    $burgundy = Burgundy::create();

    $input = <<< EOT
    <?xml version="1.0" encoding="utf-8"?>
    <rss xmlns:media="http://search.yahoo.com/mrss/" version="2.0">
        <!-- Data -->
    </rss>
EOT;

    $stories = $burgundy->read($input);

    foreach ($stories as $story) {
        var_dump($story->toArray());
    }
} catch (RonException $e) {
    // Handle exceptions
}
```

**Resource example:**
```php
<?php

require 'vendor/autoload.php';

use Impensavel\Ron\Burgundy;
use Impensavel\Ron\RonException;

try
{
    $burgundy = Burgundy::create();

    // Open local file
    $input = fopen('rss20.xml', 'r');

    // Open a remote file (if PHP settings allow)
    $input = fopen('http://feeds.bbci.co.uk/news/technology/rss.xml', 'r');

    $stories = $burgundy->read($input);

    foreach ($stories as $story) {
        var_dump($story->toArray());
    }
} catch (RonException $e) {
    // Handle exceptions
}
```

**SplFileInfo example:**
```php
<?php

require 'vendor/autoload.php';

use Impensavel\Ron\Burgundy;
use Impensavel\Ron\RonException;

try
{
    $burgundy = Burgundy::create();

    // Open local file
    $input = new SplFileInfo('rss20.xml')
    
    // Open a remote file (if PHP settings allow)
    $input = new SplFileInfo('http://feeds.bbci.co.uk/news/technology/rss.xml');

    $stories = $burgundy->read($input);

    foreach ($stories as $story) {
        var_dump($story->toArray());
    }
} catch (RonException $e) {
    // Handle exceptions
}
```

### Feed specifications
The following specifications are supported out of the box:

- Atom 0.3 + Dublin Core
- Atom 1.0
- RDF Site Summary 0.90 + Dublin Core
- RDF Site Summary 1.0/1.1 + Dublin Core
- Really Simple Syndication 2.0
- Rich Site Summary 0.91/0.92

If needed, a specification can be extended or added through an **optional** `array` argument.

In the following example, the RSS 0.9x/2.x spec is being extended with a subset of the Media RSS spec.
```php
$specs = [
    'rss/channel/item' => [
        'namespaces' => [
            'media' => 'http://search.yahoo.com/mrss/',
        ],
        'map'        => [
            'content_url'  => 'string(media:content/@url)',
            'content_size' => 'string(media:content/@size)',
            'content_type' => 'string(media:content/@type)',
        ],
    ],
];

// Pass the extended specs on creation in order to register them in the XML parser
$burgundy = Burgundy::create($client, $message, $specs);
```

#### Other specifications/extensions
- [Media RSS 2.0 Module](http://www.rssboard.org/media-rss)
- [OpenSearch RSS 2.0 Module](http://www.opensearch.org/Specifications/OpenSearch/1.1)
