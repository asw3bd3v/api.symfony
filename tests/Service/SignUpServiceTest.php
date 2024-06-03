<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Exception\UserAlreadyExistsException;
use App\Model\SignUpRequest;
use App\Repository\UserRepository;
use App\Service\SignUpService;
use App\Tests\AbstractTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;

class SignUpServiceTest extends AbstractTestCase
{
    private $hasher;
    private $userRepository;
    private $entityManager;
    private $successHandler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->hasher = $this->createMock(UserPasswordHasher::class);
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->successHandler = $this->createMock(AuthenticationSuccessHandler::class);
    }

    public function testSignUpUserAlreadyExists(): void
    {
        $this->expectException(UserAlreadyExistsException::class);

        $this->userRepository->expects($this->once())
            ->method('existsByEmail')
            ->with('test@some.where')
            ->willReturn(true);

        $this->createService()->signUp((new SignUpRequest())->setEmail('test@some.where'));
    }

    public function testSignUp(): void
    {
        $response = new Response();

        $expectedHasherUser = (new User())
            ->setRoles(['ROLE_USER'])
            ->setFirstName('John')
            ->setLastName('Smith')
            ->setEmail('test@some.where');

        $expectedUser = clone $expectedHasherUser;
        $expectedUser->setPassword('hashed_password');

        $this->userRepository->expects($this->once())
            ->method('existsByEmail')
            ->with('test@some.where')
            ->willReturn(false);

        $this->hasher->expects($this->once())
            ->method('hashPassword')
            ->with($expectedHasherUser, 'testtest')
            ->willReturn('hashed_password');

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($expectedUser);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->successHandler->expects($this->once())
            ->method('handleAuthenticationSuccess')
            ->with($expectedUser)
            ->willReturn($response);

        $signUpRequest = (new SignUpRequest())
            ->setFirstName('John')
            ->setLastName('Smith')
            ->setEmail('test@some.where')
            ->setPassword('testtest');

        $this->assertEquals($response, $this->createService()->signUp($signUpRequest));
    }

    private function createService(): SignUpService
    {
        return new SignUpService($this->hasher, $this->userRepository, $this->entityManager, $this->successHandler);
    }
}
