<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace Tests\BaseBundle\Library;

use BaseBundle\Library\DatabaseWorkflowEntityInterface;

/**
 * Class DatabaseWorkflowTestEntity2
 * @package Tests\BaseBundle\Library
 */
class DatabaseWorkflowTestEntity2 implements DatabaseWorkflowEntityInterface{
    
    /**
     * @inheritDoc
     */
    public function getLiteralType()
    {
        return 'DatabaseWorkflowTestEntity';
    }

    /**
     * @inheritDoc
     */
    public function getLiteralName()
    {
        return 'DatabaseWorkflowTestEntity';
    }

    /**
     * @inheritDoc
     */
    public function getIdentifier()
    {
        return uniqid();
    }
}