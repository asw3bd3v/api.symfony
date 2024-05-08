<?php

namespace App\Tests\Repository;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Repository\BookRepository;
use App\Tests\AbstractRepositoryTest;

class BookRepositoryTest extends AbstractRepositoryTest
{
    private BookRepository $bookRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->bookRepository = $this->getRepositoryForEntity(Book::class);
    }

    public function testFindBooksByCategoryId()
    {
        $devicesCategory = (new BookCategory())
            ->setTitle('Devices')
            ->setSlug('devices');

        $this->entityManager->persist($devicesCategory);

        for ($i = 0; $i < 6; ++$i) {
            $book = $this->createBook('device-' . $i, $devicesCategory);
            $this->entityManager->persist($book);
        }

        $this->entityManager->flush();

        $this->assertCount(6, $this->bookRepository->findBooksByCategoryId($devicesCategory->getId()));
    }

    private function createBook(string $title, BookCategory $category): Book
    {
        return (new Book())
            ->setPublicationDate(new \DateTime())
            ->setTitle($title)
            ->setAuthors(['author'])
            ->setMeap(false)
            ->setSlug($title)
            ->addCategory($category)
            ->setImage('http://localhost/' . $title . '.png');
    }
}
