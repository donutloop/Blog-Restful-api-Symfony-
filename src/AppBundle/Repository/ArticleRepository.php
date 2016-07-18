<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace AppBundle\Repository;

use Donutloop\RestfulApiWorkflowBundle\Library\DatabaseWorkflowRepositoryInterface;
use Donutloop\RestfulApiWorkflowBundle\Library\Repository;
use Doctrine\ORM\QueryBuilder;

/**
 * ArticleRepository
 *
 * Class ArticleRepository
 * @package AppBundle\Repository
 */
class ArticleRepository extends Repository implements DatabaseWorkflowRepositoryInterface
{
    /**
     * @param $queryParam
     * @return QueryBuilder
     */
    public function createBaseFindAllByQuery($queryParam): QueryBuilder 
    {
        $queryBuilder = $this->createQueryBuilder('a')
                     ->join('AppBundle\Entity\ArticleContent', 'ac', 'WITH', 'a.id = ac.article_id')
                     ->join('AppBundle\Entity\User', 'u', 'WITH', 'a.user_id = u.id')
                     ->join('AppBundle\Entity\Tag', 't');

        if (array_key_exists('search', $queryParam)){
            $queryBuilder->where('t.name LIKE :name')
                  ->setParameter('name', $queryParam['search']. '%');
        }

        return $queryBuilder;
    }

    /**
     * @return QueryBuilder
     */
    public function createBaseFindAllQuery(): QueryBuilder 
    {
        $queryBuilder = $this->createQueryBuilder('a')
            ->join('AppBundle\Entity\ArticleContent', 'ac', 'WITH', 'a.id = ac.article_id')
            ->join('AppBundle\Entity\User', 'u', 'WITH', 'a.user_id = u.id')
            ->leftJoin('AppBundle\Entity\Tag', 't', 'WITH', 'a.id = t.id');

        return $queryBuilder;
    }
}
