# Ron
[![Latest Stable Version](https://poser.pugx.org/impensavel/ron/v/stable)](https://packagist.org/packages/impensavel/ron)

Ron is a PHP RSS/Atom News Reader Library and it stands for **Read Off News**.

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
composer require "impensavel/ron:dev-master"
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

    // read available news stories from an input source
    $burgundy->read('http://feeds.bbci.co.uk/news/technology/rss.xml');
    
    // traverse through each news story
    foreach ($burgundy as $story) {
        var_dump($story->toArray());
    }

    // clear existing news stories
    $burgundy->clear();

} catch (RonException $e) {
    // handle exceptions
}
```
## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
