<?php

namespace AppBundle\Repository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

/**
 * ArticleRepository
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
                     ->select('a.id, a.title, a.createdAt, t.name as tags, ac.content, ac.contentType, u.username')
                     ->join('AppBundle\Entity\ArticleContent', 'ac', 'WITH', 'a.id = ac.article_id')
                     ->join('AppBundle\Entity\User', 'u', 'WITH', 'a.user_id = u.id')
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
            return array($e->getMessage());
        }

        return $result;
    }

    /**
     * @param $maxResults
     * @param $firstResult
     * @return array
     */
    public function findAllArticles($maxResults, $firstResult) {

        $query = $this->createQueryBuilder('a')
            ->select('a.id, a.title, a.createdAt, t.name as tags, ac.content, ac.contentType, u.username')
            ->join('AppBundle\Entity\ArticleContent', 'ac', 'WITH', 'a.id = ac.article_id')
            ->join('AppBundle\Entity\User', 'u', 'WITH', 'a.user_id = u.id')
            ->leftJoin('AppBundle\Entity\Tag', 't', 'WITH', 'a.id = t.id')
            ->setMaxResults($maxResults)
            ->getQuery();

        if ($firstResult) {
            $query->setFirstResult($firstResult);
        }

        try{
            $result = $query->getArrayResult();
        }catch (NoResultException $e){
            return array($e->getMessage());
        }

        return $result;
    }
}
