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

namespace Impensavel\Ron;

use DOMNodeList;
use Exception;

use Http\Client\HttpClient;
use Http\Message\MessageFactory;
use Impensavel\Essence\EssenceException;
use Impensavel\Essence\XML;

class Burgundy
{
    /**
     * XML parser
     *
     * @access  protected
     * @var     XML
     */
    protected $parser;

    /**
     * HTTP client
     *
     * @access  protected
     * @var     HttpClient
     */
    protected $client;

    /**
     * Message factory
     *
     * @access  protected
     * @var     \Http\Message\MessageFactory
     */
    protected $message;

    /**
     * Burgundy constructor
     *
     * @access  public
     * @param   XML            $parser  XML parser
     * @param   HttpClient     $client  HTTP client
     * @param   MessageFactory $message Message factory
     * @return  Burgundy
     */
    public function __construct(XML $parser, HttpClient $client = null, MessageFactory $message = null)
    {
        $this->parser = $parser;
        $this->client = $client;
        $this->message = $message;
    }

    /**
     * Normalise Feed Specification
     *
     * @static
     * @access  protected
     * @param   array  $spec Feed specification
     * @return  array
     */
    protected static function normalise(array $spec)
    {
        if (! isset($spec['name'])) {
            throw new RonException('Feed specification name not set');
        }

        return array_replace_recursive($spec, [
            'map'        => [],
            'namespaces' => [],
        ]);
    }

    /**
     * Create an XML parser
     *
     * @static
     * @access  protected
     * @param   array  $specs Feed specifications
     * @throws  RonException
     * @return  XML
     */
    protected static function createParser(array $specs)
    {
        $config = [];
        $namespaces = [];

        foreach ($specs as $xpath => $spec) {
            $spec = static::normalise($spec);

            // Compile Element map and data handler
            $config[$xpath] = [
                'map'     => $spec['map'],
                'handler' => function ($element, array $properties, &$stories) use ($spec) {
                    foreach ($properties as $name => $value) {
                        if ($value instanceof DOMNodeList) {
                            $properties[$name] = XML::DOMNodeListToArray($value);
                        }
                    }

                    $stories[] = new Story($spec['name'], $properties);
                },
            ];

            // Compile namespaces
            $namespaces = array_merge($namespaces, $spec['namespaces']);
        }

        try {
            return new XML($config, $namespaces);
        } catch (EssenceException $e) {
            throw new RonException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Create a Burgundy object
     *
     * @static
     * @access  public
     * @param   HttpClient     $client
     * @param   MessageFactory $message
     * @param   array          $specs   Feed specifications
     * @throws  RonException
     * @return  Burgundy
     */
    public static function create(HttpClient $client = null, MessageFactory $message = null, array $specs = [])
    {
        // Default Feed specifications
        $specs = array_replace_recursive([
            'feed/entry'       => require 'specs/Atom.php',
            'rss/channel/item' => require 'specs/RSS.php',
            'rdf:RDF/item'     => require 'specs/RDF.php',
        ], $specs);

        $parser = static::createParser($specs);

        return new static($parser, $client, $message);
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
        // Fetch input from a URL
        if ($url = filter_var($input, FILTER_VALIDATE_URL)) {
            if ($this->client === null || $this->message === null) {
                throw new RonException('HttpClient and MessageFactory required to fetch data from a URL');
            }

            try {
                $request = $this->message->createRequest('GET', $url);
                $response = $this->client->sendRequest($request);
                $input = $response->getBody()->getContents();
            } catch (Exception $e) {
                throw new RonException($e->getMessage(), $e->getCode(), $e);
            }
        }

        try {
            $options = array_replace_recursive([
                'options'  => LIBXML_PARSEHUGE|LIBXML_DTDLOAD|LIBXML_NSCLEAN,
            ], $options);

            $stories = [];

            $this->parser->extract($input, $options, $stories);

            return $stories;
        } catch (EssenceException $e) {
            throw new RonException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
