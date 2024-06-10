<?php

namespace App\Controller;

use App\Attribute\RequestBody;
use App\Attribute\RequestFile;
use App\Model\Author\BookListResponse;
use App\Model\Author\CreateBookRequest;
use App\Model\Author\PublishBookRequest;
use App\Model\Author\UploadCoverResponse;
use App\Model\ErrorResponse;
use App\Model\IdResponse;
use App\Service\AuthorService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotNull;

class AuthorController extends AbstractController
{
    public function __construct(private AuthorService $authorService)
    {
    }

    #[Route(path: '/api/v1/author/book/{id}/publish', methods: ['POST'])]
    #[OA\Tag(name: 'Author API')]
    #[OA\Response(response: 200, description: 'Publish a book')]
    #[OA\Response(response: 400, description: 'Validation failed', attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\RequestBody(attachables: [new Model(type: PublishBookRequest::class)])]
    public function publish(int $id, #[RequestBody] PublishBookRequest $request): Response
    {
        $this->authorService->publish($id, $request);

        return $this->json(null);
    }

    #[Route(path: '/api/v1/author/book/{id}/uploadCover', methods: ['POST'])]
    #[OA\Tag(name: 'Author API')]
    #[OA\Response(response: 200, description: 'Upload book cover', attachables: [new Model(type: UploadCoverResponse::class)])]
    #[OA\Response(response: 400, description: 'Validation failed', attachables: [new Model(type: ErrorResponse::class)])]
    public function uploadCover(
        int $id,
        #[RequestFile(field: 'cover', constraints: [
            new NotNull(),
            new Image(maxSize: '1M', mimeTypes: ['image/jpeg', 'image/png', 'image/jpg']),
        ])] UploadedFile $file
    ): Response {
        return $this->json($this->authorService->uploadCover($id, $file));
    }

    #[Route(path: '/api/v1/author/book/{id}/unpublish', methods: ['POST'])]
    #[OA\Tag(name: 'Author API')]
    #[OA\Response(response: 200, description: 'Unpublish a book')]
    public function unpublish(int $id): Response
    {
        $this->authorService->unpublish($id);

        return $this->json(null);
    }

    #[Route(path: "/api/v1/author/books", name: "author_books", methods: ["GET"])]
    #[OA\Tag(name: 'Author API')]
    #[OA\Response(
        response: 200,
        description: 'Get authors owned books',
        attachables: [new Model(type: BookListResponse::class)]
    )]
    public function books(): Response
    {
        return $this->json($this->authorService->getBooks());
    }

    #[Route(
        path: "/api/v1/author/book",
        name: "author_book_create",
        methods: ["POST"]
    )]
    #[OA\Tag(name: 'Author API')]
    #[OA\Response(response: 200, description: 'Create a book', attachables: [new Model(type: IdResponse::class)])]
    #[OA\Response(response: 400, description: 'Validation failed', attachables: [new Model(type: ErrorResponse::class)])]
    #[OA\RequestBody(attachables: [new Model(type: CreateBookRequest::class)])]
    public function createBook(#[RequestBody] CreateBookRequest $request): Response
    {
        return $this->json($this->authorService->createBook($request));
    }

    #[Route(
        path: "/api/v1/author/book/{id}",
        name: "author_book_delete",
        methods: ["DELETE"]
    )]
    #[OA\Tag(name: 'Author API')]
    #[OA\Response(response: 200, description: 'Remove a book')]
    #[OA\Response(response: 404, description: 'book not found', attachables: [new Model(type: ErrorResponse::class)])]
    public function deleteBook(int $id): Response
    {
        $this->authorService->deleteBook($id);

        return $this->json(null);
    }
}
