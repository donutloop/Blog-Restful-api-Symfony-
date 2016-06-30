<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace AppBundle\Library\Workflow;

use AppBundle\Entity\Tag;
use AppBundle\Library\Entries\TagEntry;
use BaseBundle\Library\DatabaseEntryInterface;
use BaseBundle\Library\DatabaseWorkflow;
use BaseBundle\Library\DatabaseWorkflowAwareInterface;
use BaseBundle\Library\DatabaseWorkflowEntityInterface;
use Doctrine\ORM\EntityNotFoundException;

class TagWorkflow extends DatabaseWorkflow implements DatabaseWorkflowAwareInterface{

    /**
     * @inheritDoc
     */
    protected function checkType(DatabaseWorkflowEntityInterface $entity)
    {
        if(!($entity instanceof Tag)){
            
        }
    }

    /**
     * @param DatabaseEntryInterface $tagEntry
     * @return Tag
     */
    public function prepareEntity(DatabaseEntryInterface $tagEntry): Tag{
        $entity = new Tag();
        $entity->setName($tagEntry->getName());
        return $entity;
    }

    /**
     * @param string $name
     * @return DatabaseWorkflowEntityInterface
     * @throws EntityNotFoundException
     */
    public function getBy(string $name): DatabaseWorkflowEntityInterface {

        $entity = $this->getRepository()->findOneBy(array('name' => $name));

        if (!$entity) {
            throw new EntityNotFoundException(sprintf('Dataset not found (id: %d)', $name));
        }

        return $entity;
    }
}
