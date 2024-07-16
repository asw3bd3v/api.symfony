<?php

namespace App\Service;

use App\Entity\BookChapter;
use App\Model\Author\CreateBookChapterRequest;
use App\Model\Author\UpdateBookChapterRequest;
use App\Model\IdResponse;
use App\Repository\BookChapterRepository;
use App\Repository\BookRepository;
use Symfony\Component\String\Slugger\SluggerInterface;

class AuthorBookChapterService
{
    public function __construct(
        private BookRepository $bookRepository,
        private BookChapterRepository $bookChapterRepository,
        private SluggerInterface $slugger,
    ) {
    }

    public function createChapter(CreateBookChapterRequest $request, int $bookId): IdResponse
    {
        $book = $this->bookRepository->getBookById($bookId);
        $title = $request->getTitle();

        $chapter = (new BookChapter)
            ->setTitle($title)
            ->setSlug($this->slugger->slug($title))
            ->setBook($book);

        $this->bookChapterRepository->saveAndCommit($chapter);

        return new IdResponse($chapter->getId());
    }

    public function updateChapter(UpdateBookChapterRequest $request): void
    {
        $chapter = $this->bookChapterRepository->getBookById($request->getId());
        $title = $request->getTitle();

        $chapter
            ->setTitle($title)
            ->setSlug($this->slugger->slug($title));

        $this->bookChapterRepository->saveAndCommit($chapter);
    }

    public function deleteChapter(int $id): void
    {
        $chapter = $this->bookChapterRepository->getBookById($id);

        $this->bookChapterRepository->removeAndCommit($chapter);
    }
}
