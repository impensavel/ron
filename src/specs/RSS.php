<?php
/**
 * This file is part of the Ron library.
 *
 * @author     Quetzy Garcia <quetzyg@impensavel.com>
 * @copyright  2015-2016
 *
 * For the full copyright and license information,
 * please view the LICENSE.md file that was distributed
 * with this source code.
 */

use Impensavel\Ron\Story;

/**
 * Rich Site Summary 0.91/0.92
 * Really Simple Syndication 2.0
 *
 * Element: rss/channel/item
 */
return [
    'name'       => 'RSS',
    'namespaces' => [],
    'map'        => [
        Story::ID        => 'string(guid)',
        Story::URL       => 'string(link)',
        Story::TITLE     => 'string(title)',
        Story::CONTENT   => 'string(description)',
        Story::AUTHOR    => 'string(author)',
        Story::TAGS      => 'category',
        Story::PUBLISHED => 'string(pubDate)',
        Story::UPDATED   => 'string(pubDate)',
    ],
];
