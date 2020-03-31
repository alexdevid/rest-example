<?php
/**
 * This file is part of ONP.
 *
 * Copyright (c) Opensoft (http://opensoftdev.com)
 *
 * The unauthorized use of this code outside the boundaries of
 * Opensoft is prohibited.
 */

namespace App\Tests\Service;

use App\Entity\Article;
use App\Pagination\Response;
use App\Repository\ArticleRepository;
use App\Service\ArticleService;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @author Alexander Tsukanov <https://alexdevid.com>
 */
class ArticleServiceTest extends TestCase
{
    /**
     * @var ArticleService
     */
    private $service;

    /**
     * @var ArticleRepository|MockObject
     */
    private $repository;

    /**
     * @var ValidatorInterface|MockObject
     */
    private $validator;

    /**
     * @var EntityManagerInterface|MockObject
     */
    private $em;

    public function testGetArticle()
    {
        $dummy = new Article();
        $dummy->setTitle('sample title');
        $this->repository->expects($this->once())->method('find')->willReturn($dummy);

        $article = $this->service->getArticle(1);
        $this->assertNotNull($article);
        $this->assertEquals($article->getTitle(), 'sample title');
        $this->assertNotNull($article->getCreatedAt());
    }

    public function testGetNoArticle()
    {
        $this->repository->expects($this->once())->method('find')->willReturn(null);
        $this->expectException(NotFoundHttpException::class);
        $this->service->getArticle(1);
    }

    public function testAddArticle()
    {
        $article = new Article();
        $article->setTitle('title');
        $errorCollection = new ConstraintViolationList([]);
        $this->validator->method('validate')->willReturn($errorCollection);
        $this->em->method('persist');
        $this->em->method('flush');

        $this->assertEquals('title', $this->service->addArticle($article)->getTitle());
    }

    public function testAddInvalidArticle()
    {
        $article = new Article();
        $error = $this->createMock(ConstraintViolation::class);
        $error->method('__toString')->willReturn('string');
        $errorCollection = new ConstraintViolationList([$error]);
        $this->validator->method('validate')->willReturn($errorCollection);

        $this->expectException(BadRequestHttpException::class);
        $this->service->addArticle($article);
    }

    public function testUpdateArticle()
    {
        $article = new Article();
        $article->setTitle('article 1');
        $newArticle = new Article();
        $newArticle->setTitle('article 2');

        $errorCollection = new ConstraintViolationList([]);
        $this->validator->method('validate')->willReturn($errorCollection);
        $this->em->method('persist');
        $this->em->method('flush');

        $this->expectException(NotFoundHttpException::class);
        $this->service->updateArticle(1, $newArticle);

        $this->repository->expects($this->once())->method('find')->willReturn($article);
        $updatedArticle = $this->service->updateArticle(1, $newArticle);

        $this->assertEquals($newArticle->getTitle(), $updatedArticle->getTitle());
    }

    protected function setUp()
    {
        $this->repository = $this->createMock(ArticleRepository::class);
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->em = $this->createMock(EntityManagerInterface::class);

        $this->service = new ArticleService($this->repository, $this->validator, $this->em);
    }
}