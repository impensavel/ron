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

class Story
{
    protected $id; // guid
    protected $link;
    protected $title;
    protected $description; // subtitle / summary / content
    protected $author; // contributor / managingEditor
    protected $published; // pubDate / lastBuildDate(channel)
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
                $this->$property = $value;
            }
        }
    }

    /**
     * Get the Item ID
     *
     * @access  public
     * @return
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Get Item link (URL)
     *
     * @access  public
     * @return
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Get
     *
     * @access  public
     * @return
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get
     *
     * @access  public
     * @return
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get
     *
     * @access  public
     * @return
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Get
     *
     * @access  public
     * @return
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Get
     *
     * @access  public
     * @return
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    public function toArray()
    {
        return [
            'id'          => $this->id,
            'link'        => $this->link,
            'title'       => $this->title,
            'description' => $this->description,
            'author'      => $this->author,
        ];
    }
}
