# Ron
[![Latest Stable Version](https://poser.pugx.org/impensavel/ron/v/stable)](https://packagist.org/packages/impensavel/ron)

**Read Off News** is a PHP RSS/Atom News Reader Library.

This library aims for [PSR-1][], [PSR-2][] and [PSR-4][] standards compliance.

[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md

## Requirements
* [PHP](http://www.php.net) 5.4+
* [Essence](https://packagist.org/packages/impensavel/essence)
* [Carbon](https://packagist.org/packages/nesbot/carbon)
* [Guzzle](https://packagist.org/packages/guzzlehttp/guzzle)

## Installation
``` bash
composer require "impensavel/ron"
```

## Usage example
```php
<?php

require 'vendor/autoload.php';

use Impensavel\Ron\Burgundy;
use Impensavel\Ron\RonException;

try
{
    $burgundy = Burgundy::create();

    $stories = $burgundy->read('http://feeds.bbci.co.uk/news/technology/rss.xml');
    
    foreach ($stories as $story) {
        var_dump($story->toArray());
    }

} catch (RonException $e) {
    // handle exceptions
}
```

## Class documentation
- [Burgundy](docs/Burgundy.md)
- [Story](docs/Story.md)

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
