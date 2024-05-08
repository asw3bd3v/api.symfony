<?php

namespace App\Tests\Service;

use App\Entity\BookCategory;
use App\Model\BookCategoryListItem;
use App\Model\BookCategoryListResponse;
use App\Repository\BookCategoryRepository;
use App\Service\BookCategoryService;
use App\Tests\AbstractTestCase;
use Doctrine\Common\Collections\Criteria;

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
            ->method("findBy")
            ->with([], ['title' => Criteria::ASC])
            ->willReturn([$bookCategory]);

        $service = new BookCategoryService($repository);

        $expected = new BookCategoryListResponse([
            new BookCategoryListItem(1, 'Test', 'test'),
        ]);

        $this->assertEquals($expected, $service->getCategories());
    }
}
