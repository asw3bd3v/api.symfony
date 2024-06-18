<?php

namespace App\Tests\Controller;

use App\Tests\AbstractControllerTest;
use App\Tests\MockUtils;
use Hoverfly\Client as HoverflyClient;
use Hoverfly\Model\RequestFieldMatcher;
use Hoverfly\Model\Response;

class RecommendationControllerTest extends AbstractControllerTest
{
    private $hoverflyClient;

    public function setUp(): void
    {
        parent::setUp();

        $this->setUpHoverfly();
    }

    public function testRecommendationsByBookId(): void
    {
        $user = MockUtils::createUser();
        $this->entityManager->persist($user);

        $book = MockUtils::createBook()
            ->setUser($user);
        $this->entityManager->persist($book);

        $this->entityManager->flush();

        $requestedId = 123;

        $this->hoverflyClient->simulate(
            $this->hoverflyClient
                ->buildSimulation()
                ->service()
                ->get(
                    new RequestFieldMatcher(
                        '/api/v1/book/' . $requestedId . '/recommendations',
                        RequestFieldMatcher::GLOB
                    )
                )
                ->headerExact('Authorization', 'Bearer test')
                ->willReturn(Response::json([
                    'ts' => 12345,
                    'id' => $requestedId,
                    'recommendations' => [['id' => $book->getId()]]
                ]))
        );

        $this->client->request('GET', '/api/v1/book/123/recommendations');
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
                        'required' => ['id', 'title', 'slug', 'image', 'shortDescription'],
                        'properties' => [
                            'id' => ['type' => 'integer'],
                            'title' => ['type' => 'string'],
                            'slug' => ['type' => 'string'],
                            'image' => ['type' => 'string'],
                            'shortDescription' => ['type' => 'string'],
                        ]
                    ]
                ]
            ]
        ]);
    }

    private function setUpHoverfly(): void
    {
        $this->hoverflyClient = new HoverflyClient(['base_uri' => $_ENV['HOVERFLY_API']]);
        $this->hoverflyClient->deleteJournal();
        $this->hoverflyClient->deleteSimulation();
    }
}
