# Создание первого сервиса

Создадим сущность BookCategory и соответствующий репозиторий.

Создадим модели:

- BookCategoryListResponse - то что будет отдаваться пользователю
- BookCategoryListItem - составная часть BookCategoryListResponse

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