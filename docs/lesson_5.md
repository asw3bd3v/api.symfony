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

Устанавливаем пакет helmich/phpunit-json-assert для функциональных тестов.

```
composer require --dev helmich/phpunit-json-assert
```

Важно: на текущий момент пакет не совместим с PHPUnit 11. Поэтому в файле E:\OSPanel6\home\api.symfony\vendor\helmich\phpunit-json-assert\src\Constraint\JsonValueMatchesSchema.php изменен мною конструктор.

```php
public function __construct($schema)
    {
        //parent::__construct();
        $this->schema = $this->forceToObject($schema);
    }
```