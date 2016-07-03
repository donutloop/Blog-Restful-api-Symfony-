<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace BaseBundle\Library;

/**
 * Interface DatabaseWorkflowAwareInterface
 * @package BaseBundle\Library
 */
interface DatabaseWorkflowAwareInterface
{
    public function prepareEntity(DatabaseEntryInterface $entry);

    public function prepareUpdateEntity(DatabaseEntryInterface $entry);

    public function findAll($offset, $limit, $queryParam);

    public function update(DatabaseWorkflowEntityInterface $entity);

    public function create(DatabaseWorkflowEntityInterface $entity);

    public function delete(DatabaseWorkflowEntityInterface $entity);

    public function get(int $id);
}