<?php

namespace App\Tests\Repository;

use App\Entity\BookCategory;
use App\Repository\BookCategoryRepository;
use App\Tests\AbstractRepositoryTest;

class BookCategoryRepositoryTest extends AbstractRepositoryTest
{
    private BookCategoryRepository $bookCategoryRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->bookCategoryRepository = $this->getRepositoryForEntity(BookCategory::class);
    }

    public function testFindAllSortedByTitle(): void
    {
        $deviceCategory = (new BookCategory())
            ->setTitle('Device')
            ->setSlug('device');
        $androidCategory = (new BookCategory())
            ->setTitle('Android')
            ->setSlug('android');
        $databaseCategory = (new BookCategory())
            ->setTitle('Database')
            ->setSlug('database');

        $categories = [$deviceCategory, $androidCategory, $databaseCategory];

        foreach ($categories as $category) {
            $this->entityManager->persist($category);
        }

        $this->entityManager->flush();

        $titles = array_map(
            fn (BookCategory $category) => $category->getTitle(),
            $this->bookCategoryRepository->findAllSortedByTitle()
        );

        $this->assertEquals(['Android', 'Database', 'Device'], $titles);
    }
}
