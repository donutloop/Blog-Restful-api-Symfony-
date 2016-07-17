<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace AppBundle\Repository;

use AppBundle\Entity\Article;
use BaseBundle\Library\DatabaseWorkflowRepositoryInterface;
use BaseBundle\Library\Repository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;

/**
 * TagRepository
 *
 * Class TagRepository
 * @package AppBundle\Repository
 */
class TagRepository extends Repository implements DatabaseWorkflowRepositoryInterface
{
    /**
     * @param $firstResult
     * @param int $maxResults
     * @return Query
     * @throws NoResultException
     */
    public function createFindAllQuery(int $firstResult = 0, int $maxResults = 10, $queryParam): Query
    {
        $query = $this->createQueryBuilder('t')
            ->select('t.name')
            ->setMaxResults($maxResults)
            ->getQuery();

        if ($firstResult) {
            $query->setFirstResult($firstResult);
        }
        
        return $query;
    }

    /**
     * @param $tag_id
     * @param Article $article
     *
     *  TODO write test cases
     */
    public function link(int $tag_id, Article $article)
    {
        $entity = $this->find($tag_id);

        if ($entity) {
            $em = $this->getEntityManager();
            $entity->addArticle($article);
            $em->persist($entity);
            $em->flush();
        }
    }

    /**
     * @param $name
     * @return mixed
     */
    public function findIdByName(string $name): int
    {
        return $this->createQueryBuilder('t')
            ->select('t.id')
            ->where('t.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
