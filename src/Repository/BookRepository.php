<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\BookToBookFormat;
use App\Exception\BookNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function save(Book $book): void
    {
        $this->getEntityManager()->persist($book);
    }

    public function commit(): void
    {
        $this->getEntityManager()->flush();
    }

    public function saveAndCommit(Book $book): void
    {
        $this->save($book);
        $this->commit();
    }

    public function remove(Book $book): void
    {
        $this->getEntityManager()->remove($book);
    }

    public function removeAndCommit(Book $book): void
    {
        $this->remove($book);
        $this->commit();
    }

    public function saveBookFormatReference(BookToBookFormat $bookToBookFormat): void
    {
        $this->getEntityManager()->persist($bookToBookFormat);
    }

    public function removeBookFormatReference(BookToBookFormat $bookToBookFormat): void
    {
        $this->getEntityManager()->remove($bookToBookFormat);
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

    /**
     * @return Book[]
     */
    public function findUserBooks(UserInterface $user): array
    {
        return $this->findBy(['user' => $user]);
    }

    public function getUserBookById(int $id, UserInterface $user): ?Book
    {
        $book = $this->findOneBy(['id' => $id, 'user' => $user]);

        if (null === $book) {
            throw new BookNotFoundException();
        }

        return $book;
    }

    public function getBookById(int $id): ?Book
    {
        $book = $this->find($id);

        if (null === $book) {
            throw new BookNotFoundException();
        }

        return $book;
    }

    public function existsBySlug(string $slug): bool
    {
        return null !== $this->findOneBy(['slug' => $slug]);
    }

    public function existsUserBookById(int $id, UserInterface $user): bool
    {
        return null !== $this->findOneBy(['id' => $id, 'user' => $user]);
    }
}
