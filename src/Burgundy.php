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

use DOMNodeList;
use Exception;

use GuzzleHttp\Client;
use Impensavel\Essence\EssenceException;
use Impensavel\Essence\XMLEssence;

class Burgundy
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
     * Normalise Feed Specification
     *
     * @static
     * @access  public
     * @param   array  $spec Feed Specification
     * @return  array
     */
    public static function normalise(array $spec)
    {
        return array_replace_recursive($spec, [
            'map'        => [],
            'namespaces' => [],
        ]);
    }

    /**
     * Create an XML Essence object
     *
     * @static
     * @access  public
     * @param   array  $specs Feed specifications
     * @throws  RonException
     * @return  XMLEssence
     */
    public static function essentialise(array $specs)
    {
        $config = [];
        $namespaces = [];

        foreach ($specs as $xpath => $spec) {
            $spec = static::normalise($spec);

            // compile element map and data handler
            $config[$xpath] = [
                'map'     => $spec['map'],
                'handler' => function ($element, array $properties, &$stories)
                {
                    foreach ($properties as $name => $value) {
                        // convert DOMNodeLists into arrays
                        if ($value instanceof DOMNodeList) {
                            $properties[$name] = [];

                            foreach ($value as $node) {
                                $properties[$name][] = $node->nodeValue;
                            }
                        }
                    }

                    $stories[] = new Story($properties);
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
            // Atom 0.3 + Dublin Core
            // Atom 1.0
            'feed/entry' => [
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
            ],

            // Rich Site Summary 0.91, 0.92
            // Really Simple Syndication 2.0
            'rss/channel/item' => [
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
            ],

            // RDF Site Summary 0.90, 1.0, 1.1 + Dublin Core
            'rdf:RDF/item' => [
                'namespaces' => [
                    'r090' => 'http://my.netscape.com/rdf/simple/0.9/',
                    'r10'  => 'http://purl.org/rss/1.0/',
                    'dc'   => 'http://purl.org/dc/elements/1.1/',
                ],
                'map'        => [
                    Story::ID        => 'string(@rdf:about)',
                    Story::URL       => 'string(r090:link|r10:link)',
                    Story::TITLE     => 'string(r090:title|r10:title)',
                    Story::CONTENT   => 'string(r10:description)',
                    Story::AUTHOR    => 'string(dc:creator)',
                    Story::TAGS      => 'dc:subject',
                    Story::PUBLISHED => 'string(dc:date)',
                    Story::UPDATED   => 'string(dc:date)',
                ],
            ],
        ];

        // merge defaults with custom specifications
        $specs = array_replace_recursive($defaults, $specs);

        // XML Essence
        $essence = static::essentialise($specs);

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
     * Read News Stories
     *
     * @access  public
     * @param   mixed  $input
     * @throws  RonException
     * @return  array
     */
    public function read($input)
    {
        try {
            $stories = [];

            // fetch the content if the input is a URL
            if (filter_var($input, FILTER_VALIDATE_URL) !== false) {
                $response = $this->http->get($input);

                $input = (string) $response->getBody();
            }

            $this->essence->extract($input, [
                'options' => LIBXML_PARSEHUGE|LIBXML_DTDLOAD|LIBXML_NSCLEAN,
            ], $stories);

            return $stories;

        } catch (Exception $e) {
            throw new RonException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
