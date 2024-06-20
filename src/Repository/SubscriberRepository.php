<?php

namespace App\Repository;

use App\Entity\Subscriber;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Subscriber>
 */
class SubscriberRepository extends ServiceEntityRepository
{
    use RepositoryModifyTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subscriber::class);
    }

    public function existsByEmail(string $email): bool
    {
        return null !== $this->findOneBy(["email" => $email]);
    }
}
