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
use Impensavel\Essence\XMLEssence;

class Burgundy implements Countable, IteratorAggregate
{
    /**
     * XML parser
     *
     * @access  protected
     * @var     XMLEssence
     */
    protected $essence;

    /**
     * Story Items
     *
     * @access  protected
     * @var     array
     */
    protected $stories = [];

    /**
     * Namespace registry
     *
     * @access  protected
     * @var     array
     */
    protected $namespaces = [];

    /**
     * Burgundy constructor
     *
     * @access  public
     * @param   \GuzzleHttp\Client $http Guzzle HTTP client
     * @return  Burgundy
     */
    public function __construct(Client $http)
    {
        $this->http = $http;
    }

    /**
     * Set XML parser
     *
     * @access  public
     * @param   array  $formats Feed formats
     * @return  Burgundy
     */
    public function setParser(array $formats)
    {
        $config = [];

        foreach ($formats as $format) {
            $config[$format->getItemRoot()] = [
                'map'      => $format->getPropertyMap(),
                'callback' => $this->getStoryProcessor(),
            ];

            // register namespaces
            $this->namespaces = array_merge($this->namespaces, $format->getNamespaces());
        }

        $this->essence = new XMLEssence($config);

        return $this;
    }

    /**
     * Create a Burgundy object
     *
     * @static
     * @access  public
     * @param   array  $formats Custom feed formats
     * @return  Burgundy
     */
    public static function create(array $formats = [])
    {
        $defaults = [
            new Atom,
            new RSS,
        ];

        // override default Atom/RSS formats with custom ones
        $formats = array_merge($defaults, $formats);

        // Guzzle HTTP client
        $http = new Client([
            'defaults' => [
                'exceptions' => false,
                'headers'    => [
                    'User-Agent' => 'Burgundy/1.0',
                ],
            ],
        ]);

        $burgundy = new static($http);

        return $burgundy->setParser($formats);
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
     * Return a Story processor
     *
     * @access  protected
     * @return  \Closure
     */
    protected function getStoryProcessor()
    {
        return function ($data)
        {
            $this->stories[] = new Story($data['properties']);
        };
    }

    /**
     * Read News from an input
     *
     * @access  public
     * @param   mixed  $input
     * @return  void
     */
    public function read($input)
    {
        if (filter_var($input, FILTER_VALIDATE_URL) !== false) {
            $response = $this->http->get($input);

            $input = (string) $response->getBody();
        }

        $this->essence->extract($input, [
            'namespaces' => $this->namespaces,
        ]);
    }
}
