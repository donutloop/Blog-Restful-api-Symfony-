<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace AppBundle\Library\Workflow;


use AppBundle\Entity\Article;
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
    public function prepareEntity($data, $user){
        $entity = new Article();
        $entity->setTitle($data->title);
        $entity->setUser($user);
        return $entity;
    }
}
