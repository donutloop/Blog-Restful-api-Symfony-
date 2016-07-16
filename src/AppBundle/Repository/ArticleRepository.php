<?php
/**
 * @author Marcel Edmund Franke <info@marcel-edmund-franke.de>
 */

namespace AppBundle\Repository;

use BaseBundle\Library\DatabaseWorkflowRepositoryInterface;
use BaseBundle\Library\Repository;
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
    public function createBaseFindAllByQuery($queryParam): QueryBuilder {
        
        $queryBuilder = $this->createQueryBuilder('a')
                     ->join('AppBundle\Entity\ArticleContent', 'ac', 'WITH', 'a.id = ac.article_id')
                     ->join('AppBundle\Entity\User', 'u', 'WITH', 'a.user_id = u.id')
                     ->join('AppBundle\Entity\Tag', 't');

        if (isset($queryParam['search'])){
            $queryBuilder->where('t.name LIKE :name')
                  ->setParameter('name', $queryParam['search']. '%');
        }

        return $queryBuilder;
    }

    /**
     * @return QueryBuilder
     */
    public function createBaseFindAllQuery(): QueryBuilder {

        $queryBuilder = $this->createQueryBuilder('a')
            ->join('AppBundle\Entity\ArticleContent', 'ac', 'WITH', 'a.id = ac.article_id')
            ->join('AppBundle\Entity\User', 'u', 'WITH', 'a.user_id = u.id')
            ->leftJoin('AppBundle\Entity\Tag', 't', 'WITH', 'a.id = t.id');

        return $queryBuilder;
    }
}
