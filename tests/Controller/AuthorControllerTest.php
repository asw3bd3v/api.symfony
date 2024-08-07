<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace App\Tests\Controller;

use App\Tests\AbstractControllerTest;
use App\Tests\MockUtils;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AuthorControllerTest extends AbstractControllerTest
{
    public function testCreateBook(): void
    {
        $this->createAuthorAndAuth('user@test.com', 'testtest');
        $this->client->request('POST', '/api/v1/author/book', [], [], [], json_encode([
            'title' => 'Test Book',
        ]));

        $responseContent = json_decode($this->client->getResponse()->getContent(), null, 512, JSON_THROW_ON_ERROR);

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema($responseContent, [
            'type' => 'object',
            'required' => ['id'],
            'properties' => [
                'id' => ['type' => 'integer'],
            ],
        ]);
    }

    /* public function testUploadBookCover(): void
    {
        $user = $this->createAuthorAndAuth('user@test.com', 'testtest');
        $book = MockUtils::createBook()->setUser($user)->setImage(null);

        $this->entityManager->persist($book);
        $this->entityManager->flush();

        $fixturePath = __DIR__ . '/../Fixtures/logo_light_white.png';
        $clonedImagePath = sys_get_temp_dir() . PATH_SEPARATOR . 'test.png';

        (new Filesystem())->copy($fixturePath, $clonedImagePath);

        $uploadedFile = new UploadedFile(
            $clonedImagePath,
            'test.png',
            'image/png',
            null,
            true,
        );

        $this->client->request('POST', '/api/v1/author/book/' . $book->getId() . '/uploadCover', [], [
            'cover' => $uploadedFile,
        ]);

        $responseContent = json_decode($this->client->getResponse()->getContent(), null, 512, JSON_THROW_ON_ERROR);

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema($responseContent, [
            'type' => 'object',
            'required' => ['link'],
            'properties' => [
                'link' => ['type' => 'string'],
            ],
        ]);
    } */

    public function testDeleteBook(): void
    {
        $user = $this->createAuthorAndAuth('user@test.com', 'testtest');
        $book = MockUtils::createBook()->setUser($user);

        $this->entityManager->persist($book);
        $this->entityManager->flush();

        $this->client->request('DELETE', '/api/v1/author/book/' . $book->getId());

        $this->assertResponseIsSuccessful();
    }

    public function testUpdateBook(): void
    {
        $user = $this->createAuthorAndAuth('user@test.com', 'testtest');
        $book = MockUtils::createBook()->setUser($user);
        $category = MockUtils::createBookCategory();
        $format = MockUtils::createBookFormat();

        $this->entityManager->persist($book);
        $this->entityManager->persist($format);
        $this->entityManager->persist($category);
        $this->entityManager->flush();

        $this->client->request('POST', '/api/v1/author/book/' . $book->getId(), [], [], [], json_encode([
            'title' => 'Updated Book',
            'authors' => ['vasya'],
            'isbn' => 'testing',
            'description' => 'testing update',
            'categories' => [$category->getId()],
            'formats' => [['id' => $format->getId(), 'price' => 123.5, 'discountPercent' => 5]],
        ]));

        $this->assertResponseIsSuccessful();
    }

    /* public function testPublishBook(): void
    {
        $user = $this->createAuthorAndAuth('user@test.com', 'testtest');
        $book = MockUtils::createBook()->setUser($user);

        $this->entityManager->persist($book);
        $this->entityManager->flush();

        $this->client->request(
            'POST',
            '/api/v1/author/book/' . $book->getId() . '/publish',
            [],
            [],
            [],
            json_encode(['date' => '22.02.2010'])
        );

        $this->assertResponseIsSuccessful();
    } */

    public function testUnpublishBook(): void
    {
        $user = $this->createAuthorAndAuth('user@test.com', 'testtest');
        $book = MockUtils::createBook()->setUser($user);

        $this->entityManager->persist($book);
        $this->entityManager->flush();

        $this->client->request('POST', '/api/v1/author/book/' . $book->getId() . '/unpublish');

        $this->assertResponseIsSuccessful();
    }

    public function testBooks(): void
    {
        $user = $this->createAuthorAndAuth('user@test.com', 'testtest');
        $book = MockUtils::createBook()->setUser($user);

        $this->entityManager->persist($book);
        $this->entityManager->flush();

        $this->client->request('GET', '/api/v1/author/books');

        $responseContent = json_decode($this->client->getResponse()->getContent(), null, 512, JSON_THROW_ON_ERROR);

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema($responseContent, [
            'type' => 'object',
            'required' => ['items'],
            'properties' => [
                'items' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'required' => ['id', 'title', 'slug', 'image'],
                        'properties' => [
                            'title' => ['type' => 'string'],
                            'slug' => ['type' => 'string'],
                            'id' => ['type' => 'integer'],
                            'image' => ['type' => 'string'],
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function testBook(): void
    {
        $user = $this->createAuthorAndAuth('user@test.com', 'testtest');
        $category = MockUtils::createBookCategory();
        $format = MockUtils::createBookFormat();
        $book = MockUtils::createBook()->setUser($user)->setCategories(new ArrayCollection([$category]));
        $join = MockUtils::createBookFormatLink($book, $format);

        $this->entityManager->persist($category);
        $this->entityManager->persist($format);
        $this->entityManager->persist($book);
        $this->entityManager->persist($join);
        $this->entityManager->flush();

        $this->client->request('GET', '/api/v1/author/book/' . $book->getId());

        $responseContent = json_decode($this->client->getResponse()->getContent(), null, 512, JSON_THROW_ON_ERROR);

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema($responseContent, [
            'type' => 'object',
            'required' => [
                'slug', 'isbn', 'description', 'categories', 'title', 'image', 'formats', 'authors', 'publicationDate',
            ],
            'properties' => [
                'title' => ['type' => 'string'],
                'description' => ['type' => 'string'],
                'slug' => ['type' => 'string'],
                'id' => ['type' => 'integer'],
                'publicationDate' => ['type' => 'integer'],
                'image' => ['type' => 'string'],
                'isbn' => ['type' => 'string'],
                'authors' => [
                    'type' => 'array',
                    'items' => ['type' => 'string'],
                ],
                'categories' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'required' => ['id', 'title', 'slug'],
                        'properties' => [
                            'title' => ['type' => 'string'],
                            'slug' => ['type' => 'string'],
                            'id' => ['type' => 'integer'],
                        ],
                    ],
                ],
                'formats' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'required' => ['id', 'title', 'description', 'comment', 'price', 'discountPercent'],
                        'properties' => [
                            'title' => ['type' => 'string'],
                            'description' => ['type' => 'string'],
                            'id' => ['type' => 'integer'],
                            'comment' => ['type' => ['string', 'null']],
                            'price' => ['type' => 'number'],
                            'discountPercent' => ['type' => 'integer'],
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function testCreateBookChapter(): void
    {
        $user = $this->createAuthorAndAuth('user@test.com', 'testtest');
        $book = MockUtils::createBook()->setUser($user);

        $this->entityManager->persist($book);
        $this->entityManager->flush();

        $this->client->request(
            'POST',
            '/api/v1/author/book/' . $book->getId() . '/chapter',
            [],
            [],
            [],
            json_encode(['title' => 'Test Book'])
        );

        $responseContent = json_decode($this->client->getResponse()->getContent(), null, 512, JSON_THROW_ON_ERROR);

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema($responseContent, [
            'type' => 'object',
            'required' => ['id'],
            'properties' => [
                'id' => ['type' => 'integer'],
            ],
        ]);
    }

    public function testUpdateBookChapter(): void
    {
        $user = $this->createAuthorAndAuth('user@test.com', 'testtest');
        $book = MockUtils::createBook()->setUser($user);
        $chapter = MockUtils::createBookChapter($book);

        $this->entityManager->persist($book);
        $this->entityManager->persist($chapter);
        $this->entityManager->flush();

        $url = '/api/v1/author/book/' . $book->getId() . '/chapter/' . $chapter->getId();
        $this->client->request(
            'POST',
            $url,
            [],
            [],
            [],
            json_encode(['title' => 'Updated Book Chapter'], JSON_THROW_ON_ERROR)
        );

        $this->assertResponseIsSuccessful();
    }

    public function testUpdateBookChapterSort(): void
    {
        $user = $this->createAuthorAndAuth('user@test.com', 'testtest');
        $book = MockUtils::createBook()->setUser($user);
        $chapterFirst = MockUtils::createBookChapter($book);
        $chapterSecond = MockUtils::createBookChapter($book);
        $chapterThird = MockUtils::createBookChapter($book);

        $this->entityManager->persist($book);
        $this->entityManager->persist($chapterFirst);
        $this->entityManager->persist($chapterSecond);
        $this->entityManager->persist($chapterThird);
        $this->entityManager->flush();

        $url = '/api/v1/author/book/' . $book->getId() . '/chapter/' . $chapterFirst->getId() . '/sort';
        $this->client->request(
            'POST',
            $url,
            [],
            [],
            [],
            json_encode([
                'nextId' => $chapterThird->getId(),
                'previousId' => $chapterSecond->getId(),
            ], JSON_THROW_ON_ERROR)
        );

        $this->assertResponseIsSuccessful();
    }

    public function testGetBookChapterTree(): void
    {
        $user = $this->createAuthorAndAuth('user@test.com', 'testtest');
        $book = MockUtils::createBook()->setUser($user);
        $chapterMain = MockUtils::createBookChapter($book);
        $chapterNested = MockUtils::createBookChapter($book)
            ->setLevel(2)
            ->setParent($chapterMain)
            ->setSort(2);

        $this->entityManager->persist($book);
        $this->entityManager->persist($chapterMain);
        $this->entityManager->persist($chapterNested);
        $this->entityManager->flush();

        $this->client->request('GET', '/api/v1/author/book/' . $book->getId() . '/chapters');

        $responseContent = json_decode($this->client->getResponse()->getContent(), null, 512, JSON_THROW_ON_ERROR);

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema($responseContent, [
            'type' => 'object',
            'required' => ['items'],
            'properties' => [
                'items' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'required' => ['id', 'title', 'slug', 'items'],
                        'properties' => [
                            'title' => ['type' => 'string'],
                            'slug' => ['type' => 'string'],
                            'id' => ['type' => 'integer'],
                            'items' => [
                                'type' => 'array',
                                'items' => [
                                    'type' => 'object',
                                    'required' => ['id', 'title', 'slug', 'items'],
                                    'properties' => [
                                        'title' => ['type' => 'string'],
                                        'slug' => ['type' => 'string'],
                                        'id' => ['type' => 'integer'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }

    /* public function testDeleteBookChapter(): void
    {
        $user = $this->createAuthorAndAuth('user@test.com', 'testtest');
        $book = MockUtils::createBook()->setUser($user);
        $chapter = MockUtils::createBookChapter($book);

        $this->entityManager->persist($book);
        $this->entityManager->persist($chapter);
        $this->entityManager->flush();

        $this->client->request('DELETE', '/api/v1/author/book/' . $book->getId() . '/chapter/' . $chapter->getId());

        $this->assertResponseIsSuccessful();
    } */

    public function testCreateBookContent(): void
    {
        $user = $this->createAuthorAndAuth('user@test.com', 'testtest');
        $book = MockUtils::createBook()->setUser($user);
        $chapter = MockUtils::createBookChapter($book);

        $this->entityManager->persist($book);
        $this->entityManager->persist($chapter);
        $this->entityManager->flush();

        $url = sprintf('/api/v1/author/book/%d/chapter/%d/content', $book->getId(), $chapter->getId());
        $this->client->request(
            'POST',
            $url,
            [],
            [],
            [],
            json_encode(['content' => 'Test Content', 'published' => true])
        );

        $responseContent = json_decode($this->client->getResponse()->getContent(), null, 512, JSON_THROW_ON_ERROR);

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatchesSchema($responseContent, [
            'type' => 'object',
            'required' => ['id'],
            'properties' => [
                'id' => ['type' => 'integer'],
            ],
        ]);
    }

    public function testUpdateBookContent(): void
    {
        $user = $this->createAuthorAndAuth('user@test.com', 'testtest');
        $book = MockUtils::createBook()->setUser($user);
        $chapter = MockUtils::createBookChapter($book);
        $content = MockUtils::createBookContent($chapter);

        $this->entityManager->persist($book);
        $this->entityManager->persist($chapter);
        $this->entityManager->persist($content);
        $this->entityManager->flush();

        $url = sprintf('/api/v1/author/book/%d/chapter/%d/content/%d', $book->getId(), $chapter->getId(), $content->getId());
        $this->client->request(
            'DELETE',
            $url,
            [],
            [],
            [],
            json_encode(['content' => 'New Test Content', 'published' => false])
        );

        $this->assertResponseIsSuccessful();
    }

    public function testDeleteBookContent(): void
    {
        $user = $this->createAuthorAndAuth('user@test.com', 'testtest');
        $book = MockUtils::createBook()->setUser($user);
        $chapter = MockUtils::createBookChapter($book);
        $content = MockUtils::createBookContent($chapter);

        $this->entityManager->persist($book);
        $this->entityManager->persist($chapter);
        $this->entityManager->persist($content);
        $this->entityManager->flush();

        $url = sprintf('/api/v1/author/book/%d/chapter/%d/content/%d', $book->getId(), $chapter->getId(), $content->getId());
        $this->client->request('DELETE', $url);

        $this->assertResponseIsSuccessful();
    }

    public function testChapterContent(): void
    {
        $user = $this->createAuthorAndAuth('user@test.com', 'testtest');
        $book = MockUtils::createBook()->setUser($user);
        $chapter = MockUtils::createBookChapter($book);
        $content = MockUtils::createBookContent($chapter);
        $unpublishedContent = MockUtils::createBookContent($chapter)->setPublished(false);

        $this->entityManager->persist($book);
        $this->entityManager->persist($chapter);
        $this->entityManager->persist($content);
        $this->entityManager->persist($unpublishedContent);
        $this->entityManager->flush();

        $url = sprintf('/api/v1/author/book/%d/chapter/%d/content', $book->getId(), $chapter->getId());
        $this->client->request('GET', $url);

        $responseContent = json_decode($this->client->getResponse()->getContent(), null, 512, JSON_THROW_ON_ERROR);

        $this->assertResponseIsSuccessful();
        $this->assertJsonDocumentMatches($responseContent, ['$.items' => self::countOf(2)]);
        $this->assertJsonDocumentMatchesSchema($responseContent, [
            'type' => 'object',
            'required' => ['items', 'page', 'pages', 'perPage', 'total'],
            'properties' => [
                'page' => ['type' => 'integer'],
                'pages' => ['type' => 'integer'],
                'perPage' => ['type' => 'integer'],
                'total' => ['type' => 'integer'],
                'items' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'required' => ['id', 'content', 'published'],
                        'properties' => [
                            'id' => ['type' => 'integer'],
                            'content' => ['type' => 'string'],
                            'published' => ['type' => 'boolean'],
                        ],
                    ],
                ],
            ],
        ]);
    }
}
