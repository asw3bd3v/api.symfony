<?php

namespace App\Tests\Service\Recommendation;

use App\Service\Recommendation\Exception\AccessDeniedException;
use App\Service\Recommendation\Exception\RequestException;
use App\Service\Recommendation\RecommendationApiService;
use App\Tests\AbstractTestCase;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\Serializer\SerializerInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpFoundation\Response;

class RecommendationApiServiceTest extends AbstractTestCase
{
    private $serializer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->serializer = $this->createMock(SerializerInterface::class);
    }

    #[DataProvider('dataProvider')]
    public function testGetRecommendationsByBookId(int $responseCode, string $exceptionClass): void
    {
        $this->expectException($exceptionClass);

        $httpClient = new MockHttpClient(
            new MockResponse('', ['http_code' => $responseCode]),
            'http://localhost'
        );

        (new RecommendationApiService($httpClient, $this->serializer))->getRecommendationsByBookId(1);
    }

    public static function dataProvider(): array
    {
        return [
            [Response::HTTP_FORBIDDEN, AccessDeniedException::class],
            [Response::HTTP_CONFLICT, RequestException::class],
        ];
    }
}
