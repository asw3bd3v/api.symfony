<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use App\Entity\User;

class JwtCreatedListener
{
    public function __invoke(JWTCreatedEvent $event)
    {
        /** @var User $user */
        $user = $event->getUser();
        $payload = $event->getData();

        $payload['id'] = $user->getId();

        $event->setData($payload);
    }
}
