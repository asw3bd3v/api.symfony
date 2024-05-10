<?php

namespace Service;

use App\Entity\Subscriber;
use App\Exception\SubscriberAlreadyExistsException;
use App\Model\SubscriberRequest;
use App\Repository\SubscriberRepository;
use Doctrine\ORM\EntityManagerInterface;

class SubscriberService
{
    public function __construct(
        private SubscriberRepository $subscriberRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function subscribe(SubscriberRequest $request): void
    {
        if ($this->subscriberRepository->existsByEmail($request->getEmail())) {
            throw new SubscriberAlreadyExistsException();
        }

        $subscriber = new Subscriber();
        $subscriber->setEmail($request->getEmail());

        $this->entityManager->persist($subscriber);
        $this->entityManager->flush();
    }
}
