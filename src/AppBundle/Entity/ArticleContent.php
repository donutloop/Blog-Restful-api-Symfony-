<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ArticleContent
 *
 * @ORM\Table(name="article_content")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArticleContentRepository")
 * @ORM\HasLifecycleCallbacks
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
     * @ORM\ManyToOne(targetEntity="Article")
     * @ORM\JoinColumn(name="article_id", referencedColumnName="id")
     */
    private $article_id;

    /**
     * Get article
     *
     * @param $article
     */
    public function setArticle(Article $article){
        $this->article_id = $article;
    }

    /**
     * Get article
     *
     * @return mixed
     */
    public function getArticle(){
        return $this->article_id;
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

