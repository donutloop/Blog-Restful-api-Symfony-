<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace BaseBundle\Library;

/**
 * Interface DatabaseWorkflowAwareInterface
 * @package BaseBundle\Library
 */
interface DatabaseWorkflowAwareInterface{
    public function prepareEntity(DatabaseEntryInterface $entry);
}