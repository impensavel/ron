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

use Exception;

use Carbon\Carbon;

class Story
{
    /**
     * Standard Story property names
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
     * Story ID
     *
     * @access  protected
     * @var     string
     */
    protected $id;

    /**
     * Story URL
     *
     * @access  protected
     * @var     string
     */
    protected $url;

    /**
     * Story Title
     *
     * @access  protected
     * @var     string
     */
    protected $title;

    /**
     * Story Content
     *
     * @access  protected
     * @var     string
     */
    protected $content;

    /**
     * Story Author
     *
     * @access  protected
     * @var     string
     */
    protected $author;

    /**
     * Story Tags
     *
     * @access  protected
     * @var     array
     */
    protected $tags;

    /**
     * Story Published Date
     *
     * @access  protected
     * @var     Carbon
     */
    protected $published;

    /**
     * Story Updated Date
     *
     * @access  protected
     * @var     Carbon
     */
    protected $updated;

    /**
     * Story constructor
     *
     * @access  public
     * @param   array  $properties
     * @throws  RonException
     * @return  Story
     */
    public function __construct(array $properties)
    {
        try {
            foreach ($properties as $property => $value) {
                if (property_exists($this, $property)) {
                    // convert date properties into Carbon objects
                    $isDate = in_array($property, [
                        self::PUBLISHED,
                        self::UPDATED,
                    ]);

                    $this->$property = $isDate ? new Carbon($value) : $value;
                }
            }
        } catch (Exception $e) {
            throw new RonException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Get the Story ID
     *
     * @access  public
     * @return  string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the Story URL
     *
     * @access  public
     * @return  string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Get the Story Title
     *
     * @access  public
     * @return  string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get the Story Content
     *
     * @access  public
     * @return  string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Get the Story Author
     *
     * @access  public
     * @return  string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Get the Story Tags
     *
     * @access  public
     * @return  array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Get the Story Published Date
     *
     * @access  public
     * @return  Carbon
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Get the Story Updated Date
     *
     * @access  public
     * @return  Carbon
     */
    public function getUpdated()
    {
        return $this->updated;
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
