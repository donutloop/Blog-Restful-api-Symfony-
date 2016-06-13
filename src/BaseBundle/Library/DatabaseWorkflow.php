<?php

namespace BaseBundle\Library;

use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class DatabaseWorkflow
 * @package BaseBundle\Library
 */
abstract class DatabaseWorkflow implements ContainerAwareInterface{

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var DatabaseWorkflowEntityInterface
     */
    protected $repository;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param string $name
     */
    public function setRepositoryName(string $name){
        $this->repository = $name;
    }

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface {

        if ($this->container === null){
            throw new \RuntimeException("Container isn't set");
        }

        return $this->container;
    }

    public function getRepository(): DatabaseWorkflowRepositoryInterface {

        if (empty($this->repository)) {
           throw new \RuntimeException('No repository configured for this workflow');
        }

        return $this->getEntityManager()->getRepository($this->repository);
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager(): EntityManager{

        if (!empty($this->em)) {
            return $this->em;
        }

       return $this->em = $this->getContainer()->get('doctrine')->getManager();
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface {

        if (!empty($this->logger)) {
            return $this->logger;
        }

        return $this->logger = $this->getContainer()->get('logger');
    }

    /**
     * @param DatabaseWorkflowEntityInterface $entity
     * @param array $extraData
     * @param callable|null $beforeCreateCallback
     * @param callable|null $afterCreateCallback
     * @return DatabaseWorkflowEntityInterface
     */
    public function create(DatabaseWorkflowEntityInterface $entity, array $extraData = [], callable $beforeCreateCallback = null, callable $afterCreateCallback = null) : DatabaseWorkflowEntityInterface{

        $processCallback = function(EntityManager $em, DatabaseWorkflowEntityInterface $entity){
            $em->persist($entity);
        };

        return $this->process($entity, $extraData, $beforeCreateCallback, $afterCreateCallback, $processCallback);
    }

    /**
     * @param array $entities
     * @param null $beforeCreateCallback
     * @param callable|null $afterCreateCallback
     */
    public function foreachCreate(array $entities, $beforeCreateCallback = null, callable $afterCreateCallback = null) {
        foreach($entities as $entity) {
              $this->create($entity, [], $beforeCreateCallback, $afterCreateCallback);
        }
    }

    /**
     * @param DatabaseWorkflowEntityInterface $entity
     * @param array $extraData
     * @param callable|null $beforeUpdateCallback
     * @param callable|null $afterUpdateCallback
     * @return DatabaseWorkflowEntityInterface
     */
    public function update(DatabaseWorkflowEntityInterface $entity, array $extraData = [], callable $beforeUpdateCallback = null, callable $afterUpdateCallback = null): DatabaseWorkflowEntityInterface{

        $processCallback = function(EntityManager $em, DatabaseWorkflowEntityInterface $entity){
            $em->persist($entity);
        };

        return $this->process($entity, $extraData, $beforeUpdateCallback, $afterUpdateCallback, $processCallback);
    }

    /**
     * @param DatabaseWorkflowEntityInterface $entity
     * @param array $extraData
     * @param callable|null $beforeDeleteCallback
     * @param callable|null $afterDeleteCallback
     * @return DatabaseWorkflowEntityInterface
     */
    public function delete(DatabaseWorkflowEntityInterface $entity, array $extraData = [], callable $beforeDeleteCallback = null, callable $afterDeleteCallback = null): DatabaseWorkflowEntityInterface{

        $processCallback = function(EntityManager $em, DatabaseWorkflowEntityInterface $entity){
            $em->remove($entity);
        };

        return $this->process($entity, $extraData, $beforeDeleteCallback, $afterDeleteCallback, $processCallback);
    }

    /**
     * @param DatabaseWorkflowEntityInterface $entity
     * @param array $extraData
     * @param callable|null $beforeCallback
     * @param callable|null $afterCallback
     * @param callable $processCallback
     * @return DatabaseWorkflowEntityInterface
     */
    private function process(DatabaseWorkflowEntityInterface $entity, array $extraData = [], callable $beforeCallback = null, callable $afterCallback = null, callable $processCallback) {
        $this->checkType($entity);

        $entity = $this->validate($entity);

        if (is_callable($beforeCallback)) {
            $entity = $beforeCallback($entity, $extraData);
        }

        $em = $this->getEntityManager();
        $processCallback($em, $entity);
        $em->flush();

        if (is_callable($afterCallback)) {
            $entity = $afterCallback($entity, $extraData);
        }

        $this->getLogger()->info(vsprintf('Process %s (%s) with id : %d successfully', [$entity->getLiteralType(), $entity->getLiteralName(), $entity->getIdentifier()]));

        return $entity;
    }

    /**
     * @param DatabaseWorkflowEntityInterface $entity
     * @return DatabaseWorkflowEntityInterface
     */
    public function validate(DatabaseWorkflowEntityInterface $entity) : DatabaseWorkflowEntityInterface {

        /**
         * @var ValidatorInterface $validator
         */
        $validator = $this->getContainer()->get('validator');

        $errors = $validator->validate($entity);

        if (count($errors) > 0) {
            throw new ValidatorException((string) $errors);
        }

        return $entity;
    }

    /**
     * @param DatabaseWorkflowEntityInterface $entity
     * @return mixed
     */
    abstract protected function checkType(DatabaseWorkflowEntityInterface $entity);
};