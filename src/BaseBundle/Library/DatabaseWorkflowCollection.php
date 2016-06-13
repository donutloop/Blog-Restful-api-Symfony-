<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 13.06.16
 * Time: 19:21
 */

namespace BaseBundle\Library;


class DatabaseWorkflowCollection
{
    /**
     * @var \ArrayObject
     */
    private $container;

    private $type;

    /**
     * DatabaseWorkflowCollection constructor.
     */
    final private function __construct(){
        $this->container = new \ArrayObject();
    }

    /**
     * @param $entity
     */
    private function checkType($entity){
        if($this->type != gettype($entity)){
            throw new \InvalidArgumentException("Wrong type");
        }
    }

    /**
     * @param DatabaseWorkflowEntityInterface $entity
     * @return DatabaseWorkflowCollection
     */
    public function addEntity(DatabaseWorkflowEntityInterface $entity): DatabaseWorkflowCollection {

        if($this->type === null){
            $this->type = gettype($entity);
        }

        $this->checkType($entity);

        if(!$this->container->offsetExists($entity->getIdentifier())) {
            $this->container->offsetSet($entity->getIdentifier(), $entity);
        }
        return $this;
    }

    /**
     * @param DatabaseWorkflowEntityInterface $entity
     * @return DatabaseWorkflowCollection
     */
    public function removeEntity(DatabaseWorkflowEntityInterface $entity): DatabaseWorkflowCollection {
        if(!$this->container->offsetExists($entity->getIdentifier())) {
            $this->container->offsetUnset($entity->getIdentifier());
        }
        return $this;
    }

    /**
     * @param DatabaseWorkflowEntityInterface $entity
     * @return mixed
     */
    public function getEntity(DatabaseWorkflowEntityInterface $entity) {
        if(!$this->container->offsetExists($entity->getIdentifier())) {
            return $this->container->offsetGet($entity->getIdentifier());
        }
    }
}