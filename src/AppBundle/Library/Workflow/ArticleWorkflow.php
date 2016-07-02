<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace AppBundle\Library\Workflow;

use AppBundle\Entity\Article;
use AppBundle\Library\Entries\ArticleEntry;
use BaseBundle\Library\DatabaseWorkflow;
use BaseBundle\Library\DatabaseWorkflowEntityInterface;
use Doctrine\ORM\NoResultException;

class ArticleWorkflow extends DatabaseWorkflow{

    /**
     * @inheritDoc
     */
    protected function checkType(DatabaseWorkflowEntityInterface $entity)
    {
        if(!($entity instanceof Article)){
            
        }
    }
    
    /**
     * @param $data
     * @param $user
     * @return Article
     */
    public function prepareEntity(ArticleEntry $data, $user){
        $entity = new Article();
        $entity->setTitle($data->getTitle());
        $entity->setUser($user);
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
}
