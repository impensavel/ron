<?php

use Impensavel\Ron\Story;

/**
 * Atom 0.3 + Dublin Core
 * Atom 1.0
 *
 * Element: feed/entry
 */
return [
    'namespaces' => [
        'a03' => 'http://purl.org/atom/ns#',
        'a10' => 'http://www.w3.org/2005/Atom',
        'dc'  => 'http://purl.org/dc/elements/1.1/',
    ],
    'map'        => [
        Story::ID        => 'string(a03:id|a10:id)',
        Story::URL       => 'string(a03:link[@rel="alternate"]/@href|a10:link[@rel="alternate"]/@href)',
        Story::TITLE     => 'string(a03:title|a10:title)',
        Story::CONTENT   => 'string(a03:content|a10:content)',
        Story::AUTHOR    => 'string(a03:author/a03:name|a10:author/a10:name)',
        Story::TAGS      => 'dc:subject|a10:category/@term',
        Story::PUBLISHED => 'string(a03:issued|a10:published)',
        Story::UPDATED   => 'string(a03:modified|a10:updated)',
    ],
];
