<?php

namespace AppBundle\Repository;
use AppBundle\Entity\Article;
use AppBundle\Entity\ArticleContent;
use BaseBundle\Library\Repository;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * ArticleContentRepository
 * 
 */
class ArticleContentRepository extends Repository
{

    /**
     * @param Article $mainEntity
     * @param \stdClass $data
     * @param ValidatorInterface $validator
     * @return ArticleContent
     * @throws ValidatorException
     */
    public function createArticleContent(Article $mainEntity, \stdClass $data, ValidatorInterface $validator): ArticleContent {

        $em = $this->getEntityManager();

        $entity = new ArticleContent();
        $entity->setContent($data->content);
        $entity->setContentType($data->contentType);
        $entity->setArticle($mainEntity);

        $errors = $validator->validate($entity);

        if (count($errors) > 0) {
            throw new ValidatorException((string) $errors);
        }

        $em->persist($entity);
        $em->persist($mainEntity);

        $em->flush();

        return $entity;
    }
}
