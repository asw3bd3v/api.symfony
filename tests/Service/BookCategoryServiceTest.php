<?php

namespace App\Tests\Service;

use Symfony\Component\String\Slugger\SluggerInterface;
use App\Entity\BookCategory;
use App\Model\BookCategory as BookCategoryModel;
use App\Model\BookCategoryListResponse;
use App\Repository\BookCategoryRepository;
use App\Service\BookCategoryService;
use App\Tests\AbstractTestCase;

class BookCategoryServiceTest extends AbstractTestCase
{
    public function testGetCategories(): void
    {
        $bookCategory = (new BookCategory())
            ->setTitle('Test')
            ->setSlug('test');

        $this->setEntityId($bookCategory, 1);

        $repository = $this->createMock(BookCategoryRepository::class);

        $repository->expects($this->once())
            ->method('findAllSortedByTitle')
            ->willReturn([$bookCategory]);

        $slugger = $this->createMock(SluggerInterface::class);

        $service = new BookCategoryService($repository, $slugger);

        $expected = new BookCategoryListResponse([
            new BookCategoryModel(1, 'Test', 'test'),
        ]);

        $this->assertEquals($expected, $service->getCategories());
    }
}
