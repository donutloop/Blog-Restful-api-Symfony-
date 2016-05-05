<?php

namespace AppBundle\Repository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

/**
 * ArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 *
 * Class ArticleRepository
 * @package AppBundle\Repository
 */
class ArticleRepository extends EntityRepository
{
    /**
     * @param $name
     * @param $maxResults
     * @param $firstResult
     * @return array
     */
    public function findAllArticlesByTag($name, $maxResults, $firstResult) {

        $query = $this->createQueryBuilder('a')
                     ->join('AppBundle\Entity\Tag', 't', 'WITH', 't.name LIKE :name')
                     ->setMaxResults($maxResults)
                     ->setParameter('name', $name)
                     ->getQuery();

        if ($firstResult) {
            $query->setFirstResult($firstResult);
        }

        try{
            $result = $query->getArrayResult();
        }catch (NoResultException $e){
            return array();
        }

        return $result;
    }
}
