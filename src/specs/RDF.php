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
 * RDF Site Summary 0.90 + Dublin Core
 * RDF Site Summary 1.0/1.1 + Dublin Core
 *
 * Element: rdf:RDF/item
 */
return [
    'name'       => 'RDF',
    'namespaces' => [
        'r090' => 'http://my.netscape.com/rdf/simple/0.9/',
        'r10'  => 'http://purl.org/rss/1.0/',
        'dc'   => 'http://purl.org/dc/elements/1.1/',
    ],
    'map'        => [
        Story::ID        => 'string(r090:link|r10:link)',
        Story::URL       => 'string(r090:link|r10:link)',
        Story::TITLE     => 'string(r090:title|r10:title)',
        Story::CONTENT   => 'string(r10:description)',
        Story::AUTHOR    => 'string(dc:creator)',
        Story::TAGS      => 'dc:subject',
        Story::PUBLISHED => 'string(dc:date)',
        Story::UPDATED   => 'string(dc:date)',
    ],
];
