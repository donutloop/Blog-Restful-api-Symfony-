<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace AppBundle\Library\Workflow;

use AppBundle\Entity\Article;
use Donutloop\RestfulApiWorkflowBundle\Library\DatabaseEntryInterface;
use Donutloop\RestfulApiWorkflowBundle\Library\DatabaseWorkflow;
use Donutloop\RestfulApiWorkflowBundle\Library\DatabaseWorkflowAwareInterface;
use Donutloop\RestfulApiWorkflowBundle\Library\DatabaseWorkflowEntityInterface;
use Donutloop\RestfulApiWorkflowBundle\Library\TraitWorkflowUtility;
use Doctrine\ORM\NoResultException;

/**
 * Class ArticleWorkflow
 * @package AppBundle\Library\Workflow
 */
class ArticleWorkflow extends DatabaseWorkflow implements DatabaseWorkflowAwareInterface
{
    use TraitWorkflowUtility;
    
    /**
     * @inheritDoc
     */
    protected function checkType(DatabaseWorkflowEntityInterface $entity)
    {
        if (!($entity instanceof Article)){
            
        }
    }
    
    /**
     * @param DatabaseEntryInterface $entry
     * @return Article
     */
    public function prepareEntity(DatabaseEntryInterface $entry)
    {
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
        $qb = $this->getRepository()->createBaseFindAllQuery();

        return $this->generatePaginateCollection($qb, 'get_articles', [], [], $offset, $limit);
    }

    /**
     * @return mixed
     * @throws NoResultException
     */
    public function findAllBy($queryParam, $offset, $limit)
    {
        $qb = $this->getRepository()->createBaseFindAllByQuery($queryParam);

        return $this->generatePaginateCollection($qb, 'get_articles', [], [], $offset, $limit);
    }

    public function prepareUpdateEntity(DatabaseEntryInterface $entry)
    {
        // TODO: Implement prepareUpdateEntity() method.
    }
}
