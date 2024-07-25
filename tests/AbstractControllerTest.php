<?php

namespace App\Tests;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Helmich\JsonAssert\JsonAssertions;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

abstract class AbstractControllerTest extends WebTestCase
{
    use JsonAssertions;
    protected KernelBrowser $client;

    protected ?EntityManagerInterface $entityManager;

    protected UserPasswordHasherInterface $hasher;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();

        $this->entityManager = self::getContainer()->get('doctrine.orm.entity_manager');
        $this->hasher = self::getContainer()->get('security.user_password_hasher');
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->restoreExceptionHandler();

        $this->entityManager->close();
        $this->entityManager = null;
    }

    protected function restoreExceptionHandler(): void
    {
        while (true) {
            $previousHandler = set_exception_handler(static fn () => null);

            restore_exception_handler();

            if (null === $previousHandler) {
                break;
            }

            restore_exception_handler();
        }
    }

    protected function auth(string $username, string $password): void
    {
        $this->client->request(
            'POST',
            '/api/v1/auth/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['username' => $username, 'password' => $password]),
        );

        $this->assertResponseIsSuccessful();

        $data = json_decode($this->client->getResponse()->getContent(), true);

        $this->client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));
    }

    protected function createUser(string $username, string $password,): User
    {
        return $this->createUserWithRoles($username, $password, ['ROLE_USER']);
    }

    protected function createAdmin(string $username, string $password,): User
    {
        return $this->createUserWithRoles($username, $password, ['ROLE_ADMIN']);
    }

    protected function createAuthor(string $username, string $password): User
    {
        return $this->createUserWithRoles($username, $password, ['ROLE_AUTHOR']);
    }

    protected function createAdminAndAuth(string $username, string $password): User
    {
        $user = $this->createAdmin($username, $password);
        $this->auth($username, $password);

        return $user;
    }

    protected function createAuthorAndAuth(string $username, string $password): User
    {
        $user = $this->createAuthor($username, $password);
        $this->auth($username, $password);

        return $user;
    }

    protected function createUserAndAuth(string $username, string $password): User
    {
        $user = $this->createUser($username, $password);
        $this->auth($username, $password);

        return $user;
    }

    private function createUserWithRoles(string $username, string $password, array $roles): User
    {
        $user = (new User())
            ->setRoles($roles)
            ->setLastName($username)
            ->setFirstName($username)
            ->setEmail($username);

        $user->setPassword($this->hasher->hashPassword($user, $password));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
