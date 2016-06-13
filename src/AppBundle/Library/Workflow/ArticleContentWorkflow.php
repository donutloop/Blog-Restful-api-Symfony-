<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace AppBundle\Library\Workflow;


use AppBundle\Entity\ArticleContent;
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
    public function prepareEntity($data, $mainEntity){
        $entity = new ArticleContent();
        $entity->setContent($data->content);
        $entity->setContentType($data->contentType);
        $entity->setArticle($mainEntity);
        return $entity;
    }
}