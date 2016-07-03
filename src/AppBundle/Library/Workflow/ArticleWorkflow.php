<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace AppBundle\Library\Workflow;

use AppBundle\Entity\Article;
use BaseBundle\Library\DatabaseEntryInterface;
use BaseBundle\Library\DatabaseWorkflow;
use BaseBundle\Library\DatabaseWorkflowAwareInterface;
use BaseBundle\Library\DatabaseWorkflowEntityInterface;
use Doctrine\ORM\NoResultException;

class ArticleWorkflow extends DatabaseWorkflow implements DatabaseWorkflowAwareInterface{

    /**
     * @inheritDoc
     */
    protected function checkType(DatabaseWorkflowEntityInterface $entity)
    {
        if(!($entity instanceof Article)){
            
        }
    }
    
    /**
     * @param DatabaseEntryInterface $entry
     * @return Article
     */
    public function prepareEntity(DatabaseEntryInterface $entry){
        $entity = new Article();
        $entity->setTitle($entry->getTitle());
        $entity->setUser($entry->getUser());
        return $entity;
    }

    /**
     * @return mixed
     * @throws NoResultException
     */
    public function findAll($offset, $limit, $queryParam = null)
    {
        $query = $this->getRepository()->createFindAllQuery($offset, $limit, $queryParam);
        
        $result = $query->getArrayResult();
        if (!$result) {
            throw new NoResultException('no result');
        }

        return $result;
    }

    public function prepareUpdateEntity(DatabaseEntryInterface $entry)
    {
        // TODO: Implement prepareUpdateEntity() method.
    }
}
