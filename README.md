# Ron
[![Latest Stable Version](https://poser.pugx.org/impensavel/ron/v/stable)](https://packagist.org/packages/impensavel/ron)

**Read Off News** is a PHP RSS/Atom News Reader Library.

This library aims for [PSR-2][] and [PSR-4][] standards compliance.

[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md

## Requirements
* [PHP](http://www.php.net) 5.5+
* [Essence](https://packagist.org/packages/impensavel/essence)
* [Carbon](https://packagist.org/packages/nesbot/carbon)

## Optional requirements
In order to parse news feeds from a URL, the following packages are needed:
* [HTTP Message related tools](https://packagist.org/packages/php-http/message)
* A package that provides [php-http/client-implementation](https://packagist.org/providers/php-http/client-implementation)

## Installation
``` bash
composer require "impensavel/ron"
composer require "php-http/guzzle6-adapter"
```
>**TIP:** This library isn't coupled to a specific HTTP client! Read the **Burgundy** [documentation](docs/Burgundy.md) for more information.


## Usage example
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

    $stories = $burgundy->read('http://feeds.bbci.co.uk/news/technology/rss.xml');
    
    foreach ($stories as $story) {
        var_dump($story->toArray());
    }

} catch (RonException $e) {
    // Handle exceptions
}
```

## Class documentation
- [Burgundy](docs/Burgundy.md)
- [Story](docs/Story.md)

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
