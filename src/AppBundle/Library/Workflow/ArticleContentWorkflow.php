<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace AppBundle\Library\Workflow;

use AppBundle\Entity\ArticleContent;
use Donutloop\RestfulApiWorkflowBundle\Library\DatabaseEntryInterface;
use Donutloop\RestfulApiWorkflowBundle\Library\DatabaseWorkflow;
use  Donutloop\RestfulApiWorkflowBundle\Library\DatabaseWorkflowEntityInterface;

/**
 * Class ArticleContentWorkflow
 * @package AppBundle\Library\Workflow
 */
class ArticleContentWorkflow extends DatabaseWorkflow
{
    /**
     * @inheritDoc
     */
    protected function checkType(DatabaseWorkflowEntityInterface $entity)
    {
        if (!($entity instanceof ArticleContent)){

        }
    }

    /**
     * @param $data
     * @param $mainEntity
     * @return ArticleContent
     */
    public function prepareEntity(DatabaseEntryInterface $data, $mainEntity)
    {
        $entity = new ArticleContent();
        $entity->setContent($data->getContent());
        $entity->setContentType($data->getType());
        $entity->setArticle($mainEntity);

        return $entity;
    }

    /**
     * @inheritDoc
     */
    public function findAll($offset, $limit, $queryParam = null)
    {
       return false;
    }
}