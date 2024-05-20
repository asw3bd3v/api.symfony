<?php

namespace App\Tests\Service;

use App\Entity\Book;
use App\Entity\BookCategory;
use App\Exception\BookCategoryNotFoundException;
use App\Model\BookListItem;
use App\Model\BookListResponse;
use App\Repository\BookCategoryRepository;
use App\Repository\BookRepository;
use App\Repository\ReviewRepository;
use App\Service\BookService;
use App\Service\RatingService;
use App\Tests\AbstractTestCase;

class BookServiceTest extends AbstractTestCase
{
    private $reviewRepository;
    private $bookRepository;
    private $bookCategoryRepository;
    private $ratingService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->reviewRepository = $this->createMock(ReviewRepository::class);
        $this->bookRepository = $this->createMock(BookRepository::class);
        $this->bookCategoryRepository = $this->createMock(BookCategoryRepository::class);
        $this->ratingService = $this->createMock(RatingService::class);
    }
    public function testGetBooksByCategoryNotFound()
    {
        $this->bookCategoryRepository->expects($this->once())
            ->method('existsById')
            ->with(130)
            //->willThrowException(new BookCategoryNotFoundException());
            ->willReturn(false);

        $this->expectException(BookCategoryNotFoundException::class);

        (new BookService(
            $this->bookRepository,
            $this->bookCategoryRepository,
            $this->reviewRepository,
            $this->ratingService,
        ))->getBooksByCategory(130);
    }

    public function testGetBooksByCategory(): void
    {
        $this->bookRepository->expects($this->once())
            ->method('findBooksByCategoryId')
            ->with(130)
            ->willReturn([$this->createBookEntity()]);

        $bookCategory = new BookCategory();
        $this->setEntityId($bookCategory, 130);

        $this->bookCategoryRepository->expects($this->once())
            ->method('existsById')
            ->with(130)
            ->willReturn(true);

        $service = new BookService(
            $this->bookRepository,
            $this->bookCategoryRepository,
            $this->reviewRepository,
            $this->ratingService,
        );

        $expected = new BookListResponse([$this->createBookItemModel()]);

        $this->assertEquals($expected, $service->getBooksByCategory(130));
    }

    private function createBookEntity(): Book
    {
        $book = (new Book())
            ->setTitle('Test Book')
            ->setSlug('test-book')
            ->setMeap(false)
            ->setIsbn('123321')
            ->setDescription('test description')
            ->setAuthors(['Tester'])
            ->setImage('http://localhost/test.png')
            ->setPublicationDate(new \DateTimeImmutable('2020-10-10'));

        $this->setEntityId($book, 123);

        return $book;
    }

    private function createBookItemModel(): BookListItem
    {
        return (new BookListItem())
            ->setId(123)
            ->setTitle('Test Book')
            ->setSlug('test-book')
            ->setMeap(false)
            ->setAuthors(['Tester'])
            ->setImage('http://localhost/test.png')
            ->setPublicationDate((new \DateTimeImmutable('2020-10-10'))->getTimestamp());
    }
}
