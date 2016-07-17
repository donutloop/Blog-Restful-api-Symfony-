<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace AppBundle\Library\Entries;

use BaseBundle\Library\DatabaseEntryInterface;
use JMS\Serializer\Annotation\Type;

/**
 * Class ArticleContentEntry
 * @package AppBundle\Library\Entries
 */
class ArticleContentEntry implements DatabaseEntryInterface{

    /**
     * @Type("integer")
     */
    private $id;

    /**
     * @Type("string")
     */
    private $content = null;

    /**
     * @Type("string")
     */
    private $type = null;

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
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return ArticleContentEntry
     */
    public function setContent($content): ArticleContentEntry
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return ArticleContentEntry
     */
    public function setType($type): ArticleContentEntry
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return null
     */
    public function getIdentifier()
    {
        return null;
    }
}