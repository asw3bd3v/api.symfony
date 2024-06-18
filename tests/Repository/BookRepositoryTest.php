<?php

namespace App\Tests\Repository;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Repository\BookRepository;
use App\Tests\AbstractRepositoryTest;
use App\Tests\MockUtils;
use Doctrine\Common\Collections\ArrayCollection;

class BookRepositoryTest extends AbstractRepositoryTest
{
    private BookRepository $bookRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->bookRepository = $this->getRepositoryForEntity(Book::class);
    }

    public function testfindPublishedBooksByCategoryId()
    {
        $user = MockUtils::createUser();
        $this->entityManager->persist($user);

        $devicesCategory = MockUtils::createBookCategory();
        $this->entityManager->persist($devicesCategory);

        for ($i = 0; $i < 6; ++$i) {
            $book = MockUtils::createBook()
                ->setUser($user)
                ->setTitle('device-' . $i)
                ->setCategories(new ArrayCollection([$devicesCategory]));

            $this->entityManager->persist($book);
        }

        $this->entityManager->flush();

        $this->assertCount(6, $this->bookRepository->findPublishedBooksByCategoryId($devicesCategory->getId()));
    }
}
