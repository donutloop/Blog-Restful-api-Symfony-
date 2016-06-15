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
     * @param array $entities
     */
    final public function __construct(array $entities = []) {
        $this->container = new \ArrayObject();
        $this->setEntities($entities);
    }

    /**
     * @param DatabaseWorkflowEntityInterface $entity
     * @return DatabaseWorkflowCollection
     */
    private function checkType(DatabaseWorkflowEntityInterface $entity): DatabaseWorkflowCollection{
        if($this->type != get_class($entity)){
            throw new \InvalidArgumentException("Wrong type");
        }
        return $this;
    }

    /**
     * @param DatabaseWorkflowEntityInterface $entity
     * @return DatabaseWorkflowCollection
     */
    private function setTypeIfNull(DatabaseWorkflowEntityInterface $entity): DatabaseWorkflowCollection{
        if($this->type === null){
            $this->type = get_class($entity);
        }
        return $this;
    }

    /**
     * @param DatabaseWorkflowEntityInterface $entity
     * @return DatabaseWorkflowCollection
     */
    public function addEntity(DatabaseWorkflowEntityInterface $entity): DatabaseWorkflowCollection {

        $this->setTypeIfNull($entity)->checkType($entity);

        if(!$this->container->offsetExists($entity->getIdentifier())) {
            $this->container->offsetSet($entity->getIdentifier(), $entity);
        }
        return $this;
    }

    /**
     * @param array $entities
     * @return DatabaseWorkflowCollection
     */
    public function setEntities(array $entities): DatabaseWorkflowCollection {
        if (!empty($entities) && is_array($entities)) {

            foreach ($entities as $entity) {

                $this->setTypeIfNull($entity)->checkType($entity);

                if($entity instanceof DatabaseWorkflowEntityInterface) {
                    if (!$this->container->offsetExists($entity->getIdentifier())){
                        $this->container->offsetSet($entity->getIdentifier(), $entity->getIdentifier());
                    }else{
                        throw new \InvalidArgumentException("Entities duplicate found");
                    }
                }
                else{
                    throw new \InvalidArgumentException(sprintf("Expected DatabaseWorkflowEntityInterface got %s", get_class($entity)));
                }
            }
        }
        return $this;
    }

    /**
     * @param mixed $id
     * @return DatabaseWorkflowCollection
     */
    public function removeEntity($id): DatabaseWorkflowCollection {
        if($this->container->offsetExists($id)) {
            $this->container->offsetUnset($id);
        }
        return $this;
    }

    /**
     * @param mixed $id
     * @return mixed
     */
    public function getEntity($id) {
        if($this->container->offsetExists($id)) {
            return $this->container->offsetGet($id);
        }
    }

    /**
     * @return \ArrayObject
     */
    public function getEntities(): \ArrayObject{
        return $this->container;
    }

    /**
     * @return int
     */
    public function count(): int{
        return count($this->container);
    }
}