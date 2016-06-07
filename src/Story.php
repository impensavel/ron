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

use Exception;

use Carbon\Carbon;

class Story
{
    /**
     * Story property names
     *
     * @var string
     */
    const ID        = 'id';
    const URL       = 'url';
    const TITLE     = 'title';
    const CONTENT   = 'content';
    const AUTHOR    = 'author';
    const TAGS      = 'tags';
    const PUBLISHED = 'published';
    const UPDATED   = 'updated';

    /**
     * Specification
     *
     * @access  protected
     * @var     string
     */
    protected $spec;

    /**
     * ID
     *
     * @access  protected
     * @var     string
     */
    protected $id;

    /**
     * Url
     *
     * @access  protected
     * @var     string
     */
    protected $url;

    /**
     * Title
     *
     * @access  protected
     * @var     string
     */
    protected $title;

    /**
     * Content
     *
     * @access  protected
     * @var     string
     */
    protected $content;

    /**
     * Author
     *
     * @access  protected
     * @var     string
     */
    protected $author;

    /**
     * Tags
     *
     * @access  protected
     * @var     array
     */
    protected $tags;

    /**
     * Published Date
     *
     * @access  protected
     * @var     Carbon
     */
    protected $published;

    /**
     * Updated Date
     *
     * @access  protected
     * @var     Carbon
     */
    protected $updated;

    /**
     * Extra properties
     *
     * @access  protected
     * @var     array
     */
    protected $extra = [];

    /**
     * Story constructor
     *
     * @access  public
     * @param   string $spec
     * @param   array  $properties
     * @throws  RonException
     * @return  Story
     */
    public function __construct($spec, array $properties)
    {
        try {
            $this->spec = $spec;

            foreach ($properties as $property => $value) {
                // Test the property value for a date format
                $isDate = is_string($value) && (strtotime($value) !== false);

                if (property_exists($this, $property)) {
                    $this->$property = $isDate ? new Carbon($value) : $value;
                } else {
                    $this->extra[$property] = $isDate ? new Carbon($value) : $value;
                }
            }
        } catch (Exception $e) {
            throw new RonException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Get the Specification
     *
     * @access  public
     * @return  string
     */
    public function getSpec()
    {
        return $this->spec;
    }

    /**
     * Get the ID
     *
     * @access  public
     * @return  string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the Url
     *
     * @access  public
     * @return  string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Get the Title
     *
     * @access  public
     * @return  string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get the Content
     *
     * @access  public
     * @return  string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Get the Author
     *
     * @access  public
     * @return  string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Get the Tags
     *
     * @access  public
     * @return  array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Get the Published Date
     *
     * @access  public
     * @return  Carbon
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Get the Updated Date
     *
     * @access  public
     * @return  Carbon
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Get Extra properties
     *
     * @access  public
     * @param   string $property Extra property name
     * @throws  RonException
     * @return  mixed
     */
    public function getExtra($property = null)
    {
        if ($property === null) {
            return $this->extra;
        }

        if (array_key_exists($property, $this->extra)) {
            return $this->extra[$property];
        }

        throw new RonException('Invalid property: '.$property);
    }

    /**
     * Get an array with the Story properties
     *
     * @access  public
     * @return  array
     */
    public function toArray()
    {
        return [
            self::ID        => $this->id,
            self::URL       => $this->url,
            self::TITLE     => $this->title,
            self::CONTENT   => $this->content,
            self::AUTHOR    => $this->author,
            self::TAGS      => $this->tags,
            self::PUBLISHED => $this->published,
            self::UPDATED   => $this->updated,
        ];
    }
}
