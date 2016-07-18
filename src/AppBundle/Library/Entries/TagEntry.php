<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace AppBundle\Library\Entries;

use Donutloop\RestfulApiWorkflowBundle\Library\DatabaseEntryInterface;
use JMS\Serializer\Annotation\Type;

/**
 * Class TagEntry
 * @package AppBundle\Library\Entries
 */
class TagEntry implements DatabaseEntryInterface
{
    /**
     * @Type("integer")
     */
    private $id;

    /**
     * @Type("string")
     */
    private $name = null;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return TagEntry
     */
    public function setName($name) : TagEntry
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return null
     */
    public function getIdentifier()
    {
        return $this->name;
    }
}