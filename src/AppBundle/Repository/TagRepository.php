<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Article;
use AppBundle\Entity\Tag;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

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
     */
    public function createTag(\stdClass $data) {
        $em = $this->getEntityManager();

        $entity = new Tag();
        $entity->setName($data->name);

        $em->persist($entity);
        $em->flush();
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
