<?php

namespace App\Repository;

use App\Entity\BookChapter;
use App\Exception\BookChapterNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BookChapter>
 */
class BookChapterRepository extends ServiceEntityRepository
{
    use RepositoryModifyTrait;
    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookChapter::class);
    }

    public function getById(int $id): BookChapter
    {
        $chapter = $this->find($id);

        if (null === $chapter) {
            throw new BookChapterNotFoundException();
        }

        return $chapter;
    }
}
