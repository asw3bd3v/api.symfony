<?php

namespace App\Controller;

use App\Attribute\RequestBody;
use App\Model\ErrorResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use OpenApi\Attributes as OA;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Model\IdResponse;
use App\Model\SignUpRequest;
use App\Service\SignUpService;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends AbstractController
{
    public function __construct(private readonly SignUpService $signUpService)
    {
    }

    #[Route(path: '/api/v1/auth/signUp', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Signs up a user',
        content: new Model(type: IdResponse::class)
    )]
    #[OA\Response(response: 409, description: 'User already exists', attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\Response(response: 400, description: 'Validation failed', attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\RequestBody(attachables: [new Model(type: SignUpRequest::class)])]
    public function signUp(#[RequestBody] SignUpRequest $signUpRequest): Response
    {
        return $this->json($this->signUpService->signUp($signUpRequest));
    }
}
