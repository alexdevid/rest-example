<?php

namespace App\Service;

use App\Entity\Article;
use App\Pagination\Response;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @author Alexander Tsukanov <https://alexdevid.com>
 */
class ArticleService
{
    /**
     * @var ArticleRepository
     */
    private $repository;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param ArticleRepository $repository
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ArticleRepository $repository, ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->entityManager = $entityManager;
    }

    /**
     * @param int $id
     * @return Article
     * @throws NotFoundHttpException
     */
    public function getArticle(int $id): Article
    {
        $article = $this->repository->find($id);
        if (!$article) {
            throw new NotFoundHttpException(sprintf("Article with id '%d' was not found", $id));
        }

        return $article;
    }

    /**
     * @param Article|object $article
     * @return Article
     */
    public function addArticle(Article $article)
    {
        $errors = $this->validator->validate($article);
        if (count($errors) > 0) {
            throw new BadRequestHttpException((string)$errors);
        }

        $this->entityManager->persist($article);
        $this->entityManager->flush();

        return $article;
    }

    /**
     * @param int $id
     * @param Article|object $newArticle
     * @return Article
     */
    public function updateArticle(int $id, Article $newArticle)
    {
        $article = $this->getArticle($id);
        if ($newArticle->getBody()) {
            $article->setBody($newArticle->getBody());
        }

        $article
            ->setTitle($newArticle->getTitle())
            ->setUpdatedAt(new \DateTime());

        $errors = $this->validator->validate($article);
        if (count($errors) > 0) {
            throw new BadRequestHttpException((string)$errors);
        }

        $this->entityManager->persist($article);
        $this->entityManager->flush();

        return $article;
    }

    /**
     * @param int $id
     */
    public function deleteArticle(int $id)
    {
        $article = $this->repository->find($id);
        $this->entityManager->remove($article);
        $this->entityManager->flush();
    }

    /**
     * @param array $requestData
     * @return Response
     */
    public function getArticles(array $requestData)
    {
        $response = Response::createFromRequestData($requestData);

        $query = $this->repository->createQueryBuilder('a')
            ->orderBy('a.' . $response->order_by, $response->order)
            ->setFirstResult(($response->page - 1) * $response->limit)
            ->setMaxResults($response->limit)
            ->getQuery();

        $paginator = new Paginator($query);
        $response->total = $paginator->count();
        $response->collection = iterator_to_array($paginator->getIterator());

        return $response;
    }
}