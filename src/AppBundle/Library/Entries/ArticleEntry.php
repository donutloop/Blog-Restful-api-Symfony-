<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace AppBundle\Library\Entries;

use AppBundle\Entity\User;
use BaseBundle\Library\DatabaseEntryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\Type;

class ArticleEntry implements DatabaseEntryInterface{

    /**
     * @Type("integer")
     */
    private $id;
    
    /**
     * @Type("string")
     */
    private $username = null;

    /**
     * @Type("string")
     */
    private $title = null;


    /**
     * @Type("ArrayCollection<AppBundle\Library\Entries\ArticleContentEntry>")
     */
    private $contents = null;

    /**
     * @Type("ArrayCollection<AppBundle\Library\Entries\TagEntry>")
     */
    private $tags = null;

    /**
     * @var User
     */
    private $user = null;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    
    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return ArticleEntry
     */
    public function setUsername($username): ArticleEntry
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return ArticleEntry
     */
    public function setTitle($title): ArticleEntry
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * @param ArrayCollection $contents
     * @return ArticleEntry
     */
    public function setContents($contents): ArticleEntry
    {
        $this->contents = $contents;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param ArrayCollection $tags
     * @return ArticleEntry
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getIdentifier()
    {
        return $this->getTitle();
    }

}