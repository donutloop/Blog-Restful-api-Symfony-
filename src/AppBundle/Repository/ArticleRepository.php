<?php

namespace AppBundle\Repository;
use AppBundle\Entity\Article;
use AppBundle\Entity\User;
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
class ArticleRepository extends EntityRepository
{
    /**
     * @param $name
     * @param $maxResults
     * @param $firstResult
     * @return array
     * @throws NoResultException
     */
    public function findAllArticlesByTag($name, $maxResults, $firstResult) {

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

        $result = $query->getArrayResult();

        if (!$result) {
            throw new NoResultException('no result');
        }

        return $result;
    }

    /**
     * @param \stdClass $data
     * @param User $user
     * @param ValidatorInterface $validator
     * @return Article
     * @throws \Exception
     */
    public function createArticle(\stdClass $data, User $user , ValidatorInterface $validator) {

        $entity = new Article();

        $entity->setTitle($data->title);

        $entity->setUser($user);

        $errors = $validator->validate($entity);

        if (count($errors) > 0) {

            $errors = (string) $errors;

            throw new ValidatorException($errors);
        }

        $em = $this->getEntityManager();
        $em->persist($entity);
        $em->flush();

        return $entity;
    }
}
