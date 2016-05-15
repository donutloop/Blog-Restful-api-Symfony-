<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Article
 *
 * @ORM\Table(name="article")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArticleRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Article
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\NotNull()
     * @Assert\Length(min=3, max=255)
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /** 
     * @var string
     * 
     * @ORM\Column(name="created_at", type="string", length=255) 
     */
    private $createdAt;

    /**
     * @var string 
     * 
     * @ORM\Column(name="update_at", type="string", length=255, nullable=true)
     */
    private $updateAt;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user_id;

    /**
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="articles")
     * @ORM\JoinTable(name="article_tag")
     */
    private $tags;

    /**
     * Article constructor.
     */
    public function __construct() {
        $this->tags = new ArrayCollection();
    }
    
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Article
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
     * Get user
     *
     * @return User
     */
    public function getUser(){
        return $this->user_id;
    }

    /**
     * Set user
     *
     * @param $user
     */
    public function setUser(User $user){
        $this->user_id = $user;
    }
    
    /**
     * Add tags
     *
     * @param Tag $tag
     * @return Article
     */
    public function addTag(Tag $tag)
    {
        $this->tags[] = $tag;
        return $this;
    }
    
    /**
     * Remove tags
     *
     * @param Tag $tag
     */
    public function removeTag(Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return ArrayCollection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     *  Set create at  
     * 
     * @ORM\PrePersist 
     */
    public function setCreateAt(){
        $this->createdAt = date('Y-m-d H:i:s');
    }
    
    /**
     * Set update at 
     * 
     * @ORM\PreUpdate
     */
    public function setUpdateAt(){
       $this->updateAt = date('Y-m-d H:i:s'); 
    }

    /**
     * get create at 
     * 
     * @return string
     */
    public function getCreateAt(){
        return $this->createdAt;
    }

    /**
     * get update at 
     * 
     * @return string
     */
    public function getUpdateAt(){
        return $this->updateAt;
    }
}
