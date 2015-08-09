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

use ArrayIterator;
use Countable;
use IteratorAggregate;

use Impensavel\Essence\EssenceException;
use Impensavel\Essence\XMLEssence;

class Burgundy implements Countable, IteratorAggregate
{
    /**
     * XML Essence
     *
     * @access  protected
     * @var     XMLEssence
     */
    protected $essence;

    /**
     * News Stories
     *
     * @access  protected
     * @var     array
     */
    protected $stories = array();

    /**
     * Burgundy constructor
     *
     * @access  public
     * @param   XMLEssence $essence XML Essence
     * @return  Burgundy
     */
    public function __construct(XMLEssence $essence)
    {
        $this->essence = $essence;
    }

    /**
     * Make sure the feed specification has the required structure
     *
     * @static
     * @access  public
     * @param   array  $spec Feed Specification
     * @return  array
     */
    public static function specNormaliser(array $spec)
    {
        return array_replace_recursive($spec, array(
            'map'        => array(),
            'namespaces' => array(),
        ));
    }

    /**
     * Create a XML Essence object
     *
     * @static
     * @access  public
     * @param   array  $specs Feed specifications
     * @throws  RonException
     * @return  XMLEssence
     */
    public static function createEssence(array $specs)
    {
        $config = array();
        $namespaces = array();

        foreach ($specs as $xpath => $spec) {
            $spec = static::specNormaliser($spec);

            // compile element map and callback
            $config[$xpath] = array(
                'map'      => $spec['map'],
                'callback' => function ($data)
                {
                    $data['extra']->stories[] = new Story($data['properties']);
                },
            );

            // compile namespaces
            $namespaces = array_merge($namespaces, $spec['namespaces']);
        }

        try {
            return new XMLEssence($config, $namespaces);
        } catch (EssenceException $e) {
            throw new RonException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Create a Burgundy object
     *
     * @static
     * @access  public
     * @param   array  $specs Extra feed specifications
     * @throws  RonException
     * @return  Burgundy
     */
    public static function create(array $specs = array())
    {
        // default feed specifications
        $defaults = array(
            // Atom 0.3 / 1.0
            'feed/entry' => array(
                'namespaces' => array(
                    'a03' => 'http://purl.org/atom/ns#',
                    'a10' => 'http://www.w3.org/2005/Atom',
                ),
                'map'        => array(
                    Story::ID        => 'string(a03:id|a10:id)',
                    Story::URL       => 'string(a03:link[@rel="alternate"]/@href|a10:link[@rel="alternate"]/@href)',
                    Story::TITLE     => 'string(a03:title|a10:title)',
                    Story::CONTENT   => 'string(a03:content|a10:content)',
                    Story::AUTHOR    => 'string(a03:author/a03:name|a10:author/a10:name)',
                    Story::PUBLISHED => 'string(a03:issued|a10:published)',
                    Story::UPDATED   => 'string(a03:modified|a10:updated)',
                ),
            ),

            // RSS 0.9.x / 2.0
            'rss/channel/item' => array(
                'namespaces' => array(),
                'map'        => array(
                    Story::ID        => 'string(guid)',
                    Story::URL       => 'string(link)',
                    Story::TITLE     => 'string(title)',
                    Story::CONTENT   => 'string(description)',
                    Story::AUTHOR    => 'string(author)',
                    Story::PUBLISHED => 'string(pubDate)',
                    Story::UPDATED   => 'string(pubDate)',
                ),
            ),
        );

        // merge defaults with custom specifications
        $specs = array_replace_recursive($defaults, $specs);

        // get an XML Essences
        $essence = static::createEssence($specs);

        return new static($essence);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->stories);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new ArrayIterator($this->stories);
    }

    /**
     * Read News Stories
     *
     * @access  public
     * @param   mixed  $input
     * @throws  RonException
     * @return  void
     */
    public function read($input)
    {
        try {
            $this->essence->extract($input, array(
                'options' => LIBXML_PARSEHUGE|LIBXML_DTDLOAD,
            ), $this);
        } catch (EssenceException $e) {
            throw new RonException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Clear News Stories
     *
     * @access  public
     * @return  void
     */
    public function clear()
    {
        $this->stories = array();
    }
}
