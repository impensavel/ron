<?php

use Impensavel\Ron\Story;

/**
 * Rich Site Summary 0.91/0.92
 * Really Simple Syndication 2.0
 *
 * Element: rss/channel/item
 */
return [
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
