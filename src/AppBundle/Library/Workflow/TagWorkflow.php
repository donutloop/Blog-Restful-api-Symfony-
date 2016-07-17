<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace AppBundle\Library\Workflow;

use AppBundle\Entity\Tag;
use BaseBundle\Library\DatabaseEntryInterface;
use BaseBundle\Library\DatabaseWorkflow;
use BaseBundle\Library\DatabaseWorkflowAwareInterface;
use BaseBundle\Library\DatabaseWorkflowEntityInterface;
use Doctrine\ORM\NoResultException;

/**
 * Class TagWorkflow
 * @package AppBundle\Library\Workflow
 */
class TagWorkflow extends DatabaseWorkflow implements DatabaseWorkflowAwareInterface
{
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
    public function prepareEntity(DatabaseEntryInterface $tagEntry): Tag
    {
        $entity = new Tag();
        $entity->setName($tagEntry->getName());

        return $entity;
    }
    
    /**
     * @inheritDoc
     */
    public function findAll($offset, $limit, $queryParam = null)
    {
        $query = $this->getRepository()->createFindAllQuery($offset, $limit, $queryParam);

        $result = $query->getArrayResult();

        if (!$result) {
            throw new NoResultException("no result");
        }

        return $result;
    }

    public function prepareUpdateEntity(DatabaseEntryInterface $entry)
    {
        $entity = $this->get($entry->getId());
        $entity->setName($entry->getName());

        return $entity;
    }
}
