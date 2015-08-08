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
     * Create a Burgundy object
     *
     * @static
     * @access  public
     * @param   array  $formats Custom feed formats
     * @throws  RonException
     * @return  Burgundy
     */
    public static function create(array $formats = array())
    {
        $defaults = array(
            new Atom,
            new RSS,
        );

        // override default Atom/RSS formats with custom ones
        $formats = array_merge($defaults, $formats);

        $config = array();
        $namespaces = array();

        foreach ($formats as $format) {
            // register element map and callback
            $config[$format->getItemRoot()] = array(
                'map'      => $format->getPropertyMap(),
                'callback' => function ($data)
                {
                    $data['extra']->stories[] = new Story($data['properties']);
                },
            );

            // register namespaces
            $namespaces = array_merge($namespaces, $format->getNamespaces());
        }

        try {
            return new static(new XMLEssence($config, $namespaces));
        } catch (EssenceException $e) {
            throw new RonException($e->getMessage(), $e->getCode(), $e);
        }
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
