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

use Carbon\Carbon;

class Story
{
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
     * @return  Story
     */
    public function __construct(array $properties)
    {
        foreach ($properties as $property => $value) {
            if (property_exists($this, $property)) {
                // create Carbon objects for date properties
                $isDate = in_array($property, array(
                    FeedFormatInterface::FEED_PUBLISHED,
                    FeedFormatInterface::FEED_UPDATED,
                ));

                $this->$property = $isDate ? new Carbon($value) : $value;
            }
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
        return array(
            'id'        => $this->id,
            'url'       => $this->url,
            'title'     => $this->title,
            'content'   => $this->content,
            'author'    => $this->author,
            'published' => $this->published,
            'updated'   => $this->updated,
        );
    }
}
