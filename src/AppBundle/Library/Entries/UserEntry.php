<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace AppBundle\Library\Entries;

use BaseBundle\Library\DatabaseEntryInterface;
use JMS\Serializer\Annotation\Type;

class UserEntry implements DatabaseEntryInterface{

    /**
     * @Type("integer")
     */
    private $id;
    
    /**
     * @Type("string")
     */
    private $username = null;

    /**
     * @Type("string")
     */
    private $password = null;

    /**
     * @Type("string")
     */
    private $email = null;

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
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return null
     */
    public function getIdentifier()
    {
        return $this->username;
    }
}