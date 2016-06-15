<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace Tests\BaseBundle\Library;

use BaseBundle\Library\DatabaseWorkflowEntityInterface;

class DatabaseWorkflowTestEntity implements DatabaseWorkflowEntityInterface{

    /**
     * @var
     */
    private $identifier;

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