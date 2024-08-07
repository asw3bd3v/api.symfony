<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\BookChapter;
use App\Exception\BookChapterNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
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

    public function getMaxSort(Book $book, int $level): int
    {
        return (int) $this->getEntityManager()
            ->createQuery('SELECT MAX(c.sort) FROM App\Entity\BookChapter c WHERE c.book = :book AND c.level = :level')
            ->setParameter('book', $book)
            ->setParameter('level', $level)
            ->getSingleScalarResult();
    }

    public function increaseSortFrom(int $sortStart, Book $book, int $level, int $sortStep = 1): void
    {
        $sql = <<<SQL
UPDATE App\Entity\BookChapter c SET c.sort = c.sort + :sortStep
WHERE c.sort >= :sortStart AND c.book = :book AND c.level = :level
SQL;

        $this->getEntityManager()->createQuery($sql)
            ->setParameter('book', $book)
            ->setParameter('level', $level)
            ->setParameter('sortStart', $sortStart)
            ->setParameter('sortStep', $sortStep)
            ->execute();
    }

    /**
     * @return BookChapter[]
     */
    public function findSortedChaptersByBook(Book $book): array
    {
        return $this->findBy(
            ['book' => $book],
            ['level' => Criteria::ASC, 'sort' => Criteria::ASC]
        );
    }
}
