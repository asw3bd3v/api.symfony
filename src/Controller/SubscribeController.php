<?php

namespace App\Controller;

use App\Attribute\RequestBody;
use App\Model\ErrorResponse;
use App\Model\SubscriberRequest;
use App\Service\BookService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Service\SubscriberService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SubscribeController extends AbstractController
{
    public function __construct(private SubscriberService $subscriberService)
    {
    }

    #[Route(path: "/api/v1/subscribe", name: "subscribe", methods: ["POST"])]
    #[OA\Response(
        response: 200,
        description: 'Subscribe email to newsletter mailing list',
    )]
    public function subscribe(#[RequestBody] SubscriberRequest $request): Response
    {
        $this->subscriberService->subscribe($request);

        return $this->json(null);
    }
}
