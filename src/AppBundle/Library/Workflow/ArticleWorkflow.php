<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace AppBundle\Library\Workflow;

use AppBundle\Entity\Article;
use AppBundle\Library\Entries\ArticleEntry;
use BaseBundle\Library\DatabaseWorkflow;
use BaseBundle\Library\DatabaseWorkflowEntityInterface;

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
}
