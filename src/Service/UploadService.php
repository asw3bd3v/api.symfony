<?php

namespace App\Service;

use App\Exception\UploadFileInvalidTypeException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Uuid;

class UploadService
{
    private const LINK_BOOK_PATTERN = '/upload/book/%d/%s';

    public function __construct(
        private Filesystem $filesystem,
        private string $uploadDir,
    ) {
    }

    public function uploadBookFile(int $id, UploadedFile $file): string
    {
        $extension = $file->guessExtension();

        if (null === $extension) {
            throw new UploadFileInvalidTypeException();
        }

        $uniqueName = Uuid::v4()->toRfc4122() . '.' . $extension;
        $uploadPath = $this->getUploadPathForBook($id);

        $file->move($uploadPath, $uniqueName);

        return sprintf(self::LINK_BOOK_PATTERN, $id, $uniqueName);
    }

    public function deleteBookFile(int $id, string $fileName): void
    {
        $this->filesystem->remove($this->getUploadPathForBook($id) . DIRECTORY_SEPARATOR . $fileName);
    }

    private function getUploadPathForBook(int $id): string
    {
        return $this->uploadDir . DIRECTORY_SEPARATOR . 'book' . DIRECTORY_SEPARATOR . $id;
    }
}
