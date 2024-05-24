<?php

namespace App\Tests\Service;

use App\Entity\Book;
use App\Model\RecommendedBook;
use App\Model\RecommendedBookListResponse;
use App\Repository\BookRepository;
use App\Service\Recommendation\Model\RecommendationItem;
use App\Service\Recommendation\Model\RecommendationResponse;
use App\Service\Recommendation\RecommendationApiService;
use App\Service\RecommendationService;
use App\Tests\AbstractTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class RecommendationServiceTest extends AbstractTestCase
{
    private $bookRepository;
    private $recommendationApiService;

    public function setUp(): void
    {
        parent::setUp();

        $this->bookRepository = $this->createMock(BookRepository::class);
        $this->recommendationApiService = $this->createMock(RecommendationApiService::class);
    }

    #[DataProvider('dataProvider')]
    public function testGetRecommendationsByBookId(string $actualDescription, string $expectedDescription): void
    {
        $entity = (new Book())
            ->setImage('image')
            ->setSlug('slug')
            ->setTitle('title')
            ->setDescription($actualDescription);

        $this->setEntityId($entity, 2);

        $this->bookRepository->expects($this->once())
            ->method('findBooksByIds')
            ->with([2])
            ->willReturn([$entity]);

        $this->recommendationApiService->expects($this->once())
            ->method('getRecommendationsByBookId')
            ->with(1)
            ->willReturn(new RecommendationResponse(1, 12345, [
                new RecommendationItem(2),
            ]));

        $expected = new RecommendedBookListResponse([
            (new RecommendedBook())->setTitle('title')
                ->setSlug('slug')
                ->setImage('image')
                ->setId(2)
                ->setShortDescription($expectedDescription),
        ]);

        $this->assertEquals($expected, $this->createService()->getRecommendationsByBookId(1));
    }

    public static function dataProvider(): array
    {
        return [
            ['short description', 'short description'],
            [
                <<<EOF
begin long description long description
long description long description long
long description long description
long description long description
description
EOF,
                <<<EOF
begin long description long description
long description long description long
long description long description
long description long descripti...
EOF,
            ],
        ];
    }

    private function createService(): RecommendationService
    {
        return new RecommendationService($this->bookRepository, $this->recommendationApiService);
    }
}
