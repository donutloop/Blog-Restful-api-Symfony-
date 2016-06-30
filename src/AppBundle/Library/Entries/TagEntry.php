<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace AppBundle\Library\Entries;

use BaseBundle\Library\DatabaseEntryInterface;
use JMS\Serializer\Annotation\Type;

class TagEntry implements DatabaseEntryInterface{

    /**
     * @Type("string")
     */
    private $name = null;

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

    public function getIdentifier()
    {
        return $this->name;
    }
}