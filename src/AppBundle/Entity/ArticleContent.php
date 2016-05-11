<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ArticleContent
 *
 * @ORM\Table(name="article_content")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArticleContentRepository")
 */
class ArticleContent
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
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="content_type", type="string", length=255)
     */
    private $contentType;

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
     * @ORM\ManyToOne(targetEntity="Article", inversedBy="contents")
     */
    private $articles;

    /**
     * Tag constructor.
     */
    public function __construct() {
        $this->articles = new ArrayCollection();
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
     * Set content
     *
     * @param string $content
     *
     * @return ArticleContent
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set contentType
     *
     * @param string $contentType
     *
     * @return ArticleContent
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;

        return $this;
    }

    /**
     * Get contentType
     *
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Add article
     *
     * @param Article $article
     * @return Tag
     */
    public function addArticle(Article $article)
    {
        $this->articles[] = $article;
        return $this;
    }
    /**
     * Remove article
     *
     * @param Article $article
     */
    public function removeArticle(Article $article)
    {
        $this->articles->removeElement($article);
    }

    /**
     * Get articles
     *
     * @return ArrayCollection
     */
    public function getArticles(){
        return $this->articles;
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

