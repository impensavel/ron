# Burgundy
The `Burgundy` class is in charge of reading News Stories.

## Usage
This document contains usage examples, along with a description of configuration options for the `Burgundy` class.

## Instantiation
The `create()` method will generate a new `Burgundy` instance.

```php
$burgundy = Burgundy::create();
```

The default configurations for the feed specs and Guzzle HTTP client, can be changed through an **optional** `array` argument.

```php
$config = [
    // extend the RSS 0.9X/2.X spec with a subset of the Media RSS specification
    'specs' => [
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
    ],

    // set a different user agent
    'http'  => [
        'defaults' => [
            'headers' => [
                'User-Agent' => 'My-User/Agent',
            ],
        ],
    ],
];

$burgundy = Burgundy::create($config);
```

### Extensions
- [Media RSS 2.0 Module](http://www.rssboard.org/media-rss)
- [OpenSearch RSS 2.0 Module](http://www.opensearch.org/Specifications/OpenSearch/1.1)

### HTTP Client
Check the Guzzle HTTP client [documentation](http://guzzle.readthedocs.org/en/5.3/) for other configuration options.

## Reading News Stories
The `read()` method accepts two arguments. 

The first being the input, which can be a URL of a RSS/Atom feed, a `string` with feed data, a `resource` or a `SplFileInfo` object,
while the (**optional**) second one should be an `array`, which will be used to pass custom configurations to the XML parser.

Know more about those configurations in the `XMLEssense` [documentation](https://github.com/impensavel/essence/blob/master/docs/XMLEssence.md#options).

### URL
```php
$stories = $burgundy->read('http://feeds.bbci.co.uk/news/technology/rss.xml');
```

### String
```php
$input = <<< EOL
<?xml version="1.0" encoding="UTF-8"?>
<rss version="0.91">
    <!-- feed data -->
</rss>
EOL;

$stories = $burgundy->read($input);
```

### Resource
```php
// opening a local file
$input = fopen('atom.xml', 'r');

// opening a URL (if PHP settings permit)
$input = fopen('http://feeds.bbci.co.uk/news/technology/rss.xml', 'r');

$stories = $burgundy->read($input);
```

### SplFileInfo
```php
// opening a local file
$input = new SplFileInfo('rss.xml');

// opening a URL (if PHP settings permit)
$input = new SplFileInfo('http://feeds.bbci.co.uk/news/technology/rss.xml');

$stories = $burgundy->read($input);
```
