# Создание первого сервиса

Создадим сущность BookCategory и соответствующий репозиторий.

Создадим модели:

-   BookCategoryListResponse - то что будет отдаваться пользователю
-   BookCategoryListItem - составная часть BookCategoryListResponse

Создадим сервис BookCategoryService.

```php
<?php

namespace App\Service;

use App\Entity\BookCategory;
use App\Model\BookCategoryListItem;
use App\Model\BookCategoryListResponse;
use App\Repository\BookCategoryRepository;

class BookCategoryService
{
    public function __construct(private BookCategoryRepository $bookCategoryRepository)
    {
    }

    public function getCategories(): BookCategoryListResponse
    {
        $categories = $this->bookCategoryRepository->findBy([], ['title' => 'ASC']);

        $items = array_map(fn (BookCategory $bookCategory) => new BookCategoryListItem(
            $bookCategory->getId(),
            $bookCategory->getTitle(),
            $bookCategory->getSlug(),
        ), $categories);

        return new BookCategoryListResponse($items);
    }
}
```

И применим его в контроллере.

```php
<?php

namespace App\Controller;

use App\Repository\BookRepository;
use App\Service\BookCategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookCategoryController extends AbstractController
{
    public function __construct(private BookCategoryService $bookCategoryService)
    {
    }

    #[Route('/')]
    public function categories(): Response
    {
        return $this->json($this->bookCategoryService->getCategories());
    }
}

```

Создадим миграцию с помощью следующей команды.

```
php bin/console doctrine:migrations:diff
```

Выполняем миграцию.

```
php bin/console doctrine:migrations:migrate
```

## Создание фикстур

Устанавливаем пакет orm-fixtures.

```
composer require --dev orm-fixtures
```

Создаем фикстуры BookCategoryFixtures.

```
php bin/console make:fixtures
```

```php
namespace App\DataFixtures;

use App\Entity\BookCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookCategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $manager->persist((new BookCategory())->setTitle("Database")->setSlug("database"));
        $manager->persist((new BookCategory())->setTitle("Android")->setSlug("android"));
        $manager->persist((new BookCategory())->setTitle("Network")->setSlug("network"));

        $manager->flush();
    }
}
```

Запускаем установку фикстур.

```
php bin/console doctrine:fixture:load --purge-with-truncate
```

## Тестирование

Установим необходимые пакеты.

```
composer require --dev symfony/test-pack

symfony composer req phpunit --dev
```

#### Важно

Возможно из-за того, что устанавливается PHPUnit 11 версии, конфиг в phpunit.xml.dist не соответствует. Чтобы в консоли не было "лишних" сообщений, часть атрибутов удалена, а часть тегов закомментирована.

Команда для запуска тестов.

```
php bin/phpunit
```

Создадим первый тест.

```
symfony console make:test TestCase Service\BookCategoryServiceTest
```

```php
<?php

namespace App\Tests\Service;

use App\Entity\BookCategory;
use App\Model\BookCategoryListItem;
use App\Model\BookCategoryListResponse;
use App\Repository\BookCategoryRepository;
use App\Service\BookCategoryService;
use Doctrine\Common\Collections\Criteria;
use PHPUnit\Framework\TestCase;

class BookCategoryServiceTest extends TestCase
{
    public function testGetCategories(): void
    {
        $repository = $this->createMock(BookCategoryRepository::class);

        $repository->expects($this->once())
            ->method("findBy")
            ->with([], ['title' => Criteria::ASC])
            ->willReturn([
                (new BookCategory())
                    ->setId(1)
                    ->setTitle('Test')
                    ->setSlug('test')
            ]);

        $service = new BookCategoryService($repository);

        $expected = new BookCategoryListResponse([
            new BookCategoryListItem(1, 'Test', 'test'),
        ]);

        $this->assertEquals($expected, $service->getCategories());
    }
}
```

## Swagger

Установим пакет nelmio/api-doc-bundle.

```
composer require nelmio/api-doc-bundle
```

Обновим config\packages\nelmio_api_doc.yaml.

```yml
nelmio_api_doc:
    documentation:
        info:
            title: Publisher API
            description: API for publishing books and more
            version: 1.0.0
    areas: # to filter documented areas
        path_patterns:
            #- ^/api(?!/doc$) # Accepts routes under /api except /api/doc
            - ^/api/v1
```

Устанавливаем пакеты для рендера документации и обновим config\routes\nelmio_api_doc.yaml.

```
composer require twig asset
```

```yml
app.swagger:
    path: /api/doc.json
    methods: GET
    defaults: { _controller: nelmio_api_doc.controller.swagger }

## Requires the Asset component and the Twig bundle
## $ composer require twig asset
app.swagger_ui:
    path: /api/doc
    methods: GET
    defaults: { _controller: nelmio_api_doc.controller.swagger_ui }
```

Документируем наш контроллер.

```php

// ...
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;

class BookCategoryController extends AbstractController
{
    #[Route('/api/v1/book/categories')]
    #[OA\Response(
        response: 200,
        description: 'Return book categories',
        content: new Model(type: BookCategoryListResponse::class)
    )]
    public function categories(): Response
    {
        return $this->json($this->bookCategoryService->getCategories());
    }
}

```

Открываем по адресу loc
