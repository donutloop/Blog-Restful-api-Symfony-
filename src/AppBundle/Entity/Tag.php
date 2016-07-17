<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace AppBundle\Entity;

use BaseBundle\Library\DatabaseWorkflowEntityInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Article;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation as JMSAnnotation;

/**
 * Tag
 *
 * @ORM\Table(name="tag")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TagRepository")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity("name")
 *
 * @JMSAnnotation\ExclusionPolicy("all")
 */
class Tag implements DatabaseWorkflowEntityInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @JMSAnnotation\Expose
     * @JMSAnnotation\Groups({"viewdata", "viewdata_list", "viewdata_reverse_list"})
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\notNull()
     * @Assert\Length(min=3, max=30)
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     *
     * @JMSAnnotation\Expose
     * @JMSAnnotation\Groups({"viewdata", "viewdata_list", "viewdata_reverse_list"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="created_at", type="string", length=255)
     *
     * @JMSAnnotation\Expose
     * @JMSAnnotation\Groups({"viewdata"})
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="update_at", type="string", length=255, nullable=true)
     *
     * @JMSAnnotation\Expose
     * @JMSAnnotation\Groups({"viewdata"})
     */
    private $updateAt;

    /**
     * @ORM\ManyToMany(targetEntity="Article", mappedBy="tags")
     *
     * @JMSAnnotation\Expose
     * @JMSAnnotation\Groups({"viewdata", "viewdata_reverse_list"})
     */
    private $articles;

    /**
     * Tag constructor.
     */
    public function __construct()
    {
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
     * Set name
     *
     * @param string $name
     *
     * @return Tag
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add article
     *
     * @param Article $article
     * @return Tag
     */
    public function addArticle(Article $article)
    {
        $this->articles->add($article);
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
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     *  Set create at
     *
     * @ORM\PrePersist
     */
    public function setCreateAt()
    {
        $this->createdAt = date('Y-m-d H:i:s');
    }

    /**
     * Set update at
     *
     * @ORM\PreUpdate
     */
    public function setUpdateAt()
    {
        $this->updateAt = date('Y-m-d H:i:s');
    }

    /**
     * get create at
     *
     * @return string
     */
    public function getCreateAt()
    {
        return $this->createdAt;
    }

    /**
     * get update at
     *
     * @return string
     */
    public function getUpdateAt()
    {
        return $this->updateAt;
    }

    /**
     * @inheritDoc
     */
    public function getLiteralType()
    {
        return 'Tag';
    }

    /**
     * @inheritDoc
     */
    public function getLiteralName()
    {
        return $this->getName();
    }

    /**
     * @inheritDoc
     */
    public function getIdentifier()
    {
        return $this->getId();
    }
}

