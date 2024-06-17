<?php

namespace App\Tests\Controller;

use App\Entity\Book;
use App\Entity\Review;
use App\Tests\AbstractControllerTest;

class ReviewControllerTest extends AbstractControllerTest
{
    public function testReview(): void
    {
        $book = $this->createBook();

        $this->createReview($book);

        $this->entityManager->flush();

        $this->client->request('GET', '/api/v1/book/' . $book->getId() . '/reviews');
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema($responseContent, [
            'type' => 'object',
            'required' => ['items', 'rating', 'page', 'pages', 'perPage', 'total'],
            'properties' => [
                'rating' => ['type' => 'number'],
                'page' => ['type' => 'integer'],
                'pages' => ['type' => 'integer'],
                'perPage' => ['type' => 'integer'],
                'total' => ['type' => 'integer'],
                'items' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'required' => ['id', 'content', 'author', 'rating', 'createdAt'],
                        'properties' => [
                            'id' => ['type' => 'integer'],
                            'rating' => ['type' => 'integer'],
                            'createdAt' => ['type' => 'integer'],
                            'content' => ['type' => 'string'],
                            'author' => ['type' => 'string'],
                        ]
                    ]
                ]
            ]
        ]);
    }

    private function createBook(): Book
    {
        $book = (new Book())
            ->setTitle("Test book")
            ->setSlug("test-book")
            ->setImage("http://localhost.png")
            ->setIsbn('123321')
            ->setDescription('test description')
            ->setPublicationDate(new \DateTimeImmutable(""))
            ->setAuthors(['Tester']);

        $this->entityManager->persist($book);

        return $book;
    }

    private function createReview(Book $book): void
    {
        $this->entityManager->persist((new Review())
                ->setAuthor('tester')
                ->setContent('test content')
                ->setCreatedAt(new \DateTimeImmutable())
                ->setRating(5)
                ->setBook($book)
        );
    }
}
