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
     * @access  protected
     * @param   array  $spec Feed Specification
     * @return  array
     */
    protected static function normalise(array $spec)
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
     * @access  protected
     * @param   array  $specs Feed specifications
     * @throws  RonException
     * @return  XMLEssence
     */
    protected static function essentialise(array $specs)
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
                        if ($value instanceof DOMNodeList) {
                            $properties[$name] = XMLEssence::DOMNodeListToArray($value);
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
     * @param   array  $config Configurations
     * @throws  RonException
     * @return  Burgundy
     */
    public static function create(array $config = [])
    {
        // configuration defaults
        $config = array_replace_recursive([
            'specs'     => [
                'feed/entry'       => require 'specs/Atom.php',
                'rss/channel/item' => require 'specs/RSS.php',
                'rdf:RDF/item'     => require 'specs/RDF.php',
            ],
            'http'      => [
                'defaults' => [
                    'headers' => [
                        'User-Agent' => 'Burgundy/1.0',
                    ],
                ],
            ],
        ], $config, [
            'http' => [
                'defaults' => [
                    'exceptions' => false,
                ],
            ],
        ]);

        $essence = static::essentialise($config['specs']);

        $http = new Client($config['http']);

        return new static($essence, $http);
    }

    /**
     * Read News Stories
     *
     * @access  public
     * @param   mixed  $input
     * @param   array  $options
     * @throws  RonException
     * @return  array
     */
    public function read($input, array $options = [])
    {
        $options = array_replace_recursive([
            'options'  => LIBXML_PARSEHUGE|LIBXML_DTDLOAD|LIBXML_NSCLEAN,
        ], $options);

        try {
            $stories = [];

            // fetch the content if the input is a URL
            if (filter_var($input, FILTER_VALIDATE_URL) !== false) {
                $response = $this->http->get($input);

                $input = (string) $response->getBody();
            }

            $this->essence->extract($input, $options, $stories);

            return $stories;

        } catch (Exception $e) {
            throw new RonException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
