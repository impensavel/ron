<?php
/**
 * This file is part of the Ron library.
 *
 * @author     Quetzy Garcia <quetzyg@impensavel.com>
 * @copyright  2015
 *
 * For the full copyright and license information,
 * please view the LICENSE.md file that was distributed
 * with this source code.
 */

namespace Impensavel\Ron;

class Atom implements FeedFormatInterface
{
    /**
     * {@inheritdoc}
     */
    public function getNamespaces()
    {
        return array(
            // Atom 1.0
            'a10' => 'http://www.w3.org/2005/Atom',

            // Atom 0.3
            'a03' => 'http://purl.org/atom/ns#',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getItemRoot()
    {
        return 'feed/entry';
    }

    /**
     * {@inheritdoc}
     */
    public function getPropertyMap()
    {
        return array(
            self::FEED_ID        => 'string(a03:id|a10:id)',
            self::FEED_URL       => 'string(a03:link[@rel="alternate"]/@href|a10:link[@rel="alternate"]/@href)',
            self::FEED_TITLE     => 'string(a03:title|a10:title)',
            self::FEED_CONTENT   => 'string(a03:content|a10:content)',
            self::FEED_AUTHOR    => 'string(a03:author/a03:name|a10:author/a10:name)',
            self::FEED_PUBLISHED => 'string(a03:issued|a10:published)',
            self::FEED_UPDATED   => 'string(a03:modified|a10:updated)',
        );
    }
}
