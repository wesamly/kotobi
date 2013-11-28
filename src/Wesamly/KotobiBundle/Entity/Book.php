<?php

namespace Wesamly\KotobiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="book")
 */

class Book
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $title;

    /**
     * @ORM\Column(type="text")
     */
    protected $intro;

    /**
     * @ORM\Column(type="integer")
     */
    protected $published;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $type;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $added;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $updated;


    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="books")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    protected $category;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Book
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set intro
     *
     * @param string $intro
     * @return Book
     */
    public function setIntro($intro)
    {
        $this->intro = $intro;
    
        return $this;
    }

    /**
     * Get intro
     *
     * @return string 
     */
    public function getIntro()
    {
        return $this->intro;
    }

    /**
     * Set published
     *
     * @param integer $published
     * @return Book
     */
    public function setPublished($published)
    {
        $this->published = $published;
    
        return $this;
    }

    /**
     * Get published
     *
     * @return integer 
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Book
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set added
     *
     * @return Book
     */
    public function setAdded()
    {
        $this->added = new \DateTime("now");
        return $this;
    }

    /**
     * Get added
     *
     * @return \DateTime 
     */
    public function getAdded()
    {
        return $this->added;
    }

    /**
     * Set updated
     *
     * @return Book
     */
    public function setUpdated()
    {
        $this->updated = new \DateTime("now");
        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set category
     *
     * @param \Wesamly\KotobiBundle\Entity\Category $category
     * @return Book
     */
    public function setCategory(\Wesamly\KotobiBundle\Entity\Category $category = null)
    {
        $this->category = $category;
    
        return $this;
    }

    /**
     * Get category
     *
     * @return \Wesamly\KotobiBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }
}