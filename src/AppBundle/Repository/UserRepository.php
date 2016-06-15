<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use BaseBundle\Library\Repository;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class UserRepository
 *
 * @package AppBundle\Repository
 */
class UserRepository extends Repository
{

    /**
     * @param \stdClass $data
     * @param ValidatorInterface $validator
     * @return User
     */
    public function createUser(\stdClass $data, ValidatorInterface $validator): User {
        $entity = new User();
        $entity->setUsername($data->username);
        $entity->setEmail($data->email);
        $entity->setPassword($data->password);

        $errors = $validator->validate($entity);

        if (count($errors) > 0) {
            throw new ValidatorException((string)$errors);
        }

        $em = $this->getEntityManager();

        $em->persist($entity);
        $em->flush();

        return $entity;
    }

}