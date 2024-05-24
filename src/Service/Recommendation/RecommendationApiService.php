<?php

namespace App\Service\Recommendation;

use App\Service\Recommendation\Exception\AccessDeniedException;
use App\Service\Recommendation\Exception\RequestException;
use App\Service\Recommendation\Model\RecommendationResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RecommendationApiService
{
    public function __construct(
        private HttpClientInterface $recommendationClient,
        private SerializerInterface $serializer,
    ) {
    }

    /**
     * @throws AccessDeniedException
     * @throws RequestException
     */
    public function getRecommendationsByBookId(string $id): RecommendationResponse
    {
        try {
            $response = $this->recommendationClient->request('GET', '/api/v1/book/' . $id . '/recommendations');

            return $this->serializer->deserialize(
                $response->getContent(),
                RecommendationResponse::class,
                JsonEncoder::FORMAT
            );
        } catch (\Throwable $exception) {
            if ($exception instanceof TransportExceptionInterface && Response::HTTP_FORBIDDEN === $exception->getCode()) {
                throw new AccessDeniedException();
            }

            throw new RequestException($exception->getMessage(), $exception);
        }
    }
}
