<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\RoleService;
use App\Tests\AbstractTestCase;
use Doctrine\ORM\EntityManagerInterface;

class RoleServiceTest extends AbstractTestCase
{
    private $userRepository;
    private $entityManager;
    private $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->userRepository = $this->createMock(UserRepository::class);
        $this->userRepository->expects($this->once())
            ->method('getUser')
            ->with(1)
            ->willReturn($this->user);

        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->user = new User();
    }

    public function testGrantAdmin(): void
    {
        $this->createService()->grantAdmin(1);
        $this->assertEquals(['ROLE_ADMIN'], $this->user->getRoles());
    }

    public function testGrantAuthor(): void
    {
        $this->createService()->grantAuthor(1);
        $this->assertEquals(['ROLE_AUTHOR'], $this->user->getRoles());
    }

    private function createService(): RoleService
    {
        return new RoleService($this->userRepository, $this->entityManager);
    }
}
