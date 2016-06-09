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
In order to parse news feeds from a remote source (URL), the following packages are needed:
* [HTTP Message related tools](https://packagist.org/packages/php-http/message)
* A package that provides [php-http/client-implementation](https://packagist.org/providers/php-http/client-implementation)

## Installation
``` bash
composer require "impensavel/ron"
```

## Class documentation
- [Burgundy](docs/Burgundy.md)
- [Story](docs/Story.md)

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
