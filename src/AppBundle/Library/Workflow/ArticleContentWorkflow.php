<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace AppBundle\Library\Workflow;


use AppBundle\Entity\ArticleContent;
use AppBundle\Library\Entries\ArticleContentEntry;
use BaseBundle\Library\DatabaseWorkflow;
use BaseBundle\Library\DatabaseWorkflowEntityInterface;

class ArticleContentWorkflow extends DatabaseWorkflow{

    /**
     * @inheritDoc
     */
    protected function checkType(DatabaseWorkflowEntityInterface $entity)
    {
        if(!($entity instanceof ArticleContent)){

        }
    }

    /**
     * @param $data
     * @param $mainEntity
     * @return ArticleContent
     */
    public function prepareEntity(ArticleContentEntry $data, $mainEntity){
        $entity = new ArticleContent();
        $entity->setContent($data->getContent());
        $entity->setContentType($data->getType());
        $entity->setArticle($mainEntity);
        return $entity;
    }
}