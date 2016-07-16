<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace AppBundle\Entity;

use FOS\OAuthServerBundle\Entity\AuthCode as BaseAuthCode;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMSAnnotation;

/**
 * @ORM\Table("oauth2_auth_codes")
 * @ORM\Entity
 *
 * @JMSAnnotation\ExclusionPolicy("all")
 */
class AuthCode extends BaseAuthCode
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppClient")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $client;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     */
    protected $user;
    
    /**
     * @inheritDoc
     */
    public function getLiteralType()
    {
        return 'AuthCode';
    }

    /**
     * @inheritDoc
     */
    public function getLiteralName()
    {
        return $this->getId();
    }

    /**
     * @inheritDoc
     */
    public function getIdentifier()
    {
        return $this->getId();
    }
}