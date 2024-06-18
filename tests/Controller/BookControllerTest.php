<?php

namespace App\Tests\Controller;

use App\Tests\AbstractControllerTest;
use App\Tests\MockUtils;
use Doctrine\Common\Collections\ArrayCollection;

class BookControllerTest extends AbstractControllerTest
{
    public function testBooksByCategory(): void
    {
        $user = MockUtils::createUser();
        $this->entityManager->persist($user);

        $bookCategory = MockUtils::createBookCategory();
        $this->entityManager->persist($bookCategory);

        $book = MockUtils::createBook()
            ->setCategories(new ArrayCollection([$bookCategory]))
            ->setUser($user);
        $this->entityManager->persist($book);

        $this->entityManager->flush();

        $this->client->request('GET', '/api/v1/category/' . $bookCategory->getId() . '/books');
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema($responseContent, [
            'type' => 'object',
            'required' => ['items'],
            'properties' => [
                'items' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'required' => ['id', 'title', 'slug', 'image', 'authors', 'publicationDate'],
                        'properties' => [
                            'id' => ['type' => 'integer'],
                            'title' => ['type' => 'string'],
                            'slug' => ['type' => 'string'],
                            'image' => ['type' => 'string'],
                            'publicationDate' => ['type' => 'integer'],
                            'authors' => [
                                'type' => 'array',
                                'items' => [
                                    'type' => 'string',
                                ]
                            ],
                        ]
                    ]
                ]
            ]
        ]);
    }

    public function testBookById(): void
    {
        $user = MockUtils::createUser();
        $this->entityManager->persist($user);

        $bookCategory = MockUtils::createBookCategory();
        $this->entityManager->persist($bookCategory);

        $format = MockUtils::createBookFormat();
        $this->entityManager->persist($format);

        $book = MockUtils::createBook()
            ->setCategories(new ArrayCollection([$bookCategory]))
            ->setUser($user);
        $this->entityManager->persist($book);

        $this->entityManager->persist(MockUtils::createBookFormatLink($book, $format));

        $this->entityManager->flush();

        $this->client->request('GET', '/api/v1/book/' . $book->getId());
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema($responseContent, [
            'type' => 'object',
            'required' => [
                'id', 'title', 'slug', 'image', 'authors', 'publicationDate', 'rating', 'reviews', 'categories', 'formats',
            ],
            'properties' => [
                'id' => ['type' => 'integer'],
                'title' => ['type' => 'string'],
                'slug' => ['type' => 'string'],
                'image' => ['type' => 'string'],
                'publicationDate' => ['type' => 'integer'],
                'authors' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'string',
                    ]
                ],
                'rating' => ['type' => 'number'],
                'reviews' => ['type' => 'integer'],
                'categories' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'required' => ['id', 'title', 'slug'],
                        'properties' => [
                            'id' => ['type' => 'integer'],
                            'title' => ['type' => 'string'],
                            'slug' => ['type' => 'string'],
                        ],
                    ]
                ],
            ]
        ]);
    }
    /* 
    private function createCategory(): int
    {
        $bookCategory = (new BookCategory())->setTitle('Devices')->setSlug('devices');
        $this->entityManager->persist($bookCategory);

        $this->entityManager->persist(
            (new Book())
                ->setTitle("Test book")
                ->setSlug("test-book")
                ->setImage("http://localhost.png")
                ->setIsbn('123321')
                ->setDescription('test description')
                ->setPublicationDate(new \DateTimeImmutable(""))
                ->setAuthors(['Tester'])
                ->addCategory($bookCategory)
        );

        $this->entityManager->flush();

        return $bookCategory->getId();
    }

    private function createBook(): int
    {
        $bookCategory = (new BookCategory())->setTitle('Devices')->setSlug('devices');
        $this->entityManager->persist($bookCategory);

        $format = (new BookFormat())
            ->setTitle('format')
            ->setDescription('description format')
            ->setComment(null);

        $this->entityManager->persist($format);

        $book = (new Book())
            ->setTitle("Test book")
            ->setSlug("test-book")
            ->setImage("http://localhost.png")
            ->setIsbn('123321')
            ->setDescription('test description')
            ->setPublicationDate(new \DateTimeImmutable(""))
            ->setAuthors(['Tester'])
            ->addCategory($bookCategory);

        $this->entityManager->persist($book);

        $join = (new BookToBookFormat())->setPrice(123.55)
            ->setFormat($format)
            ->setDiscountPercent(5)
            ->setBook($book);

        $this->entityManager->persist($join);

        $this->entityManager->flush();

        return $book->getId();
    } */
}
