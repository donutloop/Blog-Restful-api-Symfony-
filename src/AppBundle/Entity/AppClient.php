<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace AppBundle\Entity;

use FOS\OAuthServerBundle\Entity\Client as BaseClient;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMSAnnotation;

/**
 * @ORM\Table("oauth2_clients")
 * @ORM\Entity
 *
 * @JMSAnnotation\ExclusionPolicy("all")
 */
class AppClient extends BaseClient
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
    }
}