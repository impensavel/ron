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

class RSS implements FeedFormatInterface
{
    /**
     * {@inheritdoc}
     */
    public function getNamespaces()
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function getItemRoot()
    {
        return 'rss/channel/item';
    }

    /**
     * {@inheritdoc}
     */
    public function getPropertyMap()
    {
        return array(
            self::FEED_ID        => 'string(guid)',
            self::FEED_URL       => 'string(link)',
            self::FEED_TITLE     => 'string(title)',
            self::FEED_CONTENT   => 'string(description)',
            self::FEED_AUTHOR    => 'string(author)',
            self::FEED_PUBLISHED => 'string(pubDate)',
            self::FEED_UPDATED   => 'string(pubDate)',
        );
    }
}
