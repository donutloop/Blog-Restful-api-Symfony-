<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Article;
use AppBundle\Entity\Tag;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * TagRepository
 *
 * Class TagRepository
 * @package AppBundle\Repository
 */
class TagRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function findAllNames() {
        
        $query = $this->createQueryBuilder('t')
            ->select('DISTINCT t.name')
            ->getQuery();

        try{
            $result = $query->getArrayResult();
        }catch(NoResultException $e){
            return array();
        };

        return $result;
    }

    /**
     * @param $tag_id
     * @param Article $article
     *
     *  TODO write test cases
     */
    public function link($tag_id, Article $article) {
        $entity = $this->find($tag_id);

        if ($entity) {
            $em = $this->getEntityManager();
            $entity->addArticle($article);
            $em->persist($entity);
            $em->flush();
        }
    }

    /**
     * @param \stdClass $data
     * @param ValidatorInterface $validator
     * @return Tag
     */
    public function createTag(\stdClass $data, ValidatorInterface $validator) {
        $em = $this->getEntityManager();

        $entity = new Tag();
        $entity->setName($data->name);

        $errors = $validator->validate($entity);

        if (count($errors) > 0) {
            throw new ValidatorException((string) $errors);
        }

        $em->persist($entity);
        $em->flush();

        return $entity;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function findIdByName($name) {
        return $this->createQueryBuilder('t')
            ->select('t.id')
            ->where('t.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
