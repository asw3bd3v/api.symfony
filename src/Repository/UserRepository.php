<?php

namespace App\Repository;

use App\Entity\User;
use App\Exception\UserNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function existsByEmail(string $email): bool
    {
        return null !== $this->findOneBy(["email" => $email]);
    }

    public function getUser(int $id): User
    {
        $user = $this->find($id);

        if (null === $user) {
            throw new UserNotFoundException();
        }

        return $user;
    }
}
