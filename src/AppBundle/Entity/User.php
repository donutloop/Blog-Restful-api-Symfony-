<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace AppBundle\Entity;

use BaseBundle\Library\DatabaseWorkflowEntityInterface;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMSAnnotation;

use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")#
 * 
 * @Hateoas\Relation("self", href = "expr('/user/get' ~ object.getId())")
 * @Hateoas\Relation("update", href = "expr('/user/update/' ~ object.getId())")
 * @Hateoas\Relation("delete", href = "expr('/user/delete/' ~ object.getId())")
 * 
 * @@JMSAnnotation\ExclusionPolicy("all")
 */
class User extends BaseUser implements DatabaseWorkflowEntityInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @JMSAnnotation\Expose
     * @JMSAnnotation\Groups({"viewdata", "viewdata_list"})
     */
    protected $id;

    /**
     * @var string
     * @Assert\NotNull()
     * @Assert\Length(min=3, max=255)
     * @Assert\NotBlank()
     *
     * @JMSAnnotation\Expose
     * @JMSAnnotation\Groups({"viewdata", "viewdata_list", "viewdata_reverse_list"})
     */
    protected $username;

    /**
     * @var string
     * @Assert\NotNull()
     * @Assert\Length(min=6, max=255)
     * @Assert\NotBlank()
     *
     * @JMSAnnotation\Expose
     * @JMSAnnotation\Groups({"viewdata", "viewdata_list", "viewdata_reverse_list"})
     */
    protected $email;

    /**
     * @var string
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    protected $password;

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
        return 'User';
    }

    /**
     * @inheritDoc
     */
    public function getLiteralName()
    {
        return $this->getUsername();
    }

    /**
     * @inheritDoc
     */
    public function getIdentifier()
    {
        return $this->getId();
    }
}