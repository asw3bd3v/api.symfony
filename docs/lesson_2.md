# Начало проекта

## Установка приложения

```
symfony new api.symfony --version="6.4.*"
```

## Установка пакета orm-pack

```
composer require symfony/orm-pack
```

## Первый контроллер

```php
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/')]
    public function root(): Response
    {
        return $this->json([
            'test' => 'hello'
        ]);
    }
}
```

## Запуск локального сервера

```
symfony serve
```

## Установка MakerBundle

```
composer require --dev symfony/maker-bundle
```

### Создание сущности

```
php bin/console make:entity
```

### Создание миграции

```
php bin/console make:migration
```

#### Внимание

Далее возникает ошибка, если мы пользуемся docker.

Создаем файл .env.local.

```
symfony var:export --multiline > .env.local
```

Почему то файл будет создан не в utf-8, поэтому его нужно переконвертировать в utf-8.

Еще раз запускаем команду создания миграции.

```
php bin/console make:migration
```

### Выполнение миграции

```
php bin/console doctrine:migrations:migrate
```