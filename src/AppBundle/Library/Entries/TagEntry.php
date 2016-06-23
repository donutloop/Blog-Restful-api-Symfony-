<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace AppBundle\Library\Entries;

use JMS\Serializer\Annotation\Type;

class TagEntry{

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

}