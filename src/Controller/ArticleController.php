<?php

namespace App\Controller;

use App\Entity\Article;
use App\Serializer\Serializer;
use App\Service\ArticleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Alexander Tsukanov <https://alexdevid.com>
 */
class ArticleController extends AbstractController
{
    /**
     * @var ArticleService
     */
    private $articleService;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @param ArticleService $articleService
     * @param Serializer $serializer
     */
    public function __construct(ArticleService $articleService, Serializer $serializer)
    {
        $this->articleService = $articleService;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/articles", name="article.list", methods={"GET"})
     *
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request): Response
    {
        $paginationResponse = $this->articleService->getArticles($request->query->all());

        return new Response($this->serializer->serialize($paginationResponse));
    }

    /**
     * @Route("/article/{id}", name="article.get", methods={"GET"})
     *
     * @param int $id
     * @return Response
     */
    public function getAction(int $id): Response
    {
        $article = $this->articleService->getArticle($id);

        return new Response($this->serializer->serialize($article));
    }

    /**
     * @Route("/article", name="article.create", methods={"POST"})
     *
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request): Response
    {
        $article = $this->serializer->deserialize($request->getContent(), Article::class);
        $this->articleService->addArticle($article);

        return new Response($this->serializer->serialize($article));
    }

    /**
     * @Route("/article/{id}", name="article.update", methods={"PUT"})
     *
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function updateAction(int $id, Request $request): Response
    {
        $article = $this->articleService->updateArticle($id, $this->serializer->deserialize($request->getContent(), Article::class));

        return new Response($this->serializer->serialize($article));
    }

    /**
     * @Route("/article/{id}", name="article.delete", methods={"DELETE"})
     *
     * @param int $id
     * @return Response
     */
    public function deleteAction(int $id): Response
    {
        $this->articleService->deleteArticle($id);

        return new Response(null);
    }
}