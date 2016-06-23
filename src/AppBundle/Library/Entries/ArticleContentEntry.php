<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace AppBundle\Library\Entries;

use JMS\Serializer\Annotation\Type;

class ArticleContentEntry{

    /**
     * @Type("string")
     */
    private $content = null;

    /**
     * @Type("string")
     */
    private $type = null;

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
}