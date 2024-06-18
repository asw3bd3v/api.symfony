<?php

namespace App\Tests\Controller;

use App\Tests\AbstractControllerTest;
use App\Tests\MockUtils;

class ReviewControllerTest extends AbstractControllerTest
{
    public function testReview(): void
    {
        $user = MockUtils::createUser();
        $this->entityManager->persist($user);

        $book = MockUtils::createBook()
            ->setUser($user);
        $this->entityManager->persist($book);

        $this->entityManager->persist(MockUtils::createReview($book));

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
}
