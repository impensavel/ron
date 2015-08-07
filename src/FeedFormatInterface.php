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

interface FeedFormatInterface
{
    const FEED_ID        = 'id';
    const FEED_URL       = 'url';
    const FEED_TITLE     = 'title';
    const FEED_CONTENT   = 'content';
    const FEED_AUTHOR    = 'author';
    const FEED_PUBLISHED = 'published';
    const FEED_UPDATED   = 'updated';

    /**
     * Get feed format Namespaces
     *
     * @access  public
     * @return  array
     */
    public function getNamespaces();

    /**
     * Get Item Element root
     *
     * @access  public
     * @return  string
     */
    public function getItemRoot();

    /**
     * Get Item Property Map
     *
     * @access  public
     * @return  array
     */
    public function getPropertyMap();
}
