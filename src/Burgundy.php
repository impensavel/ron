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

use GuzzleHttp\Client;
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
     * Guzzle HTTP client
     *
     * @access  protected
     * @var     \GuzzleHttp\Client
     */
    protected $http;

    /**
     * News Stories
     *
     * @access  protected
     * @var     array
     */
    protected $stories = [];

    /**
     * Burgundy constructor
     *
     * @access  public
     * @param   XMLEssence         $essence XML Essence
     * @param   \GuzzleHttp\Client $http    Guzzle HTTP client
     * @return  Burgundy
     */
    public function __construct(XMLEssence $essence, Client $http)
    {
        $this->essence = $essence;
        $this->http = $http;
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
        return array_replace_recursive($spec, [
            'map'        => [],
            'namespaces' => [],
        ]);
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
        $config = [];
        $namespaces = [];

        foreach ($specs as $xpath => $spec) {
            $spec = static::specNormaliser($spec);

            // compile element map and callback
            $config[$xpath] = [
                'map'      => $spec['map'],
                'callback' => function ($data)
                {
                    $data['extra']->stories[] = new Story($data['properties']);
                },
            ];

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
    public static function create(array $specs = [])
    {
        // default feed specifications
        $defaults = [
            // Atom 0.3 / 1.0
            'feed/entry' => [
                'namespaces' => [
                    'a03' => 'http://purl.org/atom/ns#',
                    'a10' => 'http://www.w3.org/2005/Atom',
                ],
                'map'        => [
                    Story::ID        => 'string(a03:id|a10:id)',
                    Story::URL       => 'string(a03:link[@rel="alternate"]/@href|a10:link[@rel="alternate"]/@href)',
                    Story::TITLE     => 'string(a03:title|a10:title)',
                    Story::CONTENT   => 'string(a03:content|a10:content)',
                    Story::AUTHOR    => 'string(a03:author/a03:name|a10:author/a10:name)',
                    Story::PUBLISHED => 'string(a03:issued|a10:published)',
                    Story::UPDATED   => 'string(a03:modified|a10:updated)',
                ],
            ],

            // RSS 0.9.x / 2.0
            'rss/channel/item' => [
                'namespaces' => [],
                'map'        => [
                    Story::ID        => 'string(guid)',
                    Story::URL       => 'string(link)',
                    Story::TITLE     => 'string(title)',
                    Story::CONTENT   => 'string(description)',
                    Story::AUTHOR    => 'string(author)',
                    Story::PUBLISHED => 'string(pubDate)',
                    Story::UPDATED   => 'string(pubDate)',
                ],
            ],

            // RSS 1.0 (RDF based)
            'rdf:RDF/item' => [
                'namespaces' => [
                    'r10' => 'http://purl.org/rss/1.0/',
                    'dc'  => 'http://purl.org/dc/elements/1.1/',
                ],
                'map'        => [
                    Story::ID        => 'string(@rdf:about)',
                    Story::URL       => 'string(r10:link)',
                    Story::TITLE     => 'string(r10:title)',
                    Story::CONTENT   => 'string(r10:description)',
                    Story::AUTHOR    => 'string(dc:creator)',
                    Story::PUBLISHED => 'string(dc:date)',
                    Story::UPDATED   => 'string(dc:date)',
                ],
            ],
        ];

        // merge defaults with custom specifications
        $specs = array_replace_recursive($defaults, $specs);

        // get an XML Essences
        $essence = static::createEssence($specs);

        // Guzzle HTTP client
        $http = new Client([
            'defaults' => [
                'exceptions' => false,
                'headers'    => [
                    'User-Agent' => 'Burgundy/1.0',
                ],
            ],
        ]);

        return new static($essence, $http);
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
        if (filter_var($input, FILTER_VALIDATE_URL) !== false) {
            $response = $this->http->get($input);

            $input = (string) $response->getBody();
        }

        try {
            $this->essence->extract($input, [
                'options' => LIBXML_PARSEHUGE|LIBXML_DTDLOAD,
            ], $this);
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
        $this->stories = [];
    }
}
