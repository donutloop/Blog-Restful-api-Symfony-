<?php

namespace AppBundle\Entity;

use BaseBundle\Library\DatabaseWorkflowEntityInterface;
use FOS\OAuthServerBundle\Entity\RefreshToken as BaseRefreshToken;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table("oauth2_refresh_tokens")
 * @ORM\Entity
 */
class RefreshToken extends BaseRefreshToken implements DatabaseWorkflowEntityInterface
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
    public function getLiteralType(): string
    {
        return 'RefreshToken';
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
    public function getIdentifier(): int
    {
        return $this->getId();
    }
}