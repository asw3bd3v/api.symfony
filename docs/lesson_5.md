# Тесты

Пишем тесты для репозиториев.

Запуск миграций на тестовой базе.

```
php bin/console doctrine:migrations:migrate --env=test
```

Запуск тестов.

```
php bin/phpunit .\tests\Repository\BookRepositoryTest.php
```