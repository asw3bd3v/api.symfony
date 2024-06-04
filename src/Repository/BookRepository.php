<?php

namespace App\Repository;

use App\Entity\Book;
use App\Exception\BookNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * @return Book[]
     */
    public function findPublishedBooksByCategoryId(int $id): array
    {
        return $this->getEntityManager()
            ->createQuery('SELECT b from App\Entity\Book b WHERE :categoryId MEMBER OF b.categories AND b.publicationDate IS NOT NULL')
            ->setParameter('categoryId', $id)
            ->getResult();
    }

    public function getPublishedById(int $id): Book
    {
        $book = $this->getEntityManager()
            ->createQuery('SELECT b from App\Entity\Book b WHERE b.id = :id AND b.publicationDate IS NOT NULL')
            ->setParameter('id', $id)
            ->getOneOrNullResult();

        if (null === $book) {
            throw new BookNotFoundException();
        }

        return $book;

        /* $book = $this->find($id);

        if (null === $book) {
            throw new BookNotFoundException();
        }

        return $book; */
    }

    /**
     * @return Book[]
     */
    public function findBooksByIds(array $ids): array
    {
        return $this->getEntityManager()
            ->createQuery('SELECT b from App\Entity\Book b WHERE b.id IN (:ids) AND b.publicationDate IS NOT NULL')
            ->setParameter('ids', $ids)
            ->getResult();

        //return $this->findBy(['id' => $ids]);
    }
}
