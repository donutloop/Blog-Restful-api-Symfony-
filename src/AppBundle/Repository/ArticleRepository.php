<?php

namespace AppBundle\Repository;
use AppBundle\Entity\Article;
use AppBundle\Entity\User;
use BaseBundle\Library\DatabaseWorkflowRepositoryInterface;
use BaseBundle\Library\Repository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * ArticleRepository
 *
 * Class ArticleRepository
 * @package AppBundle\Repository
 */
class ArticleRepository extends Repository implements DatabaseWorkflowRepositoryInterface
{
    /**
     * @param $name
     * @param $maxResults
     * @param $firstResult
     * @return array
     * @throws NoResultException
     */
    public function findAllArticlesByTag(string $name, int $maxResults, int $firstResult): array {

        $query = $this->createQueryBuilder('a')
                     ->select('a.id, a.title, a.createdAt, t.name as tags, ac.content, ac.contentType, u.username')
                     ->join('AppBundle\Entity\ArticleContent', 'ac', 'WITH', 'a.id = ac.article_id')
                     ->join('AppBundle\Entity\User', 'u', 'WITH', 'a.user_id = u.id')
                     ->join('AppBundle\Entity\Tag', 't', 'WITH', 't.name LIKE :name')
                     ->setMaxResults($maxResults)
                     ->setParameter('name', $name . '%')
                     ->getQuery();

        if ($firstResult) {
            $query->setFirstResult($firstResult);
        }

        $result = $query->getArrayResult();

        if (!$result) {
            throw new NoResultException('no result');
        }

        return $result;
    }

    /**
     * @param $maxResults
     * @param $firstResult
     * @return array
     * @throws NoResultException
     */
    public function findAllArticles(int $maxResults, int $firstResult): array {
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

        $result = $query->getArrayResult();

        if (!$result) {
            throw new NoResultException('no result');
        }

        return $result;
    }
}
